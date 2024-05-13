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
}
else {echo("Verbindung zur DB wurde hergestellt");}

// Daten aus dem Formular erhalten
$vorname = $_POST['vorname'];
$nachname = $_POST['nachname'];
//$geburtsdatum = $_POST['geburtsdatum'];
//$kundentyp = $_POST['kundentyp'];

// Tabelle basierend auf dem Kundentyp auswählen
/*if ($kundentyp === 'Privatkunde') {
    $tabelle = 'Privatkunde';
} elseif ($kundentyp === 'Geschäftskunde') {
    $tabelle = 'Geschäftskunde';
} else {
    die("Ungültiger Kundentyp.");
}*/

// SQL-Query zum Einfügen der Daten in die entsprechende Tabelle
$sql = "INSERT INTO privatkunde (Vorname, Nachname) VALUES ('$vorname', '$nachname')";

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
