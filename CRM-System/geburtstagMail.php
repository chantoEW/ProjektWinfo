<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

function logMessage($message) {
    $logFile = 'logfile.json';
    $formattedMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "Chantal";
$password = "";
$dbname = "portal";

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);
// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    logMessage("Verbindung zur Datenbank kann nicht hergestellt werden");
} else {
    echo("Verbindung zur DB wurde hergestellt");
    logMessage("Verbindung zur Datenbank wurde hergestellt und überprüft");
}

try {
$sql = "SELECT Rabatt FROM Rabattart WHERE Art = 'Geburtstag'";
$result_Rabatt = mysqli_query($conn, $sql);

$heutigesDatum = date('Y-m-d');
$ablaufDateum = date('Y-m-d', strtotime($heutigesDatum . ' + 30 days'));

$sql = "SELECT a.Mail, a.PKundenID, b.Vorname, b.Nachname FROM kontaktdaten AS a INNER JOIN privatkunde AS b ON a.PKundenID = b.PKundenID WHERE (a.PKundenID IS NOT NULL) AND (b.GebDatum = '$heutigesDatum')";
$result_PKunden = mysqli_query($conn, $sql);
$rows = $result_PKunden->fetch_assoc();
foreach ($rows as $row) {
    $RabattCode = Str::random(20);
    "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart) VALUES '$RabattCode', '$heutigesDatum', '$ablaufDateum', false, $result_Rabatt, Geburstag";
    logMessage("GeburtstagsMail versendet an: ". $row['b.Vorname'] . " ". $row['b.Nachname'] . "mit der Email-Adresse ". $row['a.Mail']);
}


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

    // Empfänger, Betreff und Nachricht einstellen
    $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
    $mail->addAddress('fschlaghecken@gmx.de', 'Fabian Schlaghecken');
    $mail->Subject = 'Herzlichen Glückwunsch zum Geburtstag!';
    $mail->isHTML(true);
    $mail->Body = "
<!DOCTYPE html>
<html lang='de'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Geburtstagsgruß und Rabattcode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #777;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Herzlichen Glückwunsch zum Geburtstag!</h1>
        </div>
        <div class='content'>
            <p>Sehr geehrte(r) Frau/Herr Schlaghecken,</p>
            <p>zu Ihrem  Geburtstag möchten wir Ihnen unsere herzlichsten Glückwünsche übermitteln. Wir hoffen, dass Sie einen wunderbaren Tag im Kreise Ihrer Liebsten verbringen und sich gebührend feiern lassen.</p>
            <p>Um diesen besonderen Anlass noch festlicher zu gestalten, möchten wir Ihnen als treuer Kunde der <strong>Autovermietung jomaface</strong> ein kleines Geschenk machen. Zu Ihrem Geburtstag erhalten Sie von uns einen exklusiven Rabatt von <strong>htmlspecialchars($result_Rabatt)</strong> auf Ihre nächste Fahrzeuganmietung.</p>
            <p>Verwenden Sie einfach den folgenden Rabattcode bei Ihrer nächsten Buchung:</p>
            <p><strong>htmlspecialchars($RabattCode)</strong></p>
            <p>Der Rabattcode ist bis zum <strong>htmlspecialchars($ablaufDateum)</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Besuchen Sie unsere Kundenportal <a href='[www.autovermietung-jomaface.de]'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Reise zu planen.</p>
            <p>Wir möchten uns bei Ihnen für Ihre Treue und Ihr Vertrauen bedanken und freuen uns darauf, Sie bald wieder bei uns begrüßen zu dürfen.</p>
            <p>Genießen Sie Ihren Ehrentag und lassen Sie sich reich beschenken!</p>
            <p>Mit freundlichen Grüßen,<br>
            Ihre Autovermietung jomaface</p>
        </div>
        <div class='footer'>
            <p>Diese E-Mail wurde automatisch generiert. Bitte antworten Sie nicht auf diese Nachricht.</p>
        </div>
    </div>
</body>
</html>
";

    // E-Mail senden
    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>