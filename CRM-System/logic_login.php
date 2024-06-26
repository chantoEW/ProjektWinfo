<?php
session_start();

// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "Chantal";
$password = "";
$dbname = "portal";

$conn = new mysqli($servername, $username, $password, $dbname);


// Benutzereingaben überprüfen und Anmeldung durchführen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort']; // umbenannt in $passwort

    // Überprüfen, ob der Benutzer in der Datenbank existiert
    $sql = "SELECT Benutzername, Passwort, Berechtigungen FROM mitarbeiteruser as a, rollen as b WHERE Benutzername = '$benutzername' AND a.RolleniD = b.RollenID";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows == 1) {
        // Benutzer gefunden, überprüfen ob das Passwort übereinstimmt
        $row = $result->fetch_assoc();
        // Ausgabe des $row-Arrays zur Fehlerbehebung
        var_dump($row);
        if (password_verify($passwort, $row['Passwort'])) { // Überprüfung auf "Passwort" statt "password"
            // Passwort stimmt überein, Benutzer ist erfolgreich angemeldet
            $_SESSION['benutzername'] = $benutzername;

            if($row['Berechtigungen'] == 'Marketing') {
                header("Location: zugangAbteilungMarketing.html");
            }
            if($row['Berechtigungen'] == 'Verwaltung')
            {
                header("Location: verwaltungStartseite.html");
            }
        } else {
            // Passwort stimmt nicht überein
            echo "Falsches Passwort";
        }
    } else {
        // Benutzer nicht gefunden
        echo "Benutzer nicht gefunden";
    }
}

$conn->close();
?>