<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Verbindung zur Datenbank herstellen
    $servername = "localhost";
    $username = "Chantal";
    $password = "";
    $dbname = "autovermietung";

// Verbindung herstellen
    $conn = new mysqli($servername, $username, $password, $dbname);
// Verbindung überprüfen
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    } else {
        echo("Verbindung zur DB wurde hergestellt");
    }

// Daten aus dem Formular erhalten
    $benutzername = $_POST['benutzername'];
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];

    $rohGeburtsdatum = $_POST['geburtsdatum'];
    // Datum in das richtige Format konvertieren
    $geburtsdatum = date("Y-m-d", strtotime($rohGeburtsdatum));

    $passwort = $_POST['passwort'];
    $email = $_POST['email'];
    $firmenname = $_POST['firmenname'];

    // Passwort hashen
    $hashed_password = password_hash($passwort, PASSWORD_DEFAULT);

    // Überprüfe, welcher Kundentyp ausgewählt wurde
    if (isset($_POST['kundentyp'])) {
        $kundentyp = $_POST['kundentyp'];

        // Hier kannst du entsprechende Aktionen basierend auf dem Kundentyp durchführen
        if ($kundentyp == 'Privatkunde') {
            // SQL-Query zum Einfügen der Daten in die entsprechende Tabelle
            $sql = "INSERT INTO privatkunde (Benutzername, Vorname, Nachname, Passwort, eMail, Geburtsdatum) VALUES ('$benutzername', '$vorname', '$nachname', '$hashed_password', '$email', '$geburtsdatum')";
        } elseif ($kundentyp == 'Geschäftskunde') {
            // SQL-Query zum Einfügen der Daten in die entsprechende Tabelle
            $sql = "INSERT INTO geschäftskunde (Benutzername, Vorname, Nachname, Passwort, eMail, Geburtsdatum, Firma) VALUES ('$benutzername', '$vorname', '$nachname', '$hashed_password', '$email', '$geburtsdatum', '$firmenname')";
        } else {
            echo("Kundentyp ist ungültig");
        }
    } else {
        echo("Kundentyp nicht ausgewählt");
    }


// SQL-Query ausführen
    if (mysqli_query($conn, $sql)) {
        echo "Daten erfolgreich in die Tabelle eingefügt.";
    } else {
        echo "Fehler beim Einfügen der Daten: " . mysqli_error($conn);
    }

// Verbindung schließen
    mysqli_close($conn);

} else {
    echo "Formular wurde nicht gesendet";
}
?>
