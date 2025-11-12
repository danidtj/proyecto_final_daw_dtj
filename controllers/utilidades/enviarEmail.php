<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

function enviarEmail($emailDestinatario, $nombreDestinatario, $asuntoCorreo, $contenidoCorreo)
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'xitorestaurante@gmail.com';
        $mail->Password = 'yqrc ubhy odfn wftr';
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
        return true;
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
        return false;
    }
}
