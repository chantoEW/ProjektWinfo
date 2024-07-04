<?php
session_start();

function logMessage($message, $type = 'INFO') {
    $logFile = 'logfile.txt';
    $formattedMessage = date('Y-m-d H:i:s') . " - [$type] - " . $message . PHP_EOL;
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

    // Überprüfen, ob der Benutzer in der Privatkunden-Datenbank existiert
    $sql = "SELECT a.Benutzername, a.Passwort, c.Bestaetigung FROM user as a, kunden as b, kontaktdaten as c WHERE (a.KundenID = b. KundenID) AND ((b.FKundenID = c.FKundenID) OR (b.PKundenID = c.PKundenID)) AND (a.Benutzername = '$benutzername')";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

            // Benutzer gefunden, überprüfen ob das Passwort übereinstimmt
        if (password_verify($passwort, $row['Passwort'])) {
            //Übeprüfung, ob E-Mail-Adresse bestätigt wurde
            if ($row['Bestaetigung'] == 1)
            {
            // Passwort stimmt überein und E-Mail-Adresse wurde bestätigt, Benutzer ist erfolgreich angemeldet
            $_SESSION['benutzername'] = $benutzername;
            echo "<script>alert('Benutzer erfolgreich angemeldet');</script>";
            logMessage("Benutzer $benutzername ist angemeldet");
            // Weiterleitung zur Dashboard-Seite
            echo "<script>window.location.href = 'dashboard.php';</script>";
            exit();
            }
            else {
                echo "<script>alert('E-Mail-Adresse wurde nicht bestätigt. Bitte bestätigen Sie Ihre E-Mail-Adresse über den zugesendeten Link!');</script>";
                echo "<script>window.location.href = 'login.html';</script>";
            }
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
