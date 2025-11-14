<?php

use ModelsFrontend\Orden;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/DB.php';
require_once dirname(__DIR__, 2) . '/models/frontend/Orden.php';

$ordenModel = new Orden();

$idReserva = $_POST['id_reserva'] ?? null;

if (!$idReserva) {
    echo json_encode(['error' => 'ID de reserva no proporcionado']);
    exit;
}

try {
    $resultado = $ordenModel->reembolsarOrden($idReserva);

    echo json_encode([
        'success' => true,
        'refund_id' => $resultado['refund_id']
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
    exit;
}
