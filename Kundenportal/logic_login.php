<?php
session_start();

function logMessage($message) {
    $logFile = 'logfile.json';
    $formattedMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "Chantal";
$password = "";
$dbname = "portal";

$conn = new mysqli($servername, $username, $password, $dbname);
logMessage("DB Verbindung hergestellt");


// Benutzereingaben überprüfen und Anmeldung durchführen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort']; // umbenannt in $passwort

    // Überprüfen, ob der Benutzer in der Datenbank existiert
    $sql = "SELECT Benutzername, Passwort FROM user WHERE Benutzername = '$benutzername'";
    $result = mysqli_query($conn, $sql);;

    if ($result->num_rows == 1) {
        // Benutzer gefunden, überprüfen ob das Passwort übereinstimmt
        $row = $result->fetch_assoc();
        // Ausgabe des $row-Arrays zur Fehlerbehebung
        var_dump($row);
        if (password_verify($passwort, $row['Passwort'])) { // Überprüfung auf "Passwort" statt "password"
            // Passwort stimmt überein, Benutzer ist erfolgreich angemeldet
            $_SESSION['benutzername'] = $benutzername;
            echo "Benutzer erfolgreich angemeldet";
            logMessage("Benutzer $benutzername ist angemeldet");
            // Weiterleitung zur Dashboard-Seite
            header("Location: dashboard.php");
            exit();
        } else {
            // Passwort stimmt nicht überein
            echo "Falsches Passwort";
            logMessage("Falsches Passwort");
        }
    } else {
        // Benutzer nicht gefunden
        echo "Benutzer nicht gefunden";
        logMessage("Benutzer nicht gefunden");
    }
}

$conn->close();
?>