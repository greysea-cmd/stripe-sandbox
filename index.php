<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment Demo</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #5469d4; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background: #4252a8; }
        #card-element { border: 1px solid #ddd; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { color: #e74c3c; margin-top: 10px; }
        .success { color: #27ae60; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Complete Your Payment</h2>
        <p>Amount: $19.99</p>
        
        <form id="payment-form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" placeholder="John Doe" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="john@example.com" required>
            </div>
            
            <div class="form-group">
                <label>Card Details</label>
                <div id="card-element"></div>
            </div>
            
            <button type="submit">Pay $19.99</button>
            
            <div id="error-message" class="error"></div>
            <div id="success-message" class="success"></div>
        </form>
    </div>

    <script>
        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
        const elements = stripe.elements();
        
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
            },
        });
        
        cardElement.mount('#card-element');
        
        const form = document.getElementById('payment-form');
        const errorDiv = document.getElementById('error-message');
        const successDiv = document.getElementById('success-message');
        
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            errorDiv.textContent = '';
            successDiv.textContent = '';
            
            const { paymentIntent, error } = await stripe.confirmCardPayment(
                '<?php 
                    // Create payment intent and get client secret
                    require_once 'config.php';
                    $paymentIntent = $stripe->paymentIntents->create([
                        'amount' => AMOUNT,
                        'currency' => CURRENCY,
                        'automatic_payment_methods' => ['enabled' => true],
                        'metadata' => [
                            'customer_name' => 'From Form',
                            'customer_email' => 'From Form'
                        ]
                    ]);
                    echo $paymentIntent->client_secret;
                ?>',
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: document.getElementById('name').value,
                            email: document.getElementById('email').value,
                        },
                    }
                }
            );
            
            if (error) {
                errorDiv.textContent = error.message;
            } else if (paymentIntent.status === 'succeeded') {
                successDiv.textContent = 'Payment succeeded!';
                form.reset();
                cardElement.clear();
                
                // Redirect to success page after 2 seconds
                setTimeout(() => {
                    window.location.href = 'success.php?payment_intent=' + paymentIntent.id;
                }, 2000);
            }
        });
    </script>
</body>
</html>