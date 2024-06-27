<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

$vorname = $_SESSION['vorname'];
$nachname = $_SESSION['nachname'];
$benutzername = $_SESSION['benutzername'];
$firmenname = $_SESSION['firmenname'];

try {
    // Servereinstellungen konfigurieren
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'autovermietung.jomaface@outlook.de';
    $mail->Password = '!krusewhs';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Zeichencodierung setzen
    $mail->CharSet = 'UTF-8';

    $confirmationLink = "http://localhost/projektWINFO/Kundenportal/logic_bestaetigungFK.php?benutzername=$benutzername";

    // Empfänger, Betreff und Nachricht einstellen
    $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
    $mail->addAddress('fschlaghecken@gmx.de', 'Fabian Schlaghecken');

    // Inhalt
    $mail->isHTML(true);                          // Set email format to HTML
    $mail->Subject = 'Registrierung erfolgreich!';
    $mail->Body    = "
            <!DOCTYPE html>
            <html lang='de'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Registrierung erfolgreich</title>
                <style>
                    CSS-Stile für die E-Mail hier einfügen 
                </style>
            </head>
            <body>
                <div style='background-color: #f4f4f4; padding: 20px;'>
                    <p style='color: #666;'>Liebe/r " . htmlspecialchars($vorname) . " " . htmlspecialchars($nachname) . " (" . htmlspecialchars($firmenname) .",</p>
                    <p style='color: #666;'>Die Plausibilitätsprüfung wurde erfgolgreich abgeschlossen. Bitte bestätigen Sie Ihre E-Mail-Adresse über folgenden Link.</p>
                    <p style='color: #666;'>Anschließend können Sie sich mit Ihrem Benutzernamen " . htmlspecialchars($benutzername) . " in unserem Kundenportal einloggen!</p>
                    <p style='color: #666;'>Mit freundlichen Grüßen,<br>Ihre Autovermietung jomace</p>
                </div>
            </body>
            </html>";

    $mail->send();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
