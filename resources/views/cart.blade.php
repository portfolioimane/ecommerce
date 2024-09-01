<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <ul>
        @foreach ($cart as $id => $details)
            <li>
                <h2>{{ $details['name'] }}</h2>
                <p>Quantity: {{ $details['quantity'] }}</p>
                <p>Price: ${{ $details['price'] }}</p>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('checkout') }}">Checkout</a>
</body>
</html>
