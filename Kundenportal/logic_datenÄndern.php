<?php
session_start();

// Überprüfe, ob der Benutzer angemeldet ist
if (isset($_SESSION['benutzername'])) {
// Verbindung zur Datenbank herstellen
    $servername = "localhost";
    $username = "Chantal";
    $password = "";
    $dbname = "portal";

    $conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfe die Verbindung
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

// Benutzername aus der Session abrufen
    $benutzername = $_SESSION['benutzername'];

// SQL-Abfrage vorbereiten, um den Kundentyp abzurufen
    $sql_kundentyp = "SELECT Kundentyp FROM privatkunde WHERE Benutzername='$benutzername' UNION SELECT Kundentyp FROM geschäftskunde WHERE Benutzername='$benutzername'";
    $result_kundentyp = $conn->query($sql_kundentyp);

    if ($result_kundentyp->num_rows > 0) {
        $row_kundentyp = $result_kundentyp->fetch_assoc();
        $kundentyp = $row_kundentyp['Kundentyp'];

        // SQL-Abfrage vorbereiten, abhängig vom Kundentyp
        if ($kundentyp == 'Privatkunde') {
            $sql = "SELECT Vorname, Nachname, Email, Geburtsdatum FROM privatkunde WHERE Benutzername='$benutzername'";
        } elseif ($kundentyp == 'Geschäftskunde') {
            $sql = "SELECT Vorname, Nachname, Email, Geburtsdatum FROM geschäftskunde WHERE Benutzername='$benutzername'";
        } else {
            echo "Ungültiger Kundentyp.";
            exit();
        }

        // SQL-Abfrage ausführen
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Daten abrufen und anzeigen
            $row = $result->fetch_assoc();
            $vorname = $row['Vorname'];
            $nachname = $row['Nachname'];
            $email = $row['Email'];
            $geburtsdatum = $row['Geburtsdatum'];
        } else {
            echo "Keine Daten gefunden.";
            exit();
        }
    } else {
        echo "Benutzer nicht gefunden.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datenänderung - Kundenportal Autovermietung</title>
    <style>
        /* Dein CSS-Stil hier */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column; /* Der Body-Inhalt wird in einer vertikalen Spalte angezeigt */
            align-items: center; /* Zentriere den Inhalt horizontal */
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 5px;
            text-align: center;
            position: page; /* Fixiere den Header oben auf der Seite */
            width: 100%; /* Setze die Breite auf 100% der Bildschirmbreite */
        }

        /* Stelle sicher, dass der Inhalt unterhalb des Headers angezeigt wird */
        section {
            padding: 20px;
            width: 90%; /* Setze die Breite des Abschnitts */
            margin-top: 5px; /* Setze den oberen Abstand, um Platz für den Header zu schaffen */
            display: flex; /* Flexbox auf das Abschnitts-Element anwenden */
            justify-content: center; /* Zentriere den Inhalt horizontal */
            align-items: center; /* Zentriere den Inhalt vertikal */
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

        footer {
            background-color: #333;
            color: #fff;
            padding: 5px;
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
            position: fixed; /* Positioniere es fest, um es über dem Inhalt zu legen */
            z-index: 1; /* Stelle sicher, dass es über dem Inhalt liegt */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Füge einen Scrollbalken hinzu, wenn der Inhalt zu groß ist */
            background-color: rgba(0, 0, 0, 0.4); /* Hintergrundfarbe mit Transparenz */
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

<header>
    <h2>Ändern Sie Ihre Daten im Kundenportal</h2>
</header>

<section>
    <form>
        <h2>Datenänderungsformular</h2>
        <input name="benutzername" type="text" class="input-field" placeholder="Benutzername"
               value="<?php echo $benutzername; ?>">
        <br>
        <input name="vorname" id="vorname" type="text" class="input-field" placeholder="Vorname"
               value="<?php echo $vorname; ?>">
        <br>
        <input name="nachname" id="nachname" type="text" class="input-field" placeholder="Nachname"
               value="<?php echo $nachname; ?>">
        <br>
        <!--<input name="passwort" type="password" class="input-field" placeholder="Passwort">
        <br>
        <input name="passwort_wiederholen" type="password" class="input-field" placeholder="Passwort wiederholen">
        <br>
        <span id="passwort_fehlermeldung" style="color: red; display: none;">Die eingegebenen Passwörter stimmen nicht überein. Bitte überprüfen Sie Ihre Eingaben.</span>
        <span id="passwort_uebereinstimmung" style="color: green; display: none;">Die Passwörter stimmen überein.</span>
        <br>-->
        <input name="email" type="email" class="input-field" placeholder="E-Mail-Adresse"
               value="<?php echo $email; ?>">
        <br>
        <input name="geburtsdatum" id="Gebursdatum" type="date" class="input-field" placeholder="Geburtsdatum"
               value="<?php echo $geburtsdatum; ?>">
        <br>
        <input type="radio" class="input-field" id="privatkunde" name="kundentyp" value="Privatkunde" checked>
        <label for="privatkunde">Privatkunde</label>
        <br>
        <input type="radio" class="input-field" id="geschaeftskunde" name="kundentyp" value="Geschäftskunde">
        <label for="geschaeftskunde">Geschäftskunde</label>
        <br>
        <input type="text" name="firmenname" id="firmenname" class="input-field" placeholder="Firmenname"
               style="display: none;">
        <br>
        <button type="submit" class="button">Daten ändern!</button>
    </form>
</section>

<footer>
    <p>&copy; 2024 Autovermietung. Alle Rechte vorbehalten.</p>
</footer>

<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        var passwort = document.querySelector('input[name="passwort"]').value;
        var passwortWiederholen = document.querySelector('input[name="passwort_wiederholen"]').value;

        // Überprüfe, ob die Passwörter übereinstimmen
        if (passwort !== passwortWiederholen) {
            // Passwörter stimmen nicht überein, zeige die Fehlermeldung an
            document.getElementById('passwort_fehlermeldung').style.display = 'inline';
            document.getElementById('passwort_uebereinstimmung').style.display = 'none';
            // Verhindere das Absenden des Formulars
            event.preventDefault();
        } else {
            // Passwörter stimmen überein, zeige die positive Nachricht an
            document.getElementById('passwort_fehlermeldung').style.display = 'none';
            document.getElementById('passwort_uebereinstimmung').style.display = 'inline';
        }
    });

    document.querySelectorAll('input[name="kundentyp"]').forEach(function (radioButton) {
        radioButton.addEventListener('change', function () {
            if (this.value === 'Geschäftskunde') {
                document.getElementById('firmenname').style.display = 'inline';
            } else {
                document.getElementById('firmenname').style.display = 'none';
            }
        });
    });

    document.getElementById('datenänderungsformular').addEventListener('submit', function (event) {
        var email = document.getElementById('email').value;
        var emailRegex = /^[^\s@]+@[^\s@]+\.(?:com|de)$/;

        if (!emailRegex.test(email)) {
            alert('Bitte geben Sie eine gültige E-Mail-Adresse im Format "Text@Text.de" oder "Text@Text.com" ein.');
            event.preventDefault(); // Verhindere das Absenden des Formulars
        }
    });
</script>

</body>
</html>
