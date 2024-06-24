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
    logMessage("Verbindung zur Datenbank kann nicht hergestellt werden");
} else {
    echo("Verbindung zur DB wurde hergestellt");
    logMessage("Verbindung zur Datenbank wurde hergestellt und überprüft");
}

if (isset($_GET['benutzername'])) {
    $benutzername = $_GET['benutzername'];

    // Benutzer mit diesem Token finden (Beispiel: PDO)
    $sql = "SELECT KundenID FROM users WHERE benutzername = '$benutzername'";
    if (mysqli_query($conn, $sql)) {
        $KundenID = mysqli_insert_id($conn);
        $sql = "SELECT PKundenID FROM kunden WHERE KundenID = '$KundenID'";
        mysqli_query($conn, $sql);
        $PKundenID = mysqli_insert_id($conn);
        $sql = "SELECT ... FROM Kontaktdaten WHERE PKundenID = '$PKundenID'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows == 1) {
            if ($result){
                echo("E-Mail-Adresse wurde bereits bestätigt!");
            }
            else
            {
                $sql = "UPDATE Kontaktdaten SET bestaetigt = true WHERE PKundenID = '$PKundenID'";
                echo("E-Mail-Adresse wurde erfolgreich bestätigt! Sie können sich nun einloggen.");
            }
        }
        else
        {
            echo("Es wurde kein passendes Kundenkonto gefunden! Bitte versuchen Sie es später erneut.");
        }
        } else
        {
            echo 'Es konnte kein passender User gefunden werde! Bitte versuchen Sie es später erneut.';
        }
} else {
    echo 'Kein Bestätigungstoken angegeben.';
}

?>
