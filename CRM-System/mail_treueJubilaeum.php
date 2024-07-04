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
    logMessage("[Treue-Mails] Verbindung zur Datenbank kann nicht hergestellt werden" . $conn->connect_error, "ERROR");
} else {
    logMessage("[Treue-Mails] Verbindung zur Datenbank wurde hergestellt und überprüft" . $conn->connect_error);
}

$heutigesDatum = date('Y-m-d');
$heutigesDatum_date = new DateTime($heutigesDatum);
$ablaufDatum = date('Y-m-d', strtotime($heutigesDatum . ' + 30 days'));

try {
    $sql = "SELECT Art, Rabatt FROM Rabattart WHERE Art Like 'Treue%'";
    $result_Rabatt = mysqli_query($conn, $sql);
    if ($result_Rabatt) {
        // Ergebnisse durchlaufen
        while ($row = mysqli_fetch_assoc($result_Rabatt)) {
            $art = $row['Art'];
            $rabatt = $row['Rabatt'];

            // switch-Anweisung basierend auf dem Wert von $art
            switch ($art) {
                case 'Treue_2':
                    // Treue-Aktion für 2 Jahre
                    $treueArt_2 = $art;
                    $treueJahre_2 = 2;
                    $treueProzentsatz_2 = $rabatt;
                    break;
                case 'Treue_5':
                    // Treue-Aktion für 5 Jahre
                    $treueArt_5 = $art;
                    $treueJahre_5 = 5;
                    $treueProzentsatz_5 = $rabatt;
                    break;
                case 'Treue_10':
                    // Treue-Aktion für 10 Jahre
                    $treueArt_10 = $art;
                    $treueJahre_10 = 10;
                    $treueProzentsatz_10 = $rabatt;
                    break;
                // Weitere Fälle hinzufügen, wenn nötig
                default:
                    break;
            }
        }
    }



    $sql = "SELECT DISTINCT a.Mail, b.KundenID, b.Vorname, b.Name, b.Eintrittsdatum FROM kontaktdaten AS a INNER JOIN privatkunde AS b ON a.PKundenID = b.PKundenID WHERE (a.PKundenID IS NOT NULL) AND ((b.Eintrittsdatum) = DATE_SUB('$heutigesDatum', INTERVAL 2 YEAR) OR (b.Eintrittsdatum) = DATE_SUB('$heutigesDatum', INTERVAL 5 YEAR) OR (b.Eintrittsdatum) = DATE_SUB('$heutigesDatum', INTERVAL 10 YEAR))";
    $result_PKunden = mysqli_query($conn, $sql);
    $rabattCode = generateRandomCode();
    if ($result_PKunden) {
        if (mysqli_num_rows($result_PKunden) > 0) {
            echo 'Privatkunden:';
            echo '<table border="1">';
            echo '<tr><th>Vorname</th><th>Nachname</th><th>KundenID</th><th>E-Mail</th><th>RabattCode</th><th>Prozentsatz</th></tr>';
            while ($row = $result_PKunden->fetch_assoc()) {
                $kundenID = $row['KundenID'];
                $eintrittsdatum = $row['Eintrittsdatum'];
                $eintrittsdatum_date = new DateTime($eintrittsdatum);
                $interval = $eintrittsdatum_date->diff($heutigesDatum_date);
                $jahreDifferenz = $interval->y;


                switch ($jahreDifferenz) {
                    case 2:
                        $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$rabattCode', '$heutigesDatum', '$ablaufDatum', false, $treueProzentsatz_2, '$treueArt_2', $kundenID)";
                        $prozentsatz = $treueProzentsatz_2;
                        break;
                    case 5:
                        $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$rabattCode', '$heutigesDatum', '$ablaufDatum', false, $treueProzentsatz_5, '$treueArt_5', $kundenID)";
                        $prozentsatz = $treueProzentsatz_2;
                        break;
                    case 10:
                        $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$rabattCode', '$heutigesDatum', '$ablaufDatum', false, $treueProzentsatz_10, '$treueArt_10', $kundenID)";
                        $prozentsatz = $treueProzentsatz_2;
                        break;
                    default:
                        break;
                }
                if ($conn->query($sql) === TRUE) {
                    logMessage("[Treue-Mails] Mail versendet an: " . $row['Vorname'] . " " . $row['Name'] . " (KundenID: " . $kundenID . ") mit der Email-Adresse " . $row['Mail'] . " (RabattCode: " . $rabattCode . " für " . $jahreDifferenz . " Jahre, " . $prozentsatz . ")");
                    echo '<tr>';
                    echo '<td>' . $row['Vorname'] . '</td>';
                    echo '<td>' . $row['Name'] . '</td>';
                    echo '<td>' . $kundenID . '</td>';
                    echo '<td>' . $row['Mail'] . '</td>';
                    echo '<td>' . $rabattCode . '</td>';
                    echo '<td>' . $prozentsatz * 100 . '%</td>';
                    echo '</tr>';
                } else {
                    logMessage("[Treue-Mails] Mail konnte nicht versendet an: " . $row['Vorname'] . " " . $row['Name'] . " (KundenID: " . $kundenID . ") mit der Email-Adresse " . $row['Mail'] . " werden! (RabattCode: " . $rabattCode . " für " . $jahreDifferenz . " Jahre, " . $prozentsatz * 100 . ")", "ERROR");

                }
                $rabattCode = generateRandomCode();

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
                    $mail->Subject = 'Danke für Ihre Treue – Ihr exklusiver Rabattcode wartet auf Sie!';
                    $mail->isHTML(true);
                    $mail->Body = "
        <!DOCTYPE html>
        <html lang='de'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Danke für Ihre Treue</title>
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
                    <p>zu Ihrem besonderen Jubiläum möchten wir Ihnen unsere herzlichsten Glückwünsche übermitteln. Wir freuen uns sehr, dass Sie uns bereits seit <strong>" .htmlspecialchars($treueJahre_5) . " Jahren</strong> die Treue halten.</p>
                    <p>Um dieses besondere Ereignis gebührend zu feiern, möchten wir Ihnen als treuer Kunde der <strong>Autovermietung jomaface</strong> ein kleines Geschenk machen. Zu Ihrem Jubiläum erhalten Sie von uns einen exklusiven Rabatt von <strong>" .htmlspecialchars($treueProzentsatz_5)*100 . "%</strong> auf Ihre nächste Fahrzeuganmietung.</p>
                    <p>Verwenden Sie einfach den folgenden Rabattcode bei Ihrer nächsten Buchung:</p>
                    <p><strong>" . htmlspecialchars($rabattCode) . "</strong></p>
                    <p>Der Rabattcode ist bis zum <strong>" . htmlspecialchars($ablaufDatum) . "</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Besuchen Sie unser Kundenportal <a href='https://www.autovermietung-jomaface.de'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Reise zu planen.</p>
                    <p>Wir möchten uns bei Ihnen für Ihre Treue und Ihr Vertrauen bedanken und freuen uns darauf, Sie bald wieder bei uns begrüßen zu dürfen.</p>
                    <p>Genießen Sie Ihr Jubiläum und lassen Sie sich reich beschenken!</p>
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
            echo 'Keine Treue-Mails an Privatkunden versendet. Heute feiert kein Privatkunde Jubiläum!<br><br>';
            logmessage("[Treue-Mails] Keine Mails versendet. Heute feiert kein Kunde Jubiläum!");
        }
    }



    // Noch mal für Firmenkunden
    $sql = "SELECT DISTINCT a.Mail, b.KundenID, b.Vorname, b.Name as Nachname, c.Firmenname as Firmenname, b.Eintrittsdatum FROM firma as c inner join kontaktdaten AS a on c.KontaktID = a.KontaktID INNER JOIN firmenkunde AS b ON a.FKundenID = b.FKundenID WHERE (a.FKundenID IS NOT NULL) AND ((b.Eintrittsdatum) = DATE_SUB('2024-07-04', INTERVAL 2 YEAR) OR (b.Eintrittsdatum) = DATE_SUB('2024-07-04', INTERVAL 5 YEAR) OR (b.Eintrittsdatum) = DATE_SUB('2024-07-04', INTERVAL 10 YEAR))";
    $rabattCode = generateRandomCode();
    $result_FKunden = mysqli_query($conn, $sql);
    if ($result_FKunden) {
        if (mysqli_num_rows($result_PKunden) > 0) {
            echo 'Firmenkunden:';
            echo '<table border="1">';
            echo '<tr><th>Vorname</th><th>Nachname</th><th>Firma</th><th>KundenID</th><th>E-Mail</th><th>RabattCode</th><th>Prozentsatz</th></tr>';
            while ($row = $result_FKunden->fetch_assoc()) {
                $kundenID = $row['KundenID'];
                $eintrittsdatum = $row['Eintrittsdatum'];
                $eintrittsdatum_date = new DateTime($eintrittsdatum);
                $interval = $eintrittsdatum_date->diff($heutigesDatum_date);
                $jahreDifferenz = $interval->y;


                switch ($jahreDifferenz) {
                    case 2:
                        $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$rabattCode', '$heutigesDatum', '$ablaufDatum', false, $treueProzentsatz_2, '$treueArt_2', $kundenID)";
                        break;
                    case 5:
                        $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$rabattCode', '$heutigesDatum', '$ablaufDatum', false, $treueProzentsatz_5, '$treueArt_5', $kundenID)";
                        break;
                    case 10:
                        $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$rabattCode', '$heutigesDatum', '$ablaufDatum', false, $treueProzentsatz_10, '$treueArt_10', $kundenID)";
                        break;
                    default:
                        break;
                }
                if ($conn->query($sql) === TRUE) {
                    logMessage("[Treue-Mails] Mail versendet an: " . $row['Vorname'] . " " . $row['Nachname'] . " der Firma " . $row['Firmenname'] . " (KundenID: " . $kundenID . ") mit der Email-Adresse " . $row['Mail'] . " (RabattCode: " . $rabattCode . " für " . $jahreDifferenz . " Jahre, " . $prozentsatz . ")");
                    echo '<tr>';
                    echo '<td>' . $row['Vorname'] . '</td>';
                    echo '<td>' . $row['Nachname'] . '</td>';
                    echo '<td>' . $row['Firmenname'] . '</td>';
                    echo '<td>' . $kundenID . '</td>';
                    echo '<td>' . $row['Mail'] . '</td>';
                    echo '<td>' . $rabattCode . '</td>';
                    echo '<td>' . $prozentsatz * 100 . '%</td>';
                    echo '</tr>';
                } else {
                    logMessage("[Treue-Mails] Mail konnte nicht versendet an: " . $row['Vorname'] . " " . $row['Nachname'] . " der Firma " . $row['Firmenname'] . " (KundenID: " . $kundenID . ") mit der Email-Adresse " . $row['Mail'] . " werden! (RabattCode: " . $rabattCode . " für " . $jahreDifferenz . " Jahre, " . $prozentsatz . ")", "ERROR");

                }
                $rabattCode = generateRandomCode();

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
            $mail->Subject = 'Danke für Ihre Treue – Ihr exklusiver Rabattcode wartet auf Sie!';
            $mail->isHTML(true);
            $mail->Body = "
<!DOCTYPE html>
<html lang='de'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Danke für Ihre Treue</title>
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
            <p>zu Ihrem besonderen Jubiläum möchten wir Ihnen unsere herzlichsten Glückwünsche übermitteln. Wir freuen uns sehr, dass Sie uns bereits seit <strong>" . htmlspecialchars($treueJahre_5) . " Jahren</strong> die Treue halten.</p>
            <p>Um dieses besondere Ereignis gebührend zu feiern, möchten wir Ihnen als treuer Kunde der <strong>Autovermietung jomaface</strong> ein kleines Geschenk machen. Zu Ihrem Jubiläum erhalten Sie von uns einen exklusiven Rabatt von <strong>" . htmlspecialchars($treueProzentsatz_5) * 100 . "%</strong> auf Ihre nächste Fahrzeuganmietung.</p>
            <p>Verwenden Sie einfach den folgenden Rabattcode bei Ihrer nächsten Buchung:</p>
            <p><strong>" . htmlspecialchars($rabattCode) . "</strong></p>
            <p>Der Rabattcode ist bis zum <strong>" . htmlspecialchars($ablaufDatum) . "</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Besuchen Sie unser Kundenportal <a href='https://www.autovermietung-jomaface.de'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Reise zu planen.</p>
            <p>Wir möchten uns bei Ihnen für Ihre Treue und Ihr Vertrauen bedanken und freuen uns darauf, Sie bald wieder bei uns begrüßen zu dürfen.</p>
            <p>Genießen Sie Ihr Jubiläum und lassen Sie sich reich beschenken!</p>
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
            echo 'Keine Treue-Mails an Firmenkunden versendet. Heute feiert kein Firmenkunde Jubiläum!<br><br>';
            logmessage("[Treue-Mails] Keine Mails versendet. Heute feiert kein Kunde Jubiläum!");
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
    logmessage("[Treue-Mails] Message could not be sent. Mailer Error: {$mail->ErrorInfo}", "ERROR");
}

?>