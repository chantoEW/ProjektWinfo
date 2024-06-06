<?php
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
}

// Benutzernamen aus der GET-Anfrage erhalten
$benutzername = $_GET['benutzername'];

// SQL-Abfrage zum Überprüfen des Benutzernamens in der Datenbank
$sql = "SELECT * FROM user WHERE Benutzername = '$benutzername'";
$result = mysqli_query($conn, $sql);

// Überprüfen, ob der Benutzername bereits existiert
if ($result->num_rows > 0) {
    echo "exists"; // Der Benutzername existiert bereits
}

// Verbindung schließen
$conn->close();
?>
