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
            background-image: url('Auto_.jpg'); /* Pfad zum Hintergrundbild */
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
            display: inline-block;
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
<body>
<h1>Willkommen im Dashboard!</h1>
<?php
// Überprüfen, ob der Benutzer angemeldet ist
session_start();
if (isset($_SESSION['benutzername'])) {
    $benutzername = $_SESSION['benutzername'];
    echo "<p>Eingeloggt als: $benutzername</p>";
    // Hier kannst du weitere Inhalte für das Dashboard hinzufügen
} else {
    // Benutzer ist nicht angemeldet, Weiterleitung zur Anmeldeseite
    header("Location: login.php");
    exit();
}
?>
<a href="logout.php">Abmelden</a>
</body>
</html>
