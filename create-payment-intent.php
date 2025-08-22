<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $input['amount'] ?? AMOUNT,
        'currency' => $input['currency'] ?? CURRENCY,
        'automatic_payment_methods' => ['enabled' => true],
        'metadata' => [
            'customer_name' => $input['name'] ?? '',
            'customer_email' => $input['email'] ?? ''
        ]
    ]);

    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
        'paymentIntentId' => $paymentIntent->id
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>