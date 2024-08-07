<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

function logMessage($message, $type = 'INFO') {
    $logFile = 'logfile.txt';
    $formattedMessage = date('Y-m-d H:i:s') . " - [$type] - " . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

function generateRandomCode($length = 20) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "Chantal";
$password = "";
$dbname = "portal";

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Verbindung überprüfen
if ($conn->connect_error) {
    logMessage("[Geburtstags-Mails] Verbindung zur Datenbank kann nicht hergestellt werden"  . $conn->connect_error, "ERROR");
} else {
    logMessage("[Geburtstags-Mails] Verbindung zur Datenbank wurde hergestellt und überprüft");
}

try {
$sql = "SELECT Rabatt FROM Rabattart WHERE Art = 'Geburtstag'";
$result_Rabatt = mysqli_query($conn, $sql);
$rows_Rabatt = $result_Rabatt->fetch_assoc();
$RabattProzentsatz = $rows_Rabatt['Rabatt']*100;

$heutigesDatum_komplett = date('Y-m-d');
$ablaufDatum = date('Y-m-d', strtotime($heutigesDatum_komplett . ' + 30 days'));
$heutigesDatum_ohneJahr = date('m-d');

$sql = "SELECT DISTINCT a.Mail, b.KundenID, b.Vorname, b.Name FROM kontaktdaten AS a INNER JOIN privatkunde AS b ON a.PKundenID = b.PKundenID WHERE (a.PKundenID IS NOT NULL) AND (DATE_FORMAT(b.GebDatum, '%m-%d') = '$heutigesDatum_ohneJahr')";
$result_PKunden = mysqli_query($conn, $sql);
$RabattCode = generateRandomCode();
if ($result_PKunden) {
    if (mysqli_num_rows($result_PKunden) > 0) {
        echo 'Privatkunden:';
        echo '<table border="1">';
        echo '<tr><th>Vorname</th><th>Nachname</th><th>KundenID</th><th>E-Mail</th><th>RabattCode</th><th>Prozentsatz</th></tr>';
        while ($row = $result_PKunden->fetch_assoc()) {

            $KundenID = $row['KundenID'];
            $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$RabattCode', '$heutigesDatum_komplett', '$ablaufDatum', false, $RabattProzentsatz, 'Geburstag', $KundenID)";
            if ($conn->query($sql) === TRUE) {
                logMessage("[Geburtstags-Mails] Mail versendet an: " . $row['Vorname'] . " " . $row['Name'] . " (KundenID: " . $KundenID . ") mit der Email-Adresse " . $row['Mail'] . ", Rabattcode: " . $RabattCode . ", Prozentsatz: " . $RabattProzentsatz);
                echo '<tr>';
                echo '<td>' . $row['Vorname'] . '</td>';
                echo '<td>' . $row['Name'] . '</td>';
                echo '<td>' . $KundenID . '</td>';
                echo '<td>' . $row['Mail'] . '</td>';
                echo '<td>' . $RabattCode . '</td>';
                echo '<td>' . $RabattProzentsatz . '%</td>';
                echo '</tr>';
            } else {
                logMessage("[Geburtstags-Mails] Mail konnte nicht an: " . $row['Vorname'] . " " . $row['Name'] . " (KundenID: " . $KundenID . ") mit der Email-Adresse " . $row['Mail'] . ", Rabattcode: " . $RabattCode . ", Prozentsatz: " . $RabattProzentsatz . "% versendet werden!", "ERROR");
            }
            $RabattCode = generateRandomCode();
        }
        echo '</table><br><br>';

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
                </div>
                <div class='content'>
                    <p>Sehr geehrte(r) Frau/Herr Schlaghecken,</p>
                    <p>zu Ihrem  Geburtstag möchten wir Ihnen unsere herzlichsten Glückwünsche übermitteln. Wir hoffen, dass Sie einen wunderbaren Tag im Kreise Ihrer Liebsten verbringen und sich gebührend feiern lassen.</p>
                    <p>Um diesen besonderen Anlass noch festlicher zu gestalten, möchten wir Ihnen als treuer Kunde der <strong>Autovermietung jomaface</strong> ein kleines Geschenk machen. Zu Ihrem Geburtstag erhalten Sie von uns einen exklusiven Rabatt von <strong>" . htmlspecialchars($RabattProzentsatz) . "%</strong> auf Ihre nächste Fahrzeuganmietung.</p>
                    <p>Verwenden Sie einfach den folgenden Rabattcode bei Ihrer nächsten Buchung:</p>
                    <p><strong>" . htmlspecialchars($RabattCode) . "</strong></p>
                    <p>Der Rabattcode ist bis zum <strong>" . htmlspecialchars($ablaufDatum) . "</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Besuchen Sie unsere Kundenportal <a href='[www.autovermietung-jomaface.de]'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Reise zu planen.</p>
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
    }
    else{
        echo 'Heute hat kein Privatkunde Geburtstag!<br><br>';
        logmessage("[Geburstags-Mails] Kein Privatkunde hat heute Geburtstag!");
    }
}

$sql = "SELECT DISTINCT a.Mail, b.KundenID, b.Vorname, b.Name as Nachname, c.Firmenname as Firmenname FROM firma as c, kontaktdaten AS a INNER JOIN firmenkunde AS b ON a.FKundenID = b.FKundenID WHERE (a.FKundenID IS NOT NULL) AND (DATE_FORMAT(b.GebDatum, '%m-%d') = '$heutigesDatum_ohneJahr') AND (b.FirmenID = c.FirmenID)";
$result_FKunden = mysqli_query($conn, $sql);
if ($result_FKunden) {
    if (mysqli_num_rows($result_FKunden) > 0) {
        echo 'Firmenkunden:';
        echo '<table border="1">';
        echo '<tr><th>Vorname</th><th>Nachname</th><th>Firma</th><th>KundenID</th><th>E-Mail</th><th>RabattCode</th><th>Prozentsatz</th></tr>';
        while ($row = $result_FKunden->fetch_assoc()) {
            $KundenID = $row['KundenID'];

            $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$RabattCode', '$heutigesDatum_komplett', '$ablaufDatum', false, $RabattProzentsatz, 'Geburstag', $KundenID)";
            if ($conn->query($sql) === TRUE) {
                logMessage("[Geburstags-Mails] Mail versendet an: " . $row['Vorname'] . " " . $row['Nachname'] . " der Firma " . $row['Firmenname'] . " mit der Email-Adresse " . $row['Mail'] . ", Rabattcode: " . $RabattCode . ", Prozentsatz: " . $RabattProzentsatz);
                echo '<tr>';
                echo '<td>' . $row['Vorname'] . '</td>';
                echo '<td>' . $row['Nachname'] . '</td>';
                echo '<td>' . $row['Firmenname'] . '</td>';
                echo '<td>' . $KundenID . '</td>';
                echo '<td>' . $row['Mail'] . '</td>';
                echo '<td>' . $RabattCode . '</td>';
                echo '<td>' . $RabattProzentsatz . '%</td>';
                echo '</tr>';
            } else {
                logMessage("[Geburtstags-Mails] Mail konnte nicht an: " . $row['Vorname'] . " " . $row['Name'] . " der Firma " . $row['Firmenname'] . " (KundenID: " . $KundenID . ") mit der Email-Adresse " . $row['Mail'] . ", Rabattcode: " . $RabattCode . ", Prozentsatz: " . $RabattProzentsatz . "% versendet werden!");
            }
            $RabattCode = generateRandomCode();
        }

        echo '</table><br><br>';

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
                    </div>
                    <div class='content'>
                        <p>Sehr geehrte(r) Frau/Herr Schlaghecken der Firma netgo Gmbh,</p>
                        <p>zu Ihrem  Geburtstag möchten wir Ihnen unsere herzlichsten Glückwünsche übermitteln. Wir hoffen, dass Sie einen wunderbaren Tag im Kreise Ihrer Liebsten verbringen und sich gebührend feiern lassen.</p>
                        <p>Um diesen besonderen Anlass noch festlicher zu gestalten, möchten wir Ihnen als treuer Kunde der <strong>Autovermietung jomaface</strong> ein kleines Geschenk machen. Zu Ihrem Geburtstag erhalten Sie von uns einen exklusiven Rabatt von <strong>" . htmlspecialchars($RabattProzentsatz) . "%</strong> auf Ihre nächste Fahrzeuganmietung.</p>
                        <p>Verwenden Sie einfach den folgenden Rabattcode bei Ihrer nächsten Buchung:</p>
                        <p><strong>" . htmlspecialchars($RabattCode) . "</strong></p>
                        <p>Der Rabattcode ist bis zum <strong>" . htmlspecialchars($ablaufDatum) . "</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Besuchen Sie unsere Kundenportal <a href='[www.autovermietung-jomaface.de]'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Reise zu planen.</p>
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
    } else {
        echo 'Heute hat kein Firmenkunde Geburtstag!<br><br>';
        logmessage("[Geburstags-Mails] Kein Firmenkunde hat heute Geburtstag!");

    }
}

    echo '<style>
    .button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: green;
        color: white;
        border: none;
        border-radius: 5px;
        text-decoration: none; /* Entfernen Sie die Unterstreichung für Links */
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background-color: darkgreen;
    }
    </style>';

    echo '<a href="marketingStartseite.html" class="button">Zurück zum Marketing-Portal</a>';


} catch (Exception $e) {
    logmessage("[Geburtstags-Mails] Message could not be sent. Mailer Error: {$mail->ErrorInfo}", "ERROR");
}
?>