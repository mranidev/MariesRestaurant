/* */
(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Scrolls to an element with header offset
   */
  const scrollto = (el) => {
    let header = select('#header')
    let offset = header.offsetHeight

    let elementPos = select(el).offsetTop
    window.scrollTo({
      top: elementPos - offset,
      behavior: 'smooth'
    })
  }

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  let selectTopbar = select('#topbar')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
        if (selectTopbar) {
          selectTopbar.classList.add('topbar-scrolled')
        }
      } else {
        selectHeader.classList.remove('header-scrolled')
        if (selectTopbar) {
          selectTopbar.classList.remove('topbar-scrolled')
        }
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Mobile nav toggle
   */
  on('click', '.mobile-nav-toggle', function(e) {
    select('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /**
   * Mobile nav dropdowns activate
   */
  on('click', '.navbar .dropdown > a', function(e) {
    if (select('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)

  /**
   * Scrool with ofset on links with a class name .scrollto
   */
  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault()

      let navbar = select('#navbar')
      if (navbar.classList.contains('navbar-mobile')) {
        navbar.classList.remove('navbar-mobile')
        let navbarToggle = select('.mobile-nav-toggle')
        navbarToggle.classList.toggle('bi-list')
        navbarToggle.classList.toggle('bi-x')
      }
      scrollto(this.hash)
    }
  }, true)

  /**
   * Scroll with ofset on page load with hash links in the url
   */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) {
        scrollto(window.location.hash)
      }
    }
  });

  /**
   * Hero carousel indicators
   */
  let heroCarouselIndicators = select("#hero-carousel-indicators")
  let heroCarouselItems = select('#heroCarousel .carousel-item', true)

  heroCarouselItems.forEach((item, index) => {
    (index === 0) ?
    heroCarouselIndicators.innerHTML += "<li data-bs-target='#heroCarousel' data-bs-slide-to='" + index + "' class='active'></li>":
      heroCarouselIndicators.innerHTML += "<li data-bs-target='#heroCarousel' data-bs-slide-to='" + index + "'></li>"
  });

  /**
   * Testimonials slider
   */
  new Swiper('.events-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });


  /**
   * Testimonials slider
   */
  new Swiper('.testimonials-slider', {
    speed: 600,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /**
   * Open and close the order cart drawer (Bootstrap offcanvas)
   */
  let cartDrawer = select('#cartDrawer')
  let mariesCart = select('#maries-cart, .cart-box-root')
  if (cartDrawer || mariesCart) {
    window.toggleMariesCart = () => {
      if (cartDrawer && window.bootstrap) {
        const drawer = bootstrap.Offcanvas.getOrCreateInstance(cartDrawer)
        drawer.toggle()
        cartDrawer.addEventListener('shown.bs.offcanvas', () => {
          document.querySelectorAll('[data-cart-toggle]').forEach((btn) => {
            btn.setAttribute('aria-expanded', 'true')
          })
        })
        cartDrawer.addEventListener('hidden.bs.offcanvas', () => {
          document.querySelectorAll('[data-cart-toggle]').forEach((btn) => {
            btn.setAttribute('aria-expanded', 'false')
          })
        })
      } else if (mariesCart) {
        const open = mariesCart.classList.toggle('open')
        document.querySelectorAll('[data-cart-toggle]').forEach((btn) => {
          btn.setAttribute('aria-expanded', String(open))
        })
      }
    }

    // Keep the header + bottom-nav cart badges in sync with the order count.
    const syncHeaderCart = () => {
      const headerBtn = select('.header-cart-btn')
      const badge = headerBtn && headerBtn.querySelector('.header-cart-count')
      const mbnBtn = select('.mobile-bottom-nav .mbn-cart')
      const mbnBadge = mbnBtn && mbnBtn.querySelector('.mbn-cart-count')
      const boxCount = select('.cart-box-count')
      if (boxCount) {
        const n = parseInt(boxCount.textContent, 10) || 0
        if (headerBtn && badge) {
          badge.textContent = n
          badge.hidden = n <= 0
        }
        if (mbnBtn && mbnBadge) {
          mbnBadge.textContent = n
          mbnBadge.hidden = n <= 0
        }
      }
      // Keep the header cart total (e.g. £0.00) in sync with the running sum.
      const boxTotal = select('.cart-box-total')
      const headerTotal = headerBtn && headerBtn.querySelector('.header-cart-total')
      if (boxTotal && headerTotal) {
        const total = boxTotal.textContent.trim()
        if (total) headerTotal.textContent = total
      }
    }

    // Close the drawer automatically if the last item is removed.
    document.addEventListener('livewire:init', () => {
      Livewire.hook('morph.updated', ({ el }) => {
        const boxCount = select('.cart-box-count')
        const count = boxCount ? parseInt(boxCount.textContent, 10) : 0
        if (cartDrawer && cartDrawer.classList.contains('show') && count === 0) {
          bootstrap.Offcanvas.getOrCreateInstance(cartDrawer).hide()
          document.querySelectorAll('[data-cart-toggle]').forEach((btn) => {
            btn.setAttribute('aria-expanded', 'false')
          })
        }
        syncHeaderCart()
      })
    })

    // Initial badge state on load.
    syncHeaderCart()
  }


  /**
   * Square card payments (sandbox). The card form is initialised lazily when
   * the customer selects "Pay by card", and re-initialised after Livewire
   * morphs the checkout form. On submit the card is tokenized, the buyer is
   * verified, and the nonce + verification token are pushed into the CartBox
   * component state before the Livewire submit proceeds.
   */
  let squarePayments = null
  let squareCard = null
  let squareBlock = null

  const initSquareCard = async (block) => {
    if (!block || block.dataset.initialized === '1') return
    if (!window.Square) return

    const cardEl = block.querySelector('#square-card-element')
    if (!cardEl) return

    // Mark synchronously BEFORE the async attach so overlapping calls (the
    // 800ms poller plus Livewire's morph hook) can't attach a second card
    // form — Square's attach() appends a fresh element on every call.
    block.dataset.initialized = '1'

    try {
      squarePayments = window.Square.payments(block.dataset.appId, block.dataset.locationId)
      squareCard = await squarePayments.card({
        style: {
          input: {
            fontSize: '16px',
            color: '#1a1814',
          },
        },
      })
      await squareCard.attach('#square-card-element')
    } catch (e) {
      // Reset so the poller can retry (e.g. the SDK wasn't ready yet).
      block.dataset.initialized = ''
      const errEl = block.querySelector('#square-card-errors')
      if (!errEl) return
      const msg = squareCardError(e)
      errEl.textContent = msg
      // Surface the underlying cause in the console for diagnosis.
      if (typeof console !== 'undefined' && console.error) console.error('Square card init failed:', e)
    }
  }

  /**
   * Turns a Square SDK failure into a message the customer can act on.
   * Square Web Payments only runs on a secure (HTTPS) origin that is
   * registered for the app — on a local preview or any other origin it
   * throws before the card form can attach.
   */
  const squareCardError = (e) => {
    const isSecure = window.location.protocol === 'https:'
    const host = window.location.hostname || ''
    const liveHost = 'maries-restaurant.wasmer.app'
    if (!isSecure || (host !== liveHost && host !== '127.0.0.1' && host !== 'localhost')) {
      return 'Card payments are only available on the live site (https://' + liveHost + '). Please open it there, or pay on pickup.'
    }
    const detail = e && (e.message || e.code || e.errors)
    return detail
      ? 'The card form could not be loaded: ' + String(detail).slice(0, 120)
      : 'The card form could not be loaded. Please try again.'
  }

  // Attach the card form to the CURRENT visible block. If Livewire replaced
  // the block (morph) after an attach, the iframe is gone — re-attach so the
  // card form is always live in the block the customer can actually see.
  const ensureSquareCard = async () => {
    const block = select('#square-block')
    if (!block || block.hidden || !block.dataset.appId) return
    const cardEl = block.querySelector('#square-card-element')
    if (!cardEl) return
    if (block.dataset.initialized === '1') return
    if (cardEl.querySelector('iframe')) {
      block.dataset.initialized = '1'
      return
    }
    await initSquareCard(block)
  }

  const findCartBoxComponent = (form) => {
    const root = form.closest('[wire\\:id]')
    if (root && window.Livewire && Livewire.find) {
      return Livewire.find(root.getAttribute('wire:id'))
    }
    return null
  }

  const handleSquareFlow = async (form) => {
    const nonceInput = form.querySelector('input[name="square_card_nonce"]')
    const errorsEl = form.querySelector('#square-card-errors')
    const component = findCartBoxComponent(form)

    const placeOrder = () => {
      if (component) component.call('onPlaceOrder')
    }

    if (!nonceInput) {
      if (errorsEl) errorsEl.textContent = 'The card form is not ready. Please try again.'
      return
    }

    // The card form can still be attaching (it takes a second or two after the
    // customer selects "Pay by card") — wait briefly for it before giving up.
    for (let i = 0; i < 10 && !squareCard; i++) {
      await new Promise((r) => setTimeout(r, 400))
    }
    if (!squareCard) {
      if (errorsEl) errorsEl.textContent = 'The card form is not ready. Please try again.'
      return
    }

    // A nonce is already present — just place the order.
    if (nonceInput.value) return placeOrder()

    const tokenResult = await squareCard.tokenize()
    if (tokenResult.errors) {
      if (errorsEl) {
        errorsEl.innerHTML = ''
        tokenResult.errors.forEach((e) => {
          const div = document.createElement('div')
          div.textContent = e.message
          errorsEl.appendChild(div)
        })
      }
      return
    }

    if (!squareBlock) squareBlock = form.querySelector('#square-block')

    const verificationDetails = {
      intent: 'CHARGE',
      amount: Math.round(parseFloat(squareBlock.dataset.orderTotal) * 100).toString(),
      currencyCode: squareBlock.dataset.currencyCode,
      billingContact: {
        givenName: (form.querySelector('#checkout-first-name') || {}).value || '',
        familyName: (form.querySelector('#checkout-last-name') || {}).value || '',
      },
    }

    let verificationToken = ''
    try {
      const result = await squarePayments.verifyBuyer(tokenResult.token, verificationDetails)
      verificationToken = result.token
    } catch (e) {
      if (errorsEl) errorsEl.textContent = 'We could not verify your card. Please try again.'
      return
    }

    const tokenInput = form.querySelector('input[name="square_card_token"]')
    nonceInput.value = tokenResult.token
    if (tokenInput) tokenInput.value = verificationToken

    // Push the values into the CartBox component, then call the order action
    // directly once they are in place.
    if (component) {
      await component.set('squareNonce', tokenResult.token)
      await component.set('squareToken', verificationToken)
    }
    placeOrder()
  }

  document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', ({ el }) => {
      squareBlock = el.querySelector && el.querySelector('#square-block')
      if (squareBlock && !squareBlock.hidden && squareBlock.dataset.appId) {
        ensureSquareCard()
      }
    })

    // Lazy-init once the checkout form is rendered and Square is selected.
    const t = setInterval(() => {
      const block = select('#square-block')
      if (!block) return
      if (block.dataset.initialized === '1') {
        clearInterval(t)
        return
      }
      ensureSquareCard()
    }, 800)
  })

  /**
   * Inline booking calendar (flatpickr), matching the reference reservation
   * page. Lives inside a wire:ignore container, so it survives Livewire
   * morphs; we re-initialise whenever the picker step is re-rendered.
   */
  const initBookingCalendar = () => {
    if (!window.flatpickr) return
    // Initialise every booking calendar (homepage section, reservation modal,
    // /reservation page) — scoped by class so duplicate instances don't clash.
    select('.booking-date-hidden', true).forEach((input) => {
      // flatpickr renders the inline calendar as a sibling of the input; if
      // Livewire hydration wiped it, this recreates it (presence-checked).
      const wrapper = input.closest('.col-md-8')
      if (wrapper && wrapper.querySelector('.flatpickr-calendar')) return
      flatpickr(input, {
        inline: true,
        static: true,
        mode: 'single',
        dateFormat: 'Y-m-d',
        minDate: input.dataset.minDate || undefined,
        maxDate: input.dataset.maxDate || undefined,
        defaultDate: input.value || undefined,
        onChange: (selectedDates, dateStr) => {
          if (dateStr) {
            input.value = dateStr
            input.dispatchEvent(new Event('input', { bubbles: true }))
          }
        }
      })
    })
  }
  initBookingCalendar()
  document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => initBookingCalendar())
    Livewire.hook('morph.added', () => initBookingCalendar())
  })
  // Livewire's initial hydration re-renders the component and can drop the
  // inline calendar; give it a few chances to come back after mount.
  setTimeout(initBookingCalendar, 500)
  setTimeout(initBookingCalendar, 1500)

  /**
   * EN/AR language switch. Posts the chosen locale to the session-backed
   * /locale/{locale} route (CSRF-protected), then reloads so TastyIgniter's
   * Localization middleware applies it.
   */
  window.switchMariesLocale = (locale) => {
    const token = document.querySelector('meta[name="csrf-token"]')
    fetch('/locale/' + encodeURIComponent(locale), {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': token ? token.getAttribute('content') : '',
        'Accept': 'application/json'
      }
    })
      .then(() => window.location.reload())
      .catch(() => window.location.reload())
  }

  // Intercept the checkout form submit when Square is the selected payment.
  // The listener is delegated on document (the checkout form only exists in
  // the DOM once the cart has items and the form is open) and runs in the
  // CAPTURE phase so it beats Livewire's bubble-phase wire:submit listener.
  // We stop the event, tokenize the card, then call onPlaceOrder directly via
  // the component once the nonce is in place.
  document.addEventListener('submit', (e) => {
    const form = e.target && e.target.closest ? e.target.closest('.checkout-form') : null
    if (!form) return
    const checked = form.querySelector('input[name="payment"]:checked')
    if (!checked || checked.value !== 'square') return
    e.preventDefault()
    e.stopImmediatePropagation()
    handleSquareFlow(form)
  }, true)

})()
