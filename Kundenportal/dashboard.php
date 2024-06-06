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

    // SQL-Abfrage, um den Vor- und Nachnamen des Benutzers abzurufen
    $sql = "SELECT Vorname, Nachname FROM privatkunde WHERE Benutzername = '$benutzername' 
            UNION 
            SELECT Vorname, Nachname FROM geschäftskunde WHERE Benutzername = '$benutzername'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Daten des Benutzers abrufen
        $row = $result->fetch_assoc();
        $vorname = $row["Vorname"];
        $nachname = $row["Nachname"];

        // Kundenname im Header anzeigen
        echo "<header>";
        echo "<h1>Herzlich willkommen $vorname $nachname!</h1>";
        echo "</header>";

        // Weitere Inhalte der Seite hier einfügen
    } else {
        echo "Benutzerdaten nicht gefunden";
    }

    // Verbindung schließen
    $conn->close();
} else {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header("Location: logic_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="wrapper">
<div class="link-container">
<nav><a href="login.html">Startseite</a></nav>
<nav><a href="login.html">Abmelden</a></nav>
</div>

<section id="mainContent" class="container"> <!-- Zentriert den Inhalt horizontal -->
    <div style="display: flex; flex-direction: column; align-items: center;"> <!-- Flex-Container für den Text und die Buttons -->
        <p style="margin-bottom: 20px;">Eingeloggt als: <?php echo $benutzername; ?></p>
        <div style="display: flex;"> <!-- Flex-Container für die Buttons -->
            <button onclick="window.location.href='datenEinsehen.php'" name="einsehen" class="button">Benutzerdaten einsehen</button>
            <button onclick="window.location.href='logic_datenÄndern.php'" name="ändern" class="button">Benutzerdaten ändern</button>
            <button onclick="window.location.href='datenLöschen.php'" name="löschen" class="button">Benutzeraccount löschen</button>
        </div>
    </div>
</section>

<footer>
   <p> &copy; 2024 Ihr Unternehmen. Alle Rechte vorbehalten.</p>
</footer>
</div>
</body>
</html>
