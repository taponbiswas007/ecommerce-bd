<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Order Request</title>
</head>

<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h2 style="margin-bottom: 8px;">New Order Request Received</h2>
    <p style="margin-top: 0; color: #6b7280;">A customer submitted a new order request and negotiation is now open.</p>

    <table cellpadding="6" cellspacing="0" border="0" style="border-collapse: collapse; width: 100%; max-width: 640px;">
        <tr>
            <td><strong>Order</strong></td>
            <td>#{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td><strong>Customer</strong></td>
            <td>{{ $order->user?->name ?? 'Guest' }}</td>
        </tr>
        <tr>
            <td><strong>Phone</strong></td>
            <td>{{ $order->shipping_phone }}</td>
        </tr>
        <tr>
            <td><strong>Payment Method</strong></td>
            <td>{{ strtoupper($order->payment_method) }}</td>
        </tr>
        <tr>
            <td><strong>Requested Total</strong></td>
            <td>৳{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <p style="margin-top: 16px;">Open admin order panel to update transport/carrying/transfer costs and finalize quote.
    </p>
</body>

</html>
