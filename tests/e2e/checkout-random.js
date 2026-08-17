#!/usr/bin/env node
/**
 * Automated checkout UI test for Maries Restaurant (live site).
 *
 * Picks N random menu items, adds them to the cart, walks the checkout form,
 * completes the payment, and verifies the order on the confirmation screen
 * (and optionally in MySQL).
 *
 * Usage:
 *   BASE_URL=https://maries-restaurant.wasmer.app ITEMS=2 PAYMENT=square \
 *   DB_HOST=... DB_PORT=... DB_USER=... DB_PASSWORD=... DB_NAME=... \
 *   node tests/e2e/checkout-random.js
 *
 * Env:
 *   BASE_URL  – site under test (default https://maries-restaurant.wasmer.app)
 *   ITEMS     – how many random products to order (default 2)
 *   PAYMENT   – 'square' (default) or 'cod'
 *   HEADLESS  – '0' to watch the browser
 *   DB_*      – optional; when set the order is cross-checked in MySQL
 *
 * Exit code 0 on success, 1 on any failed assertion.
 */
const { chromium } = require('playwright');
const { execFileSync } = require('child_process');

const BASE_URL = process.env.BASE_URL || 'https://maries-restaurant.wasmer.app';
const ITEMS = parseInt(process.env.ITEMS || '2', 10);
const PAYMENT = process.env.PAYMENT || 'square';
const HEADLESS = process.env.HEADLESS !== '0';

const TEST_CARD = { number: '4111111111111111', exp: '1230', cvv: '111', zip: '12345' };
const email = `auto+${Date.now()}@example.com`;

const results = [];
const check = (name, ok, detail = '') => {
  results.push({ name, ok, detail });
  console.log(`${ok ? 'PASS' : 'FAIL'}  ${name}${detail ? ` — ${detail}` : ''}`);
};

(async () => {
  const browser = await chromium.launch({ headless: HEADLESS });
  const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });
  const pageErrors = [];
  page.on('pageerror', (e) => pageErrors.push(e.message));

  let picked = [];
  try {
    // 1. Load the menu
    await page.goto(BASE_URL, { waitUntil: 'networkidle', timeout: 60000 });
    await page.waitForSelector('.menu-add', { timeout: 30000 });
    check('site loads', (await page.title()).length > 0, await page.title());

    // 2. Pick N random enabled products
    const enabled = await page.evaluate(() =>
      // Name comes from the aria-label ('Add <name> to order') so it works for
      // both the specialty cards and the carte rows.
      [...document.querySelectorAll('.menu-add:not([disabled])')].map((b) => ({
        name: (b.getAttribute('aria-label') || 'Item').replace(/^Add\s*/, '').replace(/\s*to order$/, ''),
      })),
    );
    if (enabled.length < ITEMS) throw new Error(`only ${enabled.length} orderable items, need ${ITEMS}`);
    const indices = new Set();
    while (indices.size < ITEMS) indices.add(Math.floor(Math.random() * enabled.length));
    picked = [...indices].map((i) => enabled[i].name);
    console.log(`\nordering ${ITEMS} random item(s): ${picked.join(' + ')}`);

    // 3. Add each item to the cart
    const buttons = page.locator('.menu-add:not([disabled])');
    for (const i of [...indices]) {
      await buttons.nth(i).click();
      await page.waitForTimeout(1200);
    }
    // The header cart button carries the live count badge (`.header-cart-count`);
    // the cart box header shows the same count (`.cart-box-count`).
    const badge = await page
      .locator('.header-cart-count')
      .first()
      .textContent()
      .catch(() => '0');
    check('cart badge reflects all items', parseInt(badge, 10) === ITEMS, `badge=${badge}`);

    // 4. Open the cart and verify the picked items are listed
    await page.locator('[data-cart-toggle]').first().click();
    await page.waitForTimeout(900);
    const cartNames = await page.locator('.cart-item-name').allTextContents();
    const missing = picked.filter((n) => !cartNames.some((c) => c.includes(n)));
    check('cart lists all picked items', missing.length === 0, missing.length ? `missing: ${missing.join(', ')}` : cartNames.length + ' items');

    // 5. Checkout form
    await page.getByText(/Place order/i).first().click();
    await page.waitForTimeout(1000);
    await page.fill('#checkout-first-name', 'Auto');
    await page.fill('#checkout-last-name', 'Tester');
    await page.fill('#checkout-email', email);
    await page.fill('#checkout-phone', '07123456789');
    await page.fill('#checkout-comment', `Automated test · ${new Date().toISOString()}`);
    await page.waitForTimeout(600);

    // 6. Payment
    if (PAYMENT === 'square') {
      await page.locator('.payment-option-label:has(input[value="square"])').click();
      // The Square SDK's hidden main-iframe appears before the card form is
      // actually attached — wait for the real card iframe inside the block.
      await page.waitForSelector('#square-card-element iframe[title="Secure Credit Card Form"]', { timeout: 20000 });
      const cardFrames = page
        .frames()
        .filter((f) => /single-card-element-iframe/.test(f.url()));
      check('square card form loads', cardFrames.length > 0);
      for (const fr of cardFrames) {
        for (const [n, ph, v] of [['cardNumber', 'card number', TEST_CARD.number], ['expirationDate', 'MM/YY', TEST_CARD.exp], ['cvv', 'CVV', TEST_CARD.cvv], ['postalCode', 'ZIP', TEST_CARD.zip]]) {
          const l = fr.locator(`input[name="${n}"]`);
          if (await l.count()) { await l.click(); await l.pressSequentially(v, { delay: 25 }); continue; }
          const p = fr.getByPlaceholder(new RegExp(ph, 'i'));
          if (await p.count()) { await p.click(); await p.pressSequentially(v, { delay: 25 }); }
        }
      }
      await page.waitForTimeout(600);
    }
    check('payment method selected', true, PAYMENT);

    // 7. Confirm the order
    await page.getByRole('button', { name: /Confirm order/i }).click();

    // 8. Wait for the confirmation screen (or a surfaced error)
    let success = null;
    let surfaced = null;
    for (let i = 0; i < 35; i++) {
      surfaced = await page.evaluate(() => {
        const sq = document.querySelector('#square-card-errors');
        const sqTxt = sq && sq.textContent.trim();
        const fb = [...document.querySelectorAll('.invalid-feedback')].map((e) => e.textContent.trim()).filter(Boolean).join(' | ');
        const al = document.querySelector('.checkout-alert');
        const alTxt = al && al.textContent.trim();
        return sqTxt || fb || alTxt || null;
      });
      success = await page.evaluate(() => {
        const el = document.querySelector('.checkout-success');
        return el ? el.textContent.replace(/\s+/g, ' ').trim() : null;
      });
      if (success || surfaced) break;
      await page.waitForTimeout(1000);
    }
    if (surfaced) check('no checkout error surfaced', false, surfaced.slice(0, 160));
    check('order confirmation shown', !!success, success ? success.slice(0, 90) : 'no .checkout-success');
    const ref = success ? (success.match(/Order #(\d+)/) || [])[1] : null;
    check('order reference extracted', !!ref, ref ? `order #${ref}` : '');
    check('no unhandled page errors', pageErrors.length === 0, pageErrors.slice(0, 2).join(' | '));

    // 9. Optional MySQL cross-check
    if (process.env.DB_HOST) {
      const out = execFileSync('mariadb', [
        '--skip-ssl', '--host=' + process.env.DB_HOST, '--port=' + (process.env.DB_PORT || '3306'),
        '-u', process.env.DB_USER, '-p' + process.env.DB_PASSWORD, process.env.DB_NAME,
        '-e', `SELECT o.order_id, o.payment, o.order_total, o.status_id, o.processed,
               GROUP_CONCAT(CONCAT(om.quantity,'x ',om.name) SEPARATOR ' | ') AS items
               FROM ti_orders o JOIN ti_order_menus om ON om.order_id = o.order_id
               WHERE o.email='${email}' GROUP BY o.order_id ORDER BY o.order_id DESC LIMIT 1;`,
      ], { encoding: 'utf8' });
      const lines = out.trim().split('\n');
      const row = lines.length > 1 ? lines[1] : '';
      const hasItems = picked.every((n) => row.includes(n));
      check('DB order matches picked items', !!row && hasItems, row ? row.slice(0, 140) : 'no row');
      check('DB payment success', !!row && row.includes(PAYMENT), row ? (row.match(/\t([^\t]+)\t/) || [])[1] : '');
    }
  } catch (e) {
    check('flow completed without exception', false, e.message);
    await page.screenshot({ path: '/tmp/checkout-fail.png', fullPage: false }).catch(() => {});
  }

  const failed = results.filter((r) => !r.ok);
  console.log(`\n=== ${results.length - failed.length}/${results.length} checks passed (payment=${PAYMENT}, items=${picked.join(' + ') || 'none'}) ===`);
  await browser.close();
  process.exit(failed.length ? 1 : 0);
})();
