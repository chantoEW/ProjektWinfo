<?php
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

    // SQL-Abfrage erstellen
    if ($kundentyp == 'geschaeft') {
        $sql = "DELETE fk, kd, zi, user, firma, kunden
                FROM firmenkunde AS fk
                JOIN kontaktdaten AS kd ON fk.FKundenID = kd.FKundenID
                JOIN zahlungsinformationen AS zi ON fk.FKundenID = zi.KundenID
                JOIN user ON user.KundenID = fk.FKundenID
                JOIN firma ON fk.FKundenID = firma.kundenID
                JOIN kunden ON fk.FKundenID = kunden.FKundenID
                WHERE fk.FKundenID = ?;";
    } else if ($kundentyp == 'privat') {
        $sql = "DELETE pk, kd, zi, user, kunden
                FROM privatkunde AS pk
                JOIN kontaktdaten AS kd 
                ON pk.PKundenID = kd.PKundenID
                JOIN zahlungsinformationen AS zi 
                ON pk.PKundenID = zi.KundenID
                JOIN user 
                ON user.KundenID = pk.PKundenID
                JOIN kunden
                ON pk.PkundenID = kunden.PKundenID
                WHERE pk.PKundenID = ?";
    }

    // SQL-Abfrage vorbereiten
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kundenId);

    // Abfrage ausführen und Ergebnis überprüfen
    if ($stmt->execute()) {
        echo "Der Kunde wurde erfolgreich gelöscht.";
    } else {
        echo "Fehler beim Löschen des Kunden: " . $stmt->error;
    }

    // Statement schließen
    $stmt->close();
}

// Verbindung schließen
$conn->close();
?>