<?php
require_once 'vendor/autoload.php';

// Stripe API keys - Get from Stripe Dashboard
define('STRIPE_SECRET_KEY', 'sk_test_your_secret_key_here');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_your_publishable_key_here');
define('STRIPE_WEBHOOK_SECRET', 'whsec_your_webhook_secret_here');

// Initialize Stripe client
$stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

// Set currency and other defaults
define('CURRENCY', 'usd');
define('AMOUNT', 1999); // $19.99 in cents
?>