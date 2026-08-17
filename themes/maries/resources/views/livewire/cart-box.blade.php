<div class="cart-box-root" wire:loading.class="is-loading">
    <div class="cart-box h-100">
    <span class="cart-box-count visually-hidden" aria-hidden="true">{{ $cart->count() }}</span>
    <span class="cart-box-total visually-hidden" aria-hidden="true">{{ currency_format($cart->total()) }}</span>

    <div class="cart-box-body p-3">
        @if ($cart->count())
            <ul class="cart-items">
                @foreach ($cart->content()->reverse() as $cartItem)
                    <li class="cart-item" wire:key="cart-item-{{ $cartItem->rowId }}">
                        <div class="cart-item-info">
                            <span class="cart-item-name">
                                @if ($cartItem->qty > 1)
                                    <span class="cart-item-qty-badge">{{ $cartItem->qty }}×</span>
                                @endif
                                {{ $cartItem->name }}
                            </span>
                            <span class="cart-item-controls">
                                <button
                                    type="button"
                                    class="btn-qty"
                                    wire:click="onUpdateItemQuantity('{{ $cartItem->rowId }}', 'minus')"
                                    wire:loading.attr="disabled"
                                    aria-label="Decrease quantity"
                                >−</button>
                                <span class="cart-item-qty">{{ $cartItem->qty }}</span>
                                <button
                                    type="button"
                                    class="btn-qty"
                                    wire:click="onUpdateItemQuantity('{{ $cartItem->rowId }}', 'plus')"
                                    wire:loading.attr="disabled"
                                    aria-label="Increase quantity"
                                >+</button>
                            </span>
                        </div>
                        <span class="cart-item-price">{{ currency_format($cartItem->subtotal) }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="cart-totals">
                <div class="cart-total-row">
                    <span>Subtotal</span>
                    <span>{{ currency_format($cart->subtotal()) }}</span>
                </div>
                <div class="cart-total-row cart-total-grand">
                    <span>Total</span>
                    <span>{{ currency_format($cart->total()) }}</span>
                </div>
            </div>
        @else
            <p class="cart-empty">Your order is empty. Pick something from the menu.</p>
        @endif

        @if ($placed && $order)
            <div class="checkout-box" wire:key="checkout-success-{{ $order->order_id }}">
                <div class="checkout-success">
                    <i class="bi bi-check-circle-fill checkout-success-icon"></i>
                    <h5 class="checkout-success-title">Order placed. Thank you!</h5>
                    <p class="checkout-success-ref">Order #{{ $order->order_id }} · Reference {{ $order->hash }}</p>
                    <p class="checkout-success-total">{{ currency_format($order->order_total) }} · {{ $order->payment_method ? $order->payment_method->name : 'Paid' }}</p>
                    <p class="checkout-success-note">
                        Your order has been received and will be prepared for pickup at
                        {{ $order->location ? $order->location->location_name : 'our restaurant' }}.
                        Keep the reference handy.
                    </p>
                </div>
            </div>
        @elseif ($cart->count() && !$showForm)
            <div class="checkout-box">
                <button
                    type="button"
                    class="btn btn-checkout"
                    wire:click="onToggleForm"
                    wire:loading.attr="disabled"
                >
                    Place order · {{ currency_format($this->cartTotal) }}
                </button>
            </div>
        @elseif ($cart->count() && $showForm)
            <div class="checkout-box">
                <form class="checkout-form" wire:submit="onPlaceOrder">
                    @error('checkout')
                        <div class="checkout-alert">{{ $message }}</div>
                    @enderror

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label" for="checkout-first-name">First name</label>
                            <input
                                id="checkout-first-name"
                                type="text"
                                class="form-control @error('firstName') is-invalid @enderror"
                                wire:model="firstName"
                            >
                            @error('firstName') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="checkout-last-name">Last name</label>
                            <input
                                id="checkout-last-name"
                                type="text"
                                class="form-control @error('lastName') is-invalid @enderror"
                                wire:model="lastName"
                            >
                            @error('lastName') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="checkout-email">Email</label>
                            <input
                                id="checkout-email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                wire:model="email"
                            >
                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="checkout-phone">Phone</label>
                            <input
                                id="checkout-phone"
                                type="tel"
                                class="form-control @error('telephone') is-invalid @enderror"
                                wire:model="telephone"
                            >
                            @error('telephone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="checkout-comment">Order notes (optional)</label>
                            <textarea
                                id="checkout-comment"
                                rows="2"
                                class="form-control @error('comment') is-invalid @enderror"
                                wire:model="comment"
                            ></textarea>
                            @error('comment') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="checkout-meta">
                        <span class="checkout-meta-item"><i class="bi bi-shop"></i> Pick-up</span>
                    </div>

                    <div class="checkout-payment">
                        <div class="payment-option">
                            <label class="payment-option-label">
                                <input
                                    type="radio"
                                    name="payment"
                                    value="cod"
                                    wire:model.live="payment"
                                    class="form-check-input"
                                >
                                <span class="payment-option-body">
                                    <span class="payment-option-title">Cash on pickup</span>
                                    <span class="payment-option-desc">Pay when you collect your order.</span>
                                </span>
                            </label>
                        </div>

                        @if ($this->squareAvailable())
                            <div class="payment-option">
                                <label class="payment-option-label">
                                    <input
                                        type="radio"
                                        name="payment"
                                        value="square"
                                        wire:model.live="payment"
                                        class="form-check-input"
                                    >
                                    <span class="payment-option-body">
                                        <span class="payment-option-title">Pay by card</span>
                                        <span class="payment-option-desc">Visa, Mastercard, Amex — via Square.</span>
                                    </span>
                                </label>
                            </div>

                            @php($square = $this->squareGateway())
                            <div
                                class="square-block"
                                id="square-block"
                                data-app-id="{{ $square->getAppId() }}"
                                data-location-id="{{ $square->getLocationId() }}"
                                data-currency-code="{{ $square->isTestMode() ? 'USD' : currency()->getUserCurrency() }}"
                                data-order-total="{{ $this->cartTotal }}"
                                @if ($this->payment !== 'square') hidden @endif
                            >
                                <div id="square-card-element" class="square-ccbox"></div>
                                <div id="square-card-errors" class="square-errors"></div>
                                <input type="hidden" name="square_card_nonce" wire:model="squareNonce">
                                <input type="hidden" name="square_card_token" wire:model="squareToken">
                                @error('payment') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="checkout-actions">
                        <button type="button" class="btn btn-checkout-ghost" wire:click="onToggleForm">Back</button>
                        <button type="submit" class="btn btn-checkout" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="onPlaceOrder">Confirm order · {{ currency_format($this->cartTotal) }}</span>
                            <span wire:loading wire:target="onPlaceOrder">Placing order…</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
</div>
