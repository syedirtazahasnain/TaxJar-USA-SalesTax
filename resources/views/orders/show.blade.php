<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body>
    <h1>Order Details</h1>
    <p>Order Number: {{ $order->order_number }}</p>
    <p>Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
    <p>Tax: ${{ number_format($order->tax, 2) }}</p>
    <p>Total: ${{ number_format($order->total, 2) }}</p>
</body>
</html>
