@extends('layouts.main')

@section('content')
<style>
    .payment-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f7fb;
    }

    .payment-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .payment-title {
        font-weight: 600;
        text-align: center;
        margin-bottom: 20px;
    }

    #card-element {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fafafa;
    }

    #payBtn {
        width: 100%;
        border-radius: 8px;
        font-weight: 500;
        padding: 12px;
        transition: 0.3s;
    }

    #payBtn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid #fff;
        border-top: 3px solid transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .secure-text {
        font-size: 13px;
        color: #888;
        text-align: center;
        margin-top: 15px;
    }
</style>

<div class="payment-wrapper">
    <div class="payment-card">
        <h4 class="payment-title"> Pay with card</h4>

        <p class="text-center text-muted mb-3">
            Pay <strong>Rs {{ number_format($order->total) }}</strong>
        </p>

        <div id="card-element"></div>

        <button id="payBtn" class="btn btn-primary mt-4 d-flex align-items-center justify-content-center">
            <span id="btnText">Pay Now</span>
            <div class="spinner" id="spinner"></div>
        </button>

        <div id="card-errors" class="text-danger mt-3 text-center"></div>

        <div class="secure-text">
             Your payment is secured by Stripe
        </div>
    </div>
</div>


<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();

    const card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#a0aec0'
                }
            }
        }
    });

    card.mount('#card-element');

    const payBtn = document.getElementById('payBtn');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');

    payBtn.addEventListener('click', async () => {
        payBtn.disabled = true;
        spinner.style.display = 'inline-block';
        btnText.innerText = "Processing...";

        const {error, paymentIntent} = await stripe.confirmCardPayment("{{ $clientSecret }}", {
            payment_method: {
                card: card,
                billing_details: {
                    name: "{{ $order->first_name }} {{ $order->last_name }}",
                    email: "{{ $order->email }}",
                }
            }
        });

        if(error){
            document.getElementById('card-errors').textContent = error.message;

            payBtn.disabled = false;
            spinner.style.display = 'none';
            btnText.innerText = "Pay Now";
        } 
        else if(paymentIntent.status === 'succeeded') {

            btnText.innerText = "Success ✔";

            fetch("{{ route('stripe.success', $order->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({payment_intent_id: paymentIntent.id})
            }).then(() => {
                setTimeout(() => {
                    window.location.href = "{{ route('order.success', $order->id) }}";
                }, 1000);
            });
        }
    });
</script>
@endsection