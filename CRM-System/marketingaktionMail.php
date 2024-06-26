<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

function logMessage($message)
{
    $logFile = 'logfile.json';
    $formattedMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Funktion zum Generieren eines zufälligen Rabattcodes
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
// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    logMessage("Verbindung zur Datenbank kann nicht hergestellt werden");
} else {
    echo ("Verbindung zur DB wurde hergestellt");
    logMessage("Verbindung zur Datenbank wurde hergestellt und überprüft");
}

//Variablen aus marketingaktionStarten.php
$emailSubject = $_POST['emailSubject'];
$emailBody = $_POST['emailBody'];
$aktionsart = $_POST['discountSelect'];
$empfaenger = $_POST['empfaenger'];


try {
    $sql = "SELECT Rabatt FROM Rabattart WHERE Art = '$aktionsart'";
    $result_Rabatt = mysqli_query($conn, $sql);

    if ($result_Rabatt && $row = mysqli_fetch_assoc($result_Rabatt)) {
        $rabattProzentsatz = $row['Rabatt'];
    } else {
        throw new Exception("Rabattart nicht gefunden.");
    }

    $heutigesDatum = date('Y-m-d');
    $ablaufDatum = date('Y-m-d', strtotime($heutigesDatum . ' + 30 days'));


    if ($empfaenger == 'a-kunden')
    {
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
    }
    else if($empfaenger == 'b-kunden')
    {
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
    }
    else if($empfaenger == 'c-kunden')
    {
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
    }
    else if($empfaenger == 'alle')
    {
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
            $RabattCode = generateRandomCode();
            $kundenID = $row['KundenID'];
            $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$RabattCode', '$heutigesDatum', '$ablaufDatum', false, $rabattProzentsatz, '$aktionsart', $kundenID)";
            if ($conn->query($sql) === TRUE) {
                logMessage("$aktionsart" . "-Mail versendet an: " . $row['Vorname'] . " " . $row['Name'] . " mit der Email-Adresse " . $row['Mail']);
            } else {
                logMessage("Fehler beim Einfügen des Rabattcodes für: " . $row['Vorname'] . " " . $row['Name']);
            }
        }
    }

    if ($result_firmenkundenMails) {
        while ($row = $result_firmenkundenMails->fetch_assoc()) {
            $RabattCode = generateRandomCode();
            $kundenID = $row['KundenID'];
            $sql = "INSERT INTO RabattLog (Rabattcode, Datum_Erzeugung, Datum_FristEnde, Eingeloest, Prozentsatz, Rabattart, KundenID) VALUES ('$RabattCode', '$heutigesDatum', '$ablaufDatum', false, $rabattProzentsatz, '$aktionsart', $kundenID)";
            if ($conn->query($sql) === TRUE) {
                logMessage("$aktionsart" . "-Mail versendet an: " . $row['Vorname'] . " " . $row['Name'] . " mit der Email-Adresse " . $row['Mail']);
            } else {
                logMessage("Fehler beim Einfügen des Rabattcodes für: " . $row['Vorname'] . " " . $row['Name']);
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
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>