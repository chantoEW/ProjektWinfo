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
    logMessage("[Erinnerungs-Mails] Verbindung zur Datenbank kann nicht hergestellt werden" . $conn->connect_error, "ERROR");
} else {
    logMessage("[Erinnerungs-Mails] Verbindung zur Datenbank wurde hergestellt und überprüft");
}

$heutigesDatum = date('Y-m-d');
$heutigesDatum_date = new DateTime($heutigesDatum);

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

    // Empfänger, Betreff und Nachricht einstellen
    $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
    $mail->addAddress('fschlaghecken@gmx.de', 'Fabian Schlaghecken');
    $mail->Subject = 'Vergessen Sie Ihren Rabattcode nicht!';
    $mail->isHTML(true);

    // Datensätze für Privatkunden ermitteln
    $sql = "SELECT DISTINCT a.Mail, b.Vorname, b.Name, c.KundenID, c.Prozentsatz, c.Rabattart, c.Rabattcode, c.Datum_FristEnde FROM kontaktdaten AS a, privatkunde as b, rabattlog as c WHERE c.KundenID = b. KundenID AND b.PKundenID = a.PKundenID AND c.Eingeloest = false AND c.Datum_FristEnde = (CURDATE() + INTERVAL 7 DAY)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo 'Privatkunden:';
            echo '<table border="1">';
            echo '<tr><th>Vorname</th><th>Nachname</th><th>KundenID</th><th>E-Mail</th><th>RabattCode</th><th>Prozentsatz</th><th>Rabattart</th></tr>';

        while($row = $result->fetch_assoc()) {
            $KundenID_PK = $row['KundenID'];
            $mail_PK = $row['Mail'];
            $vorname_PK = $row['Vorname'];
            $name_PK = $row['Name'];
            $prozentsatz_PK = $row['Prozentsatz']*100;
            $rabattart_PK = $row['Rabattart'];
            $rabatcode_PK = $row['Rabattcode'];
            $datum_FristEnde_PK = $row['Datum_FristEnde'];

            logMessage("[Erinnerungs-Mails] Mail versendet an: " . $mail_PK . ", " . $vorname_PK . ", KundenID:  " . $KundenID_PK . ", Rabattcode: " . $name_PK . ", Rabattcode: " . $rabatcode_PK . ", Rabattart: " . $rabattart_PK . ", " . $prozentsatz_PK . "%, FristEnde: " . $datum_FristEnde_PK);
            echo '<tr>';
            echo '<td>' . $vorname_PK . '</td>';
            echo '<td>' . $name_PK . '</td>';
            echo '<td>' . $KundenID_PK . '</td>';
            echo '<td>' . $mail_PK . '</td>';
            echo '<td>' . $rabatcode_PK . '</td>';
            echo '<td>' . $prozentsatz_PK . '%</td>';
            echo '<td>' . $rabattart_PK . '%</td>';
            echo '</tr>';
        }

            echo '</table><br><br>';

        // Nur die Mail zum letzten Datensatz raussenden, falls Datensätze existieren

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
                            <p>Sehr geehrte(r) Frau/Herr " . htmlspecialchars($vorname_PK) . " " . htmlspecialchars($name_PK) . ",</p>
                            <p>wir hoffen, dass Sie mit unseren Dienstleistungen zufrieden sind und bereits viele schöne Fahrten mit unseren Fahrzeugen genießen konnten.</p>
                            <p>Vor Kurzem haben wir Ihnen einen exklusiven <strong>" . htmlspecialchars($prozentsatz_PK) . "%</strong>-Rabattcode zugesendet, den Sie bei Ihrer nächsten Fahrzeuganmietung verwenden können. Wir möchten Sie daran erinnern, dass dieser Rabattcode in <strong>7 Tagen</strong> abläuft.</p>
                            <p>Ihr Rabattcode lautet:</p>
                            <p><strong>" . htmlspecialchars($rabatcode_PK) . "</strong></p>
                            <p>Der Rabattcode ist bis zum <strong>" . htmlspecialchars($datum_FristEnde_PK) . "</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Zögern Sie nicht, ihn einzulösen und profitieren Sie von unserem exklusiven Angebot!</p>
                            <p>Besuchen Sie unser Kundenportal <a href='https://www.autovermietung-jomaface.de'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Buchung vorzunehmen.</p>
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

            // Privatkunden-E-Mail senden
            $mail->send();
        }
        else
        {
            echo 'Keine Erinnerungs-Mails an Privatkunden versendet. Kein Rabattcode läuft In 7 Tagen ab!<br><br>';
            logmessage("[Erinnerungs-Mail] Keine Erinnerungs-Mails an Privatkunden versendet. Kein Rabattcode läuft In 7 Tagen ab!");
        }

    }


    // Firmen-Kunden ermitteln
    $sql = "SELECT DISTINCT a.Mail, b.Vorname, b.Name as Nachname, c.KundenID, d.Firmenname as Firma, c.Prozentsatz, c.Rabattart, c.Rabattcode, c.Datum_FristEnde FROM kontaktdaten AS a, firmenkunde as b, rabattlog as c, firma as d WHERE c.KundenID = b. KundenID AND b.FKundenID = a.FKundenID AND c.KundenID = d.KundenID AND c.Eingeloest = false AND c.Datum_FristEnde = (CURDATE() + INTERVAL 7 DAY)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo 'Firmenkunden:';
            echo '<table border="1">';
            echo '<tr><th>Vorname</th><th>Nachname</th><th>Firma</th><th>KundenID</th><th>E-Mail</th><th>RabattCode</th><th>Prozentsatz</th><th>Rabattart</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $KundenID_FK = $row['KundenID'];
                $mail_FK = $row['Mail'];
                $vorname_FK = $row['Vorname'];
                $nachname_FK = $row['Nachname'];
                $firma_FK = $row['Firma'];
                $prozentsatz_FK = $row['Prozentsatz'] * 100;
                $rabattart_FK = $row['Rabattart'];
                $rabatcode_FK = $row['Rabattcode'];
                $datum_FristEnde_FK = $row['Datum_FristEnde'];

                logMessage("[Erinnerungs-Mails] Mail versendet an: " . $mail_FK . ", " . $vorname_FK . ", " . $nachname_FK . " von Firma " . $firma_FK . ", KundenID:  " . $KundenID_FK . ", Rabattcode: " . $rabatcode_FK . ", Rabattart: " . $rabattart_FK . ", " . $prozentsatz_FK . "%, FristEnde: " . $datum_FristEnde_FK);
                echo '<tr>';
                echo '<td>' . $vorname_FK . '</td>';
                echo '<td>' . $nachname_FK . '</td>';
                echo '<td>' . $firma_FK . '</td>';
                echo '<td>' . $KundenID_FK . '</td>';
                echo '<td>' . $mail_FK . '</td>';
                echo '<td>' . $rabatcode_FK . '</td>';
                echo '<td>' . $prozentsatz_FK . '%</td>';
                echo '<td>' . $rabattart_FK . '</td>';
                echo '</tr>';
            }
            echo '</table><br><br>';

            // Nur die Mail zum letzten Datensatz raussenden, falls Datensätze existieren
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
                                <p>Sehr geehrte(r) Frau/Herr " . htmlspecialchars($vorname_FK) . " " . htmlspecialchars($nachname_FK) . " der Firma " . htmlspecialchars($firma_FK) . ",</p>
                                <p>wir hoffen, dass Sie mit unseren Dienstleistungen zufrieden sind und bereits viele schöne Fahrten mit unseren Fahrzeugen genießen konnten.</p>
                                <p>Vor Kurzem haben wir Ihnen einen exklusiven <strong>" . htmlspecialchars($prozentsatz_FK) . "%</strong>-Rabattcode zugesendet, den Sie bei Ihrer nächsten Fahrzeuganmietung verwenden können. Wir möchten Sie daran erinnern, dass dieser Rabattcode in <strong>7 Tagen</strong> abläuft.</p>
                                <p>Ihr Rabattcode lautet:</p>
                                <p><strong>" . htmlspecialchars($rabatcode_FK) . "</strong></p>
                                <p>Der Rabattcode ist bis zum <strong>" . htmlspecialchars($datum_FristEnde_FK) . "</strong> gültig und kann auf alle verfügbaren Fahrzeugkategorien angewendet werden. Zögern Sie nicht, ihn einzulösen und profitieren Sie von unserem exklusiven Angebot!</p>
                                <p>Besuchen Sie unser Kundenportal <a href='https://www.autovermietung-jomaface.de'>www.autovermietung-jomaface.de</a> oder kontaktieren Sie unser Serviceteam, um Ihre nächste Buchung vorzunehmen.</p>
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
            // Firmenkunden-E-Mail senden
            $mail->send();
        }
        else
        {
            echo 'Keine Erinnerungs-Mails an Firmenkunden versendet. Kein Rabattcode läuft In 7 Tagen ab!<br>';
            logmessage("[Erinnerungs-Mail] Keine Erinnerungs-Mails an Firmenkunden versendet. Kein Rabattcode läuft In 7 Tagen ab!");
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
        logmessage("[Erinnerungs-Mails] Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
}

?>