<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

function logMessage($message)
{
    $logFile = 'logfile.json';
    $formattedMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

function sendAccountDeletionEmail($kundentyp, $customerId)
{
    // Datenbankverbindung herstellen
    $servername = "localhost";
    $username = "Chantal";
    $password = "";
    $dbname = "portal";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        logMessage("Verbindung zur Datenbank kann nicht hergestellt werden: " . $conn->connect_error);
        return;
    }

    // Kundendaten abrufen
    if ($kundentyp == "privat")
    {
        $sql = "SELECT kd.Mail, pk.Name, pk.Vorname
        FROM kunden AS k
        JOIN privatkunde AS pk ON k.PKundenID = pk.PKundenID
        JOIN kontaktdaten AS kd ON pk.KontaktID = kd.KontaktID
        WHERE k.KundenID = ?";
    }
    else
    {
        $sql = "SELECT kd.Mail, fk.Name, fk.Vorname
        FROM kunden AS k
        JOIN firmenkunde AS fk ON k.FKundenID = fk.FKundenID
        JOIN firma ON firma.FirmenID = fk.FirmenID
        JOIN kontaktdaten AS kd ON firma.KontaktID = kd.KontaktID
        WHERE k.KundenID = ?";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        logMessage("Kunde mit ID $customerId nicht gefunden.");
        return;
    }

    // E-Mail-Inhalt generieren
    $emailSubject = "Ihr Konto wurde gelöscht";
    $emailBody = "Sehr geehrte/r " . $customer['Vorname'] . " " . $customer['Name'] . ",\n\n";
    $emailBody .= "Ihr Konto wurde erfolgreich gelöscht. Falls Sie Fragen haben oder Unterstützung benötigen, kontaktieren Sie bitte unseren Kundenservice.\n\n";
    $emailBody .= "Mit freundlichen Grüßen,\nIhr Kundenservice";

    // E-Mail-Instanz erstellen
    $mail = new PHPMailer(true);

    try {
        // Servereinstellungen konfigurieren
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'autovermietung.jomaface@outlook.de';
        $mail->Password = '!krusewhs';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Empfänger, Betreff und Nachricht einstellen
        $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
        $mail->addAddress('maxiwehning@gmail.com', 'Maximilian Wehning');
        $mail->Subject = $emailSubject;
        $mail->isHTML(false);
        $mail->Body = $emailBody;

        // E-Mail senden
        $mail->send();
        logMessage("Löschungs-E-Mail an " . $customer['Vorname'] . " " . $customer['Name'] . " gesendet.");
    } catch (Exception $e) {
        logMessage("E-Mail konnte nicht gesendet werden. Fehler: " . $mail->ErrorInfo);
    }

    // Verbindung schließen
    $conn->close();
}

// Datenbankverbindung herstellen
$servername = "localhost";
$username = "projektwinfo";
$password = "Ocm394Ldmc";
$dbname = "portal";

// Verbindung erstellen
$conn = new mysqli($servername, $username, $password, $dbname);



// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}


if (isset($_POST['kundentyp']) && isset($_POST['kundenId'])) {
    // POST-Daten empfangen
    $kundentyp = $_POST['kundentyp'];
    $kundenId = $_POST['kundenId'];

    sendAccountDeletionEmail($kundentyp, $kundenId);
    // SQL-Abfrage erstellen
    if ($kundentyp == 'geschaeft') {
        $sql = "DELETE k, fk, kd, zi, user, firma
                FROM kunden AS k
                JOIN firmenkunde AS fk
                ON fk.FKundenID = k.FKundenID
                JOIN zahlungsinformationen AS zi 
                ON fk.ZahlungsID = zi.ZahlungsID
                JOIN user 
                ON user.KundenID = k.KundenID
                JOIN firma 
                ON fk.FirmenID = firma.FirmenID
                JOIN kontaktdaten AS kd 
                ON firma.KontaktID = kd.KontaktID
                WHERE k.KundenID = ?;";
    } else if ($kundentyp == 'privat') {
        $sql = "DELETE k, pk, kd, zi, user
                FROM kunden AS k
                JOIN privatkunde AS pk
                ON pk.PKundenID = k.PKundenID
                JOIN kontaktdaten AS kd 
                ON pk.KontaktID = kd.KontaktID
                JOIN zahlungsinformationen AS zi 
                ON pk.ZahlungsID = zi.ZahlungsID
                JOIN user 
                ON user.KundenID = k.KundenID
                WHERE k.KundenID = ?";
    }

    // SQL-Abfrage vorbereiten
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kundenId);

    // Abfrage ausführen und Ergebnis überprüfen
    if ($stmt->execute()) {
        echo "Der Kunde wurde erfolgreich gelöscht.";
        logMessage("Die Daten für Kunde mit der ID $kundenId wurden gelöscht.");
    } else {
        echo "Fehler beim Löschen des Kunden: " . $stmt->error;
        logMessage("Fehler beim Löschen des Kunden mit der ID $KundenId.");
    }

    // Statement schließen
    $stmt->close();
}

// Verbindung schließen
$conn->close();
?>