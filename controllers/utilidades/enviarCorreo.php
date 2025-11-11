<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
$emailDestinatario = $_GET['email_destinatario'];
$nombreDestinatario = $_GET['nombre_destinatario'];
$asuntoCorreo = $_GET['asunto_correo'];
$contenidoCorreo = $_GET['contenido_correo'];

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'xitorestaurante@gmail.com';
    $mail->Password = 'zlbz potj qept zrmq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('xitorestaurante@gmail.com', 'Restaurante XITO');
    $mail->addAddress($emailDestinatario, $nombreDestinatario);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $asuntoCorreo;
    $mail->Body    = $contenidoCorreo;
    $mail->AltBody = $contenidoCorreo;

    $mail->send();
    echo 'El correo ha sido enviado';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}

?>