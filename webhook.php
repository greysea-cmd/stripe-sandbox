<?php
require_once 'config.php';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, STRIPE_WEBHOOK_SECRET
    );
} catch(\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;
        // Handle successful payment
        error_log("Payment succeeded: " . $paymentIntent->id);
        break;
        
    case 'payment_intent.payment_failed':
        $paymentIntent = $event->data->object;
        // Handle failed payment
        error_log("Payment failed: " . $paymentIntent->id);
        break;
        
    default:
        error_log('Received unknown event type: ' . $event->type);
}

http_response_code(200);
?>