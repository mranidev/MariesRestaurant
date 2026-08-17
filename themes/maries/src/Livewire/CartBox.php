<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\Cart\Classes\CartManager;
use Igniter\Cart\Classes\OrderManager;
use Igniter\Cart\Models\Order;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\Local\Facades\Location;
use Igniter\User\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

/**
 * Floating order cart + checkout.
 *
 * The checkout lives in the same component as the cart so the "Place order"
 * button, form and confirmation always re-render in sync with the cart
 * contents (separate Livewire components on the same page do not re-render
 * when the cart changes).
 *
 * Orders are placed through the Cart extension's OrderManager. The store only
 * offers collection (pick-up) — no delivery areas are configured. Cash on
 * delivery and Square (card) are the available payment gateways; Square is a
 * client-side (tokenized) gateway, so the nonce and buyer-verification token
 * collected by the Square web SDK are passed straight to OrderManager.
 */
class CartBox extends Component
{
    public bool $showForm = false;

    public bool $placed = false;

    public string $firstName = '';

    public string $lastName = '';

    public string $email = '';

    public string $telephone = '';

    public string $comment = '';

    public string $payment = 'cod';

    public string $squareNonce = '';

    public string $squareToken = '';

    public ?int $orderId = null;

    protected CartManager $cartManager;

    protected OrderManager $orderManager;

    public function boot(): void
    {
        $this->cartManager = resolve(CartManager::class);
        $this->orderManager = resolve(OrderManager::class);
    }

    public function mount(): void
    {
        if ($customer = Auth::customer()) {
            $this->firstName = $customer->first_name ?? '';
            $this->lastName = $customer->last_name ?? '';
            $this->email = $customer->email ?? '';
            $this->telephone = $customer->telephone ?? '';
        }
    }

    /**
     * Adds an item to the cart. Fired from the menu list's "Add to order"
     * button via a global Livewire event.
     */
    #[On('cart-box:add-item')]
    public function onAddItem(int $menuId, ?int $quantity = null): void
    {
        // New items start a new order — drop the previous confirmation.
        if ($this->placed) {
            $this->placed = false;
            $this->orderId = null;
        }

        $this->cartManager->addOrUpdateCartItem([
            'menuId' => $menuId,
            'quantity' => $quantity ?: 1,
        ]);
    }

    public function onUpdateItemQuantity(string $rowId, string $action = 'plus'): void
    {
        $this->cartManager->updateCartItemQty($rowId, $action);
    }

    public function onToggleForm(): void
    {
        $this->showForm = !$this->showForm;
    }

    #[Computed]
    public function cartTotal(): float
    {
        return $this->cartManager->getCart()->total();
    }

    /**
     * The enabled Square payment gateway, or null when it is disabled. The
     * gateway object proxies straight onto the Payment model, so the view can
     * read the sandbox app/location ids for the web SDK.
     */
    public function squareGateway(): ?object
    {
        $square = $this->orderManager->getPayment('square');

        return $square && $square->status ? $square : null;
    }

    public function squareAvailable(): bool
    {
        return (bool)$this->squareGateway();
    }

    public function onPlaceOrder(): void
    {
        $this->checkCheckoutSecurity();

        $this->validate([
            'firstName' => ['required', 'between:1,48'],
            'lastName' => ['required', 'between:1,48'],
            'email' => ['required', 'email:filter', 'max:96'],
            'telephone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/i'],
            'comment' => ['max:500'],
        ]);

        $paymentCode = $this->payment === 'square' ? 'square' : 'cod';

        // Pin the session so applyRequiredAttributes() records the right
        // order type and payment method on the order.
        Location::updateOrderType('collection');
        $this->orderManager->setCurrentPaymentCode($paymentCode);

        $paymentData = ['payment' => $paymentCode];

        if ($paymentCode === 'square') {
            $square = $this->squareGateway();
            if (!$square) {
                throw ValidationException::withMessages(['payment' => 'Card payments are unavailable right now.']);
            }

            if (!strlen($this->squareNonce)) {
                throw ValidationException::withMessages(['payment' => 'Please enter your card details to continue.']);
            }

            $paymentData['square_card_nonce'] = $this->squareNonce;
            $paymentData['square_card_token'] = $this->squareToken;
        }

        $order = $this->orderManager->loadOrder();

        $this->orderManager->saveOrder($order, [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'comment' => $this->comment,
            'payment' => $paymentCode,
        ]);

        $this->orderManager->processPayment($order, $paymentData);

        Event::dispatch('maries.checkout.placed', [$order]);

        // Detach the order from the session so a fresh cart does not
        // accidentally link to it, then empty the cart.
        $this->orderManager->clearOrder();
        $this->cartManager->getCart()->destroy();

        $this->orderId = $order->getKey();
        $this->placed = true;
        $this->showForm = false;
    }

    public function render(): View
    {
        return view('maries::livewire.cart-box', [
            'cart' => $this->cartManager->getCart(),
            'order' => $this->placed && $this->orderId ? Order::find($this->orderId) : null,
        ]);
    }

    protected function checkCheckoutSecurity(): void
    {
        try {
            $this->cartManager->validateContents();
            $this->orderManager->validateCustomer(Auth::getUser());
            $this->cartManager->validateLocation();
            $this->cartManager->validateOrderTime();

            if ($this->cartManager->cartTotalIsBelowMinimumOrder()) {
                throw new ApplicationException(sprintf(lang('igniter.cart::default.alert_min_order_total'),
                    currency_format(Location::currentOrDefault()->minimumOrderTotal())));
            }
        } catch (Throwable $ex) {
            throw ValidationException::withMessages(['checkout' => $ex->getMessage()]);
        }
    }
}
