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
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #555;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }
        nav a:hover {
            background-color: #777;
        }
        section {
            padding: 140px;
            position: relative; /* Setze Position auf relativ für die Verwendung von Hintergrundbild */
            /*background-image: url('Auto_.jpg'); /* Pfad zum Hintergrundbild */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
        .input-field {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .modal {
            display: none; /* Verstecke das Modal standardmäßig */
            position: fixed; /* Positioniere es fest, um es über den Inhalt zu legen */
            z-index: 1; /* Stelle sicher, dass es über dem Inhalt liegt */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Füge einen Scrollbalken hinzu, wenn der Inhalt zu groß ist */
            background-color: rgba(0,0,0,0.4); /* Hintergrundfarbe mit Transparenz */
            padding-top: 60px; /* Abstand vom oberen Rand */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* Zentriere das Modal vertikal und horizontal */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Setze die Breite des Modals */
            border-radius: 5px;
        }

        /* Schließe das Modal, wenn der Benutzer auf das Schließen-Symbol klickt */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<header style="display: flex; justify-content: center;"> <!-- Flexbox-Container für die Navigation -->
    <nav style="margin-right: 10px;"><a href="index.html">Startseite</a></nav>
    <nav><a href="index.html">Abmelden</a></nav>
</header>
<body>
<section style="text-align: center;"> <!-- Zentriert den Inhalt horizontal -->
    <div style="display: flex; flex-direction: column; align-items: center;"> <!-- Flex-Container für den Text und die Buttons -->
        <p style="margin-bottom: 20px;">Eingeloggt als: <?php echo $benutzername; ?></p>
        <div style="display: flex;"> <!-- Flex-Container für die Buttons -->
            <button onclick="window.location.href='datenÄndern.php'" name="ändern" class="button">Benutzerdaten ändern</button>
            <button name="löschen" class="button">Benutzeraccount löschen</button>
        </div>
    </div>
</section>

<footer>
    &copy; 2024 Ihr Unternehmen. Alle Rechte vorbehalten.
</footer>
</body>
</html>
