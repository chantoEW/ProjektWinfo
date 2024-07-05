<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

session_start();
// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

function logMessage($message, $type = 'INFO') {
    $logFile = 'logfile.txt';
    $formattedMessage = date('Y-m-d H:i:s') . " - [$type] - " . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Funktion zum Generieren eines zufälligen Rabattcodes
function generateRandomCode($length = 20)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
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
    logMessage("[MarketingaktionMail] Verbindung zur Datenbank kann nicht hergestellt werden");
} else {
    echo ("Verbindung zur DB wurde hergestellt.");
    logMessage("[MarketingaktionMail] Verbindung zur Datenbank wurde hergestellt und überprüft");
}

//Variablen aus marketingaktionStarten.php
$emailSubject = $_POST['emailSubject'];
$emailBody = $_POST['emailBody'];
$aktionsart = $_POST['discountSelect'];
$empfaenger = $_POST['empfaenger'];
$aktionsartText = $_POST['discountText'];
$rabattProzentsatz = $_POST['prozentsatz'];


$firstSpacePos = strpos($aktionsartText, ' ');

if($firstSpacePos !== false) {
    $firstPart = substr($aktionsartText, 0, $firstSpacePos);
}
else{
    $firstPart = $aktionsartText;
}

    
try {
    $sql = "SELECT Rabatt FROM Rabattart WHERE Art = '$firstPart'";
    $result_Rabatt = mysqli_query($conn, $sql);

    /*if ($result_Rabatt && $row = mysqli_fetch_assoc($result_Rabatt)) {
        $rabattProzentsatz = $row['Rabatt'];
        
    } else {
        throw new Exception("Rabattart nicht gefunden.");
    }*/

    $heutigesDatum = date('Y-m-d');
    $ablaufDatum = date('Y-m-d', strtotime($heutigesDatum . ' + 30 days'));


    // Den Startpunkt des Rabattcodes finden
    $startPos = strpos($emailBody, 'Rabatt-Code: ');
    
    if ($startPos !== false) {
        // Die Position des Codes selbst berechnen
        $startPos += strlen('Rabatt-Code: ');
        // Den Rabattcode extrahieren, bis zum Ende des Strings oder bis zu einem Zeilenumbruch
        $endPos = strpos($emailBody, "\n", $startPos);
        if ($endPos === false) {
            $endPos = strlen($emailBody);
        }
        $rabattCodeAusFormular = trim(substr($emailBody, $startPos, $endPos - $startPos));
       
    } else {
        echo "Kein Rabattcode gefunden.";
    }


    if ($empfaenger == 'a-kunden') {
        $sql = "SELECT kd.Mail, pk.Name, pk.Vorname, k.KundenID
        FROM kunden AS k
        JOIN privatkunde AS pk
        ON k.PKundenID = pk.PKundenID
        JOIN kontaktdaten AS kd
        ON pk.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON k.KundenID = ka.KundenID
        WHERE ka.ABC_Klasse = 'A'";
        $result_privatkundenMails = mysqli_query($conn, $sql);

        $sql = "SELECT kd.Mail, fk.Name, fk.Vorname, k.KundenID 
        FROM kunden AS k
        JOIN firmenkunde AS fk
        ON k.FKundenID = fk.FKundenID
        JOIN firma
        ON firma.FirmenID = fk.FirmenID
        JOIN kontaktdaten AS kd
        ON firma.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON k.KundenID = ka.KundenID
        WHERE ka.ABC_Klasse = 'A'";
        $result_firmenkundenMails = mysqli_query($conn, $sql);
    } else if ($empfaenger == 'b-kunden') {
        $sql = "SELECT kd.Mail, pk.Name, pk.Vorname, k.KundenID
        FROM kunden AS k
        JOIN privatkunde AS pk
        ON k.PKundenID = pk.PKundenID
        JOIN kontaktdaten AS kd
        ON pk.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON k.KundenID = ka.KundenID
        WHERE ka.ABC_Klasse = 'B'";
        $result_privatkundenMails = mysqli_query($conn, $sql);

        $sql = "SELECT kd.Mail, fk.Name, fk.Vorname, k.KundenID
        FROM kunden AS k
        JOIN firmenkunde AS fk
        ON k.FKundenID = fk.FKundenID
        JOIN firma
        ON firma.FirmenID = fk.FirmenID
        JOIN kontaktdaten AS kd
        ON firma.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON k.KundenID = ka.KundenID
        WHERE ka.ABC_Klasse = 'B'";
        $result_firmenkundenMails = mysqli_query($conn, $sql);
    } else if ($empfaenger == 'c-kunden') {
        $sql = "SELECT kd.Mail, pk.Name, pk.Vorname, k.KundenID 
        FROM kunden AS k
        JOIN privatkunde AS pk
        ON k.PKundenID = pk.PKundenID
        JOIN kontaktdaten AS kd
        ON pk.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON k.KundenID = ka.KundenID
        WHERE ka.ABC_Klasse = 'C'";
        $result_privatkundenMails = mysqli_query($conn, $sql);

        $sql = "SELECT kd.Mail, fk.Name, fk.Vorname, k.KundenID 
        FROM kunden AS k
        JOIN firmenkunde AS fk
        ON k.FKundenID = fk.FKundenID
        JOIN firma
        ON firma.FirmenID = fk.FirmenID
        JOIN kontaktdaten AS kd
        ON firma.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON k.KundenID = ka.KundenID
        WHERE ka.ABC_Klasse = 'C'";
        $result_firmenkundenMails = mysqli_query($conn, $sql);
    } else if ($empfaenger == 'alle') {
        $sql = "SELECT kd.Mail, pk.Name, pk.Vorname, k.KundenID
            FROM kunden AS k
            JOIN privatkunde AS pk
            ON k.PKundenID = pk.PKundenID
            JOIN kontaktdaten AS kd
            ON pk.KontaktID = kd.KontaktID";
        $result_privatkundenMails = mysqli_query($conn, $sql);

        $sql = "SELECT kd.Mail, fk.Name, fk.Vorname, k.KundenID 
            FROM kunden AS k
            JOIN firmenkunde AS fk
            ON k.FKundenID = fk.FKundenID
            JOIN firma
            ON firma.FirmenID = fk.FirmenID
            JOIN kontaktdaten AS kd
            ON firma.KontaktID = kd.KontaktID";
        $result_firmenkundenMails = mysqli_query($conn, $sql);
    }

    if ($result_privatkundenMails) {
        while ($row = $result_privatkundenMails->fetch_assoc()) {
            $RabattCode = $rabattCodeAusFormular;
            $kundenID = $row['KundenID'];
            $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$RabattCode', '$heutigesDatum', '$ablaufDatum', false, $rabattProzentsatz, '$firstPart', $kundenID)";
            if ($conn->query($sql) === TRUE) {
                logMessage("[MarketingaktionMail] $aktionsart" . "-Mail versendet an: " . $row['Vorname'] . " " . $row['Name'] . " mit der Email-Adresse " . $row['Mail']);
            } else {
                logMessage("[MarketingaktionMail] Fehler beim Einfügen des Rabattcodes für: " . $row['Vorname'] . " " . $row['Name'], "ERROR");
            }
        }
    }

    if ($result_firmenkundenMails) {
        while ($row = $result_firmenkundenMails->fetch_assoc()) {
            $RabattCode = $rabattCodeAusFormular;
            $kundenID = $row['KundenID'];
            $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$RabattCode', '$heutigesDatum', '$ablaufDatum', false, $rabattProzentsatz, '$firstPart', $kundenID)";
            if ($conn->query($sql) === TRUE) {
                logMessage("[MarketingaktionMail] $aktionsart" . "-Mail versendet an: " . $row['Vorname'] . " " . $row['Name'] . " mit der Email-Adresse " . $row['Mail']);
            } else {
                logMessage("[MarketingaktionMail] Fehler beim Einfügen des Rabattcodes für: " . $row['Vorname'] . " " . $row['Name'], "ERROR");
            }
        }
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
    $mail->addAddress('maxiwehning@gmail.com', 'Maximilian Wehning');
    $mail->Subject = $emailSubject;
    $mail->isHTML(false);
    $mail->Body = $emailBody;

    // E-Mail senden
    $mail->send();
    echo 'Email sent successfully.';
    $_SESSION['success_message'] = 'Die Email wurde erfolgreich versendet.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    $_SESSION['error_message'] = "Beim Versenden der Email ist ein Fehler aufgetreten.";
}

// Nach dem Ausführen aller Aktionen auf marketingaktionStarten.php weiterleiten
header('Location: marketingaktionStarten.php');
exit;

?>