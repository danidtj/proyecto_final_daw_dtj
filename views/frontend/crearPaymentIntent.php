<?php
session_start();

require_once dirname(__DIR__, 2) . '/config/DB.php';
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Configurar Stripe con la clave secreta cargada desde .env
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

//echo "<script>alert('Prueba: ".$_SESSION['nuevoPagoAdelantado']."');</script>";

try {
    // Verificar que el montante esté definido en la sesión
    if (!isset($_SESSION['nuevoPagoAdelantado']) || $_SESSION['nuevoPagoAdelantado'] <= 0) {
        throw new Exception('Montante inválido o no definido en la sesión.');
    }

    $amount = intval(round($_SESSION['nuevoPagoAdelantado'] * 100));
    $input = json_decode(file_get_contents('php://input'), true);
    $cardholderName = $input['name'] ?? 'Sin nombre';

    // Crear el PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'eur',
        'automatic_payment_methods' => ['enabled' => true],
        'metadata' => [
            'cardholder_name' => $cardholderName
        ],
    ]);

    $_SESSION['stripe_payment_id'] = $paymentIntent->id;

    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
