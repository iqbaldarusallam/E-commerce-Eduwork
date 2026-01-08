@extends('layouts.userNavbar')

@section('content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Payment</h3>

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    @php
                        $payment = $transaction->payments->last();
                        $isMidtrans = $payment && ($payment->payment_gateway === 'midtrans');
                    @endphp

                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">Order #{{ $transaction->id }}</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-gray-700 text-sm">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format(max(0, $transaction->total_amount - $transaction->shipping_fee), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700 text-sm mb-2">
                        <span>Shipping:</span>
                        <span>Rp {{ number_format($transaction->shipping_fee, 0, ',', '.') }}</span>
                    </div>

                    <!-- Replace payment meta with item summary -->
                    <h4 class="text-sm font-medium text-gray-900 mt-2 mb-3">{{ __('Items Purchased') }}</h4>
                    <div class="space-y-3">
                        @foreach($transaction->items as $item)
                            <div class="flex items-center gap-4">
                                @if($item->product && $item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->url) }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                    <p class="text-sm text-gray-600">{{ __('Quantity') }}: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($isMidtrans)
                    <div id="payment-container" class="mb-6">
                        <p class="text-center text-gray-600 mb-4">
                            @if(!empty($payment->snap_token))
                                Click the button below to complete payment:
                            @else
                                Click the button below to initialize payment and open a secure popup.
                            @endif
                        </p>

                        {{-- Always render the button so JavaScript can initialize/open payment --}}
                        <button id="pay-button" class="w-full px-6 py-3 bg-green-600 text-white font-bold rounded hover:bg-green-700">
                            Pay Now
                        </button>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-700">
                        <p>Payment gateway not initialized properly. Please contact support to complete your payment or retry.</p>
                        <div class="mt-3">
                            <a href="{{ route('user.checkout.payment.show', $transaction) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Retry</a>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>

    @if($isMidtrans && $payment && $payment->snap_token)
        @php
            $midtransScript = env('MIDTRANS_IS_PRODUCTION', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
        @endphp
        @if(env('MIDTRANS_CLIENT_KEY'))
            <script src="{{ $midtransScript }}" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
            <script>
                // Minimal diagnostics to help debug missing popup
                try {
                    console.log('Midtrans: script tag present?', !!document.querySelector('script[src*="snap.js"]'));
                    console.log('Midtrans: data-client-key present?', !!document.querySelector('script[data-client-key]'));

                    const snapScript = document.querySelector('script[src*="snap.js"]');
                    const debugFail = (msg) => {
                        console.error(msg);
                        // visible message for users
                        const container = document.getElementById('payment-container');
                        if (container && !document.getElementById('midtrans-error')) {
                            const el = document.createElement('div');
                            el.id = 'midtrans-error';
                            el.className = 'mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded';
                            el.innerText = 'Payment script failed to load. Please check your internet connection or browser extensions (adblock). Click Pay Now to retry.';
                            container.prepend(el);
                        }
                        // unhide pay button so users can retry manually
                        const btn = document.getElementById('pay-button');
                        if (btn) btn.classList.remove('hidden');
                    };

                    if (snapScript) {
                        snapScript.addEventListener('error', (e) => debugFail('Midtrans snap script failed to load'));
                        snapScript.addEventListener('load', () => console.log('Midtrans snap script loaded'));
                    } else {
                        console.warn('Midtrans script tag not found in DOM');
                    }

                    // Listen for CSP violations (blocked eval used by Midtrans popup)
                    window.addEventListener('securitypolicyviolation', (e) => {
                        try {
                            console.warn('CSP violation detected', e);
                            if (e && e.blockedURI && e.blockedURI.includes('midtrans')) {
                                debugFail('Content Security Policy prevented Midtrans popup (eval blocked). Consider allowing `unsafe-eval` on payment page.');
                            }
                        } catch (err) { /* noop */ }
                    });

                    // If snap doesn't become available within 6s, show visible failure
                    setTimeout(() => {
                        if (typeof snap === 'undefined') {
                            debugFail('Midtrans `snap` object not available after timeout');
                        } else {
                            console.log('Midtrans `snap` ready');
                        }
                    }, 6000);
                } catch (err) {
                    console.error('Midtrans diagnostic error', err);
                }
            </script>
        @else
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
                Midtrans client key not configured. Please set <strong>MIDTRANS_CLIENT_KEY</strong> in your environment and retry.
            </div>
        @endif
        <script>
            const NF = window.Notiflix ?? null;
            const payButton = document.getElementById('pay-button');
            let snapToken = @json($payment->snap_token ?? null);

            const showLoading = (message = 'Opening payment popup...') => {
                if (NF && NF.Loading) {
                    NF.Loading.circle(message);
                }
            };
            const hideLoading = () => {
                if (NF && NF.Loading) {
                    NF.Loading.remove();
                }
            };

            const notifyServer = async (orderId) => {
                try {
                    await fetch('{{ route('user.checkout.notify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order_id: orderId })
                    });
                } catch (err) {
                    console.error('Notify payment error', err);
                }
            };

            function openSnapToken(token) {
                showLoading();
                try {
                    snap.pay(token, {
                        onSuccess: async function(result) {
                            await notifyServer(result.order_id);
                            hideLoading();
                            if (NF && NF.Notify) NF.Notify.success('Payment successful.');
                            window.location.href = '{{ route('user.home') }}?payment=success&order=' + result.order_id;
                        },
                        onPending: function(result) {
                            hideLoading();
                            if (NF && NF.Notify) NF.Notify.info('Payment pending. We\'ll update your order once confirmed.');
                        },
                        onError: function(result) {
                            hideLoading();
                            if (NF && NF.Notify) NF.Notify.failure('Payment failed. Please try again.');
                            if (payButton) payButton.classList.remove('hidden');
                        },
                        onClose: function() {
                            hideLoading();
                            if (payButton) payButton.classList.remove('hidden');
                        }
                    });
                } catch (err) {
                    hideLoading();
                    if (payButton) payButton.classList.remove('hidden');
                    console.error('Failed to open Midtrans popup', err);
                    if (NF && NF.Notify) NF.Notify.failure('Unable to open payment popup. Please click the button to retry.');
                }
            }

            async function ensureSnapToken() {
                if (snapToken) return snapToken;
                showLoading('Initializing payment...');
                try {
                    const res = await fetch('{{ route('user.checkout.payment.switch', $transaction) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    if (!res.ok) throw new Error(json.error || 'Unable to initialize payment');
                    snapToken = json.snap_token;
                    hideLoading();
                    return snapToken;
                } catch (err) {
                    hideLoading();
                    if (payButton) payButton.classList.remove('hidden');
                    alert(err.message || 'Unable to initialize payment. Please try again later.');
                    throw err;
                }
            }

            if (payButton) {
                payButton.addEventListener('click', async function() {
                    if (payButton) payButton.classList.add('hidden');
                    try {
                        const token = await ensureSnapToken();

                        // Wait for snap object to be available (script may still be loading)
                        const waitForSnap = (timeout = 5000) => new Promise((resolve) => {
                            const interval = 150; let elapsed = 0;
                            const timer = setInterval(() => {
                                if (typeof snap !== 'undefined') { clearInterval(timer); resolve(true); }
                                elapsed += interval;
                                if (elapsed > timeout) { clearInterval(timer); resolve(false); }
                            }, interval);
                        });

                        const ready = await waitForSnap(5000);
                        if (!ready) {
                            hideLoading();
                            alert('Payment script failed to load. Please try again or disable adblocker.');
                            if (payButton) payButton.classList.remove('hidden');
                            return;
                        }

                        openSnapToken(token);
                    } catch (err) {

                    }
                });
            }
        </script>
    @endif
@endsection
