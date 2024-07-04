<?php

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

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);
// Verbindung überprüfen
if ($conn->connect_error) {
    logMessage("Verbindung zur Datenbank kann nicht hergestellt werden");
} else {
    logMessage("Verbindung zur Datenbank wurde hergestellt und überprüft");
}

if (isset($_GET['benutzername'])) {
    $benutzername = $_GET['benutzername'];

    // KundenID des Users finden
    $sql = "SELECT KundenID FROM user WHERE benutzername = '$benutzername'";
    if (mysqli_query($conn, $sql)) {
        $result_KundenID = mysqli_query($conn, $sql);
        $row_KundenID = mysqli_fetch_assoc($result_KundenID);
        $KundenID = $row_KundenID['KundenID'];

        // PKundenID herausfinden
        $sql = "SELECT PKundenID FROM kunden WHERE KundenID = '$KundenID'";
        if (mysqli_query($conn, $sql)) {
            $result_PKundenID = mysqli_query($conn, $sql);
            $row_PKundenID = mysqli_fetch_assoc($result_PKundenID);
            $PKundenID = $row_PKundenID['PKundenID'];

            // Bestätigungswert herausfinden
            $sql = "SELECT Bestaetigung, Mail FROM kontaktdaten WHERE PKundenID = '$PKundenID'";
            $result = mysqli_query($conn, $sql);
            if ($result){
                $row = $result->fetch_assoc();
                if ($row['Bestaetigung'] == 1) {

                    echo("Ihre E-Mail-Adresse wurde bereits bestätigt!");
                    logMessage("E-Mail-Adresse " . $row['Mail'] . " wurde bereits bestätigt!");
                }
                else if($row['Bestaetigung'] == 0)
                {
                    $sql = "UPDATE Kontaktdaten SET Bestaetigung = '1' WHERE PKundenID = '$PKundenID'";
                    if (mysqli_query($conn, $sql)) {
                        logMessage("E-Mail-Adresse " . $row['Mail'] . " für user " . $benutzername . " wurde bestätigt!");
                        echo "<script>alert('E-Mail-Adresse wurde erfolgreich bestätigt! Sie können sich nun einloggen.'); window.location.href='login.html';</script>";
                        exit();
                    }else{
                        logMessage("Fehler beim Bestätigen der E-Mail-Adresse " . $row['Mail'] . " für user " . $benutzername . " (Update für Tabelle Kontaktdaten nicht erfolgreich!)");
                        echo("Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut!");
                    }
                }
                else{
                        logMessage("Fehler beim Bestätigen der E-Mail-Adresse für user " . $benutzername . " (Keinen korrekten Datensatz in der Datenbank gefunden!)");
                        echo("Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut!");
                    }
            } else
            {
                logMessage("Fehler beim Bestätigen der E-Mail-Adresse für user " . $benutzername . " (Ergebnis aus Tabelle kontaktdaten fehlerhaft)");
                echo("Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut!");
            }
        } else
        {
            logMessage("Fehler beim Bestätigen der E-Mail-Adresse für user " . $benutzername . " (Ergebnis aus Tabelle kunden fehlerhaft)");
            echo("Es wurde kein passendes Privat-Kundenkonto gefunden! Bitte versuchen Sie es später erneut.");
        }
    } else
    {
        logMessage("Fehler beim Bestätigen der E-Mail-Adresse für user " . $benutzername . " (Ergebnis aus Tabelle user fehlerhaft)");
        echo("Es wurde kein passendes Kundenkonto gefunden! Bitte versuchen Sie es später erneut.");
    }
} else {
    logMessage("Fehler beim Bestätigen der E-Mail-Adresse es wurde kein Token übergeben)");
    echo 'Kein Bestätigungstoken angegeben.';
}

?>
