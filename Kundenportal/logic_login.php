<?php
session_start();

function logMessage($logMessage, $logType = 'INFO') {
    $directory = 'C:\\Users\\Lenovo\\OneDrive - studmail.w-hs.de\\_Studium\\ProjektWinfo'; // Absolute path to the target directory
    $fileName = 'logfile.json';
    $logFile = $directory . DIRECTORY_SEPARATOR . $fileName;

    // Format the log message with a timestamp and type
    $formattedMessage = date('Y-m-d H:i:s') . " [$logType] - " . $logMessage . PHP_EOL;

    // Ensure the directory exists
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Write the log message to the file
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Example of logging an error message
logMessage('This is an error message from another PHP file.', 'ERROR');

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
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows == 1) {
        // Benutzer gefunden, überprüfen ob das Passwort übereinstimmt
        $row = $result->fetch_assoc();
        if (password_verify($passwort, $row['Passwort'])) {
            // Passwort stimmt überein, Benutzer ist erfolgreich angemeldet
            $_SESSION['benutzername'] = $benutzername;
            echo "<script>alert('Benutzer erfolgreich angemeldet');</script>";
            logMessage("Benutzer $benutzername ist angemeldet");
            // Weiterleitung zur Dashboard-Seite
            echo "<script>window.location.href = 'dashboard.php';</script>";
            exit();
        } else {
            // Passwort stimmt nicht überein
            echo "<script>alert('Falsches Passwort'); window.location.href = 'login.html';</script>";
            logMessage('Falsches Passwort', 'ERROR');
        }
    } else {
        // Benutzer nicht gefunden
        echo "<script>alert('Benutzer nicht gefunden'); window.location.href = 'login.html';</script>";
        logMessage('Benutzer nicht gefunden', 'ERROR');
    }
}

$conn->close();
?>
