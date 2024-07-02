<?php
// Datenbankverbindung herstellen
$servername = "localhost";
$username = "projektwinfo";
$password = "Ocm394Ldmc";
$dbname = "portal";

// Verbindung erstellen
$conn = new mysqli($servername, $username, $password, $dbname);

function logMessage($message)
{
    $logFile = 'logfile.json';
    $formattedMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}


if (isset($_POST['kundentyp']) && isset($_POST['kundenId'])) {
    // POST-Daten empfangen
    $kundentyp = $_POST['kundentyp'];
    $kundenId = $_POST['kundenId'];

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