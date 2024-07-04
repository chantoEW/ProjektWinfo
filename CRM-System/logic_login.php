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
        if (password_verify($passwort, $row['Passwort'])) { // Überprüfung auf "Passwort" statt "password"
            // Passwort stimmt überein, Benutzer ist erfolgreich angemeldet
            $_SESSION['benutzername'] = $benutzername;

            if($row['Berechtigungen'] == 'Marketing'|| $row['Berechtigungen'] == 'Admin') {
                header("Location: marketingStartseite.html");
            }
            else if($row['Berechtigungen'] == 'Verwaltung' || $row['Berechtigungen'] == 'Admin')
            {
                header("Location: verwaltungStartseite.html");
            }
            else {
                echo "<script>alert('Du besitzt keine Berechtigungen!'); window.location.href = 'index.html';</script>";
            }

        } else {
            // Passwort stimmt nicht überein
            echo "<script>alert('Falsches Passwort'); window.location.href = 'index.html';</script>";
        }
    } else {
        // Benutzer nicht gefunden
        echo "<script>alert('Benutzer nicht gefunden'); window.location.href = 'index.html';</script>";
    }
}

$conn->close();
?>