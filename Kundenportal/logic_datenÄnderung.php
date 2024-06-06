<?php
session_start();

// Überprüfe, ob der Benutzer angemeldet ist
if (isset($_SESSION['benutzername'])) {
    // Verbindung zur Datenbank herstellen
    $servername = "localhost";
    $username = "Chantal";
    $password = "";
    $dbname = "autovermietung";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Überprüfe die Verbindung
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // Benutzername aus der Session abrufen
    $benutzername = $_SESSION['benutzername'];

    // Überprüfe, ob das Formular gesendet wurde
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Formulardaten abrufen
        $vorname = $_POST['vorname'];
        $nachname = $_POST['nachname'];
        // $passwort = $_POST['passwort']; // Beachte hier die Sicherheitsrisiken bei der Speicherung von Passwörtern!
        $email = $_POST['email'];
        $geburtsdatum = $_POST['geburtsdatum'];
        $kundentyp = $_POST['kundentyp'];
        $firmenname = isset($_POST['firmenname']) ? $_POST['firmenname'] : ""; // Überprüfe, ob ein Wert für Firmenname übergeben wurde

        // SQL-Abfrage vorbereiten
        $sql = "UPDATE ";
        if ($kundentyp == 'Privatkunde') {
            $sql .= "privatkunde SET Vorname='$vorname', Nachname='$nachname', Passwort='$passwort', Email='$email', Geburtsdatum='$geburtsdatum' ";
        } else {
            $sql .= "geschäftskunde SET Vorname='$vorname', Nachname='$nachname', Passwort='$passwort', Email='$email', Geburtsdatum='$geburtsdatum', Firmenname='$firmenname' ";
        }
        $sql .= "WHERE Benutzername='$benutzername'";

        // SQL-Abfrage ausführen
        if ($conn->query($sql) === TRUE) {
            echo "Daten erfolgreich aktualisiert!";
        } else {
            echo "Fehler beim Aktualisieren der Daten: " . $conn->error;
        }
    } else {
        echo "Das Formular wurde nicht gesendet.";
    }

    // Verbindung schließen
    $conn->close();
} else {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header("Location: logic_login.php");
    exit();
}
?>
