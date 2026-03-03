<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Proof Submitted</title>
</head>

<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h2 style="margin-bottom: 8px;">Customer Payment Submitted</h2>
    <p style="margin-top: 0; color: #6b7280;">Customer submitted transaction details for verification.</p>

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
            <td><strong>Method</strong></td>
            <td>{{ strtoupper($order->payment_method) }}</td>
        </tr>
        <tr>
            <td><strong>Selected Account</strong></td>
            <td>{{ $order->paymentAccount?->account_name ?? 'N/A' }}
                ({{ $order->paymentAccount?->account_number ?? 'N/A' }})</td>
        </tr>
        <tr>
            <td><strong>Transaction ID</strong></td>
            <td>{{ $order->payment_reference ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Payable</strong></td>
            <td>৳{{ number_format($order->payable_amount, 2) }}</td>
        </tr>
    </table>

    <p style="margin-top: 16px;">Please review in admin order panel and confirm payment/order status.</p>
</body>

</html>
