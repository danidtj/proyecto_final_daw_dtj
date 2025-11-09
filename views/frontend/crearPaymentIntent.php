<?php

@session_start();
require dirname(__DIR__, 2) . '/vendor/autoload.php';

// Clave secreta de Stripe (modo prueba)
\Stripe\Stripe::setApiKey('sk_test_51SMCTFC5kWSf4beJX0HWNTKELeKQv7730Nm6T9X20DwX6iCuCWy9Fd3ilc7xnTIuLkDiUnbSkCimfz2HMRISNInA008L6bVTGq');

header('Content-Type: application/json');

// Puedes calcular el monto desde tu carrito, ejemplo: 10€ -> 1000 céntimos
$amount = $_SESSION['nuevoPagoAdelantado'] * 100; 

$paymentIntent = \Stripe\PaymentIntent::create([
    'amount' => $amount,
    'currency' => 'eur',
    'payment_method_types' => ['card'],
]);

echo json_encode(['clientSecret' => $paymentIntent->client_secret]);


/*@session_start();

require dirname(__DIR__, 2) . '/vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51SMCTFC5kWSf4beJX0HWNTKELeKQv7730Nm6T9X20DwX6iCuCWy9Fd3ilc7xnTIuLkDiUnbSkCimfz2HMRISNInA008L6bVTGq');

header('Content-Type: application/json');


$paymentIntent = \Stripe\PaymentIntent::create([
    'amount' => 15000,
    'currency' => 'eur',
    'payment_method_types' => ['card'],
]);

echo json_encode(['client_secret' => $paymentIntent->client_secret]);*/

?>
