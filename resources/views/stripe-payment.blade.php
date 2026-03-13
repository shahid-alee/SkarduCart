@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Pay with Card</h2>

    <div id="card-element" class="form-control"></div>
    <button id="payBtn" class="btn btn-primary mt-3">Pay Rs {{ $order->total }}</button>

    <div id="card-errors" role="alert" class="text-danger mt-2"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}'); 
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const payBtn = document.getElementById('payBtn');

    payBtn.addEventListener('click', async () => {
        payBtn.disabled = true;
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
        } else if(paymentIntent.status === 'succeeded'){
            // Payment successful, update order
            fetch("{{ route('stripe.success', $order->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({payment_intent_id: paymentIntent.id})
            }).then(() => {
                window.location.href = "{{ route('order.success', $order->id) }}";
            });
        }
    });
</script>
@endsection