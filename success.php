<?php
require_once 'config.php';

$paymentIntentId = $_GET['payment_intent'] ?? '';

if ($paymentIntentId) {
    try {
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);
        $amount = $paymentIntent->amount / 100; // Convert cents to dollars
        $currency = strtoupper($paymentIntent->currency);
    } catch (Exception $e) {
        // Handle error
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .success { color: #27ae60; font-size: 24px; }
    </style>
</head>
<body>
    <div class="success">✅ Payment Successful!</div>
    <?php if ($paymentIntentId): ?>
        <p>Payment ID: <?php echo htmlspecialchars($paymentIntentId); ?></p>
        <p>Amount: <?php echo htmlspecialchars($amount); ?> <?php echo htmlspecialchars($currency); ?></p>
    <?php endif; ?>
    <p><a href="index.php">Make another payment</a></p>
</body>
</html>