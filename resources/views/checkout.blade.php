<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <button id="stripe-button">Pay with Stripe</button>
    <form action="{{ route('paypal.create') }}" method="POST">
        @csrf
        <button type="submit">Pay with PayPal</button>
    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');

        document.getElementById('stripe-button').addEventListener('click', function () {
            fetch('/create-checkout-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                return stripe.redirectToCheckout({ sessionId: data.id });
            })
            .then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>

