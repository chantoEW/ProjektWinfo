<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

$vorname = $_SESSION['vorname'];
$nachname = $_SESSION['nachname'];
$benutzername = $_SESSION['benutzername'];

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

    $confirmationLink = "http://192.168.233.1/ProjektWinfo/Kundenportal/logic_bestaetigungPK.php?benutzername=" . urlencode($benutzername);

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
                    <p style='color: #666;'>Liebe/r <strong>" . htmlspecialchars($vorname) . "</strong> <strong>" . htmlspecialchars($nachname) . "</strong>,</p>
                    <p style='color: #666;'>Die Plausibilitätsprüfung wurde erfgolgreich abgeschlossen. Bitte bestätigen Sie Ihre E-Mail-Adresse über folgenden Link:</p>
                    <p><a href='$confirmationLink' style='color: #1a73e8;'>Registrierung bestätigen</a></p>
                    <p style='color: #666;'>Anschließend können Sie sich mit Ihrem Benutzernamen <strong>" . htmlspecialchars($benutzername) . "</strong> in unserem Kundenportal einloggen!</p>
                    <p style='color: #666;'>Mit freundlichen Grüßen,<br>Ihre Autovermietung jomace</p>
                </div>
            </body>
            </html>";

    $mail->send();

    logmessage("[Registrierungs-Mail-PK] Mail versendet an: " . $vorname . " " . $nachname . ", Benutzername: " . $benutzername);

    echo "<script>alert('Die Daten wurden erfolgreich übermittelt. Wir informieren Sie per E-mail, sobald die Plausibilätsprüfung abgeschlossen ist.'); window.location.href='login.html';</script>";
    exit();
} catch (Exception $e) {
    logmessage("[Registrierungs-Mail-PK] Message could not be sent. Mailer Error: {$mail->ErrorInfo}", "ERROR");
}
?>
