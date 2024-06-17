<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

$response = array("success" => false, "message" => "");

try {
    // Servereinstellungen konfigurieren
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'autovermietung.jomaface@outlook.de';
    $mail->Password = 'projektwinfo!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Empfänger, Betreff und Nachricht einstellen
    $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
    $mail->addAddress('chantal.ewig@hotmail.com', 'Chantal Ewig');
    $mail->Subject = 'Test Email';
    $mail->isHTML(true);
    $mail->Body = 'This is a test email.';

    // E-Mail senden
    $mail->send();
    $response["success"] = true;
    $response["message"] = "Email sent successfully.";
} catch (Exception $e) {
    $response["message"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

echo json_encode($response);
?>
