<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="styleCRM.css" rel="stylesheet">
    <title>CRM-System</title>
    <!--<style>
        html,
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            background-image: url('hintergrundbild.jpg');
            /* Hintergrundbild einfügen */
            background-size: cover;
            /* Bildgröße anpassen */
            background-repeat: no-repeat;
            /* Wiederholung des Bildes verhindern */
            background-attachment: fixed;
            /* Hintergrundbild fixieren */
            background-color: rgba(244, 244, 244, 0.2);
            /* Transparente Hintergrundfarbe */
            color: #333;
            overflow-x: hidden;
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

        nav .link-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
        }

        nav a:hover {
            background-color: #777;
        }

        #mainContent {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            width: 100%;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
        }

        .link {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
        }

        .link:hover {
            background-color: #777;
        }

        .input-field {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: calc(100% - 22px);
            /* Adjusted width for better spacing */
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
        }

        .input-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1em;
            /* Increased font size for emphasis */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
        }

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

        .container {
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            /* Weißer, leicht transparenter Hintergrund */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Vertikale Scrollleiste */
            /*max-height: calc(100vh - 200px); /* Maximale Höhe für Scrollbereich */
        }

        .content {
            padding: 20px;
            border-bottom: 1px solid #ccc;
        }

        .btn {
            background-color: #4CAF50;
            /* Grüne Hintergrundfarbe */
            border: none;
            /* Kein Rand */
            color: white;
            /* Weiße Schriftfarbe */
            padding: 10px 20px;
            /* Innenabstand */
            text-align: center;
            /* Zentrierte Ausrichtung */
            text-decoration: none;
            /* Keine Unterstreichung */
            display: inline-block;
            /* Element wird inline-block dargestellt */
            font-size: 16px;
            /* Schriftgröße */
            border-radius: 5px;
            /* Abgerundete Ecken */
            cursor: pointer;
            /* Zeiger ändern, um Benutzerinteraktion anzuzeigen */
            transition: background-color 0.3s ease;
            /* Weiche Übergänge für Hintergrundfarbe */
            display: flex;
        }

        .btn:hover {
            background-color: #45a049;
            /* Dunkleres Grün beim Hover */
        }
    </style>-->
</head>

<body>
    <header>
        <h1 id="pageTitle">Kundendaten ändern</h1>
    </header>
    <nav>
    <div class="link-container">
            <a href="#" class="link"><i class="bi bi-arrow-left"></i> Zurück</a>
            <a href="#" class="link" onclick="event.preventDefault();"><i class="bi bi-house"></i> Startseite</a>
            <a href="#" class="link"><i class="bi bi-pencil"></i> Kunde anlegen</a>
            <a href="#" class="link"><i class="bi bi-door-closed"></i> Abmelden</a>
            <div id="kundenIdDiv">
                <!-- Kunden-ID: <span id="kundenIdSpan"></span> -->
            </div>
        </div>
    </nav>
    <section id="mainContent">
        <div class="container">
            <nav>
                <a href="#" class="link" onclick="event.preventDefault(); switchTab('benutzerdaten')">Benutzerdaten</a>
                <a href="#" class="link" onclick="event.preventDefault(); switchTab('kontaktdaten')">Kontaktdaten</a>
                <a href="#" class="link" onclick="event.preventDefault(); switchTab('zahlungsdaten')">Zahlungsdaten</a>
                <a href="#" class="link" onclick="event.preventDefault(); switchTab('kennzahlen')">Kennzahlen</a>
            </nav>
            <div id="benutzerdaten" class="content">
                <div class="input-title">Benutzerdaten</div>
                <label><input type="radio" name="kundentyp" value="privat" onclick="toggleFirmaFeld()" checked disabled>
                    Privat</label>
                <label><input type="radio" name="kundentyp" value="geschaeft" onclick="toggleFirmaFeld()" disabled>
                    Geschäftskunde</label>
                <br><br>
                <label for="benutzername">Benutzername:</label><br>
                <input type="text" class="input-field" id="benutzername"><br>
                <label for="passwort">Passwort:</label><br>
                <input type="password" class="input-field" id="passwort"><br>
                <label for="name">Name:</label><br>
                <input type="text" class="input-field" id="name"><br>
                <label for="vorname">Vorname:</label><br>
                <input type="text" class="input-field" id="vorname"><br>
                <label for="geburtsdatum">Geburtsdatum:</label><br>
                <input type="date" class="input-field" id="geburtsdatum"><br>
                <div id="firmaContainer" style="display: none;">
                    <label for="firma">Firma:</label><br>
                    <input type="text" class="input-field" id="firma"><br>
                </div>
            </div>
            <div id="kontaktdaten" class="content" style="display:none;">
                <div class="input-title">Kontaktdaten</div>
                <label for="strasse">Straße:</label><br>
                <input type="text" class="input-field" id="strasse"><br>
                <label for="ort">Ort:</label><br>
                <input type="text" class="input-field" id="ort"><br>
                <label for="postleitzahl">Postleitzahl:</label><br>
                <input type="text" class="input-field" id="postleitzahl"><br>
                <label for="telefonnummer">Telefonnummer:</label><br>
                <input type="text" class="input-field" id="telefonnummer"><br>
                <label for="mailadresse">Mailadresse:</label><br>
                <input type="text" class="input-field" id="mailadresse"><br>
            </div>
            <div id="zahlungsdaten" class="content" style="display:none;">
                <div class="input-title">Zahlungsdaten</div>
                <label for="blz">BLZ:</label><br>
                <input type="text" class="input-field" id="blz"><br>
                <label for="institut">Institut:</label><br>
                <input type="text" class="input-field" id="institut"><br>
                <label for="iban">IBAN:</label><br>
                <input type="text" class="input-field" id="iban"><br>
                <label for="inhaber">Inhaber:</label><br>
                <input type="text" class="input-field" id="inhaber"><br>
            </div>
            <div id="kennzahlen" class="content" style="display:none;">
                <div class="input-title">Kennzahlen</div>
                <label for="bonitaetsklasse">Bonitätsklasse:</label><br>
                <input type="text" class="input-field" id="bonitaetsklasse"><br>
                <label for="abc_klassifikation">ABC-Klassifikation:</label><br>
                <input type="text" class="input-field" id="abc_klassifikation"><br>
            </div>
        </div>

    </section>

    <footer>
        <p>&copy; 2024 Autovermietung. Alle Rechte vorbehalten.</p>
    </footer>
    <script>



        function anzeigenKundenId(kundenId) {
            document.getElementById("kundenIdSpan").innerText = kundenId;
        }

        function switchTab(tabName) {
            var tabs = document.querySelectorAll('.content');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].style.display = "none";
            }
            document.getElementById(tabName).style.display = "block";
        }

        function toggleBearbeitungsmodus() {
            var inputs = document.querySelectorAll('#mainContent input');
            var speichernLink = document.getElementById("speichernLink");
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].disabled = !inputs[i].disabled;
            }
            speichernLink.style.display = speichernLink.style.display === "none" ? "block" : "none";
        }

        function speichereDaten() {
            alert("Daten wurden gespeichert!");
            toggleBearbeitungsmodus();
        }

        function toggleFirmaFeld() {
            var firmaContainer = document.getElementById("firmaContainer");
            var kundentyp = document.querySelector('input[name="kundentyp"]:checked').value;
            firmaContainer.style.display = kundentyp === "geschaeft" ? "block" : "none";
        }

        function getQueryParam(param) {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

    </script>
    <?php

    $filename = 'kundenID.txt';
    $jsonFilename = 'query_result.json';

    // Überprüfen, ob die Datei existiert
    if (file_exists($filename)) {
        // ID aus der Datei lesen
        $id = file_get_contents($filename);

        // Prüfen, ob die Datei erfolgreich gelesen wurde
        if ($id !== false) {
            // Debugging: Anzeigen der gelesenen ID
            error_log("Gelesene ID: " . $id);

            // Datei leeren
            $file = fopen($filename, 'w');
            if ($file !== false) {
                fclose($file);

                // Debugging: Bestätigung, dass die Datei geleert wurde
                error_log("Datei erfolgreich geleert: " . $filename);

                // Datenbankverbindung herstellen
                $servername = "localhost";
                $username = "projektwinfo";
                $password = "Ocm394Ldmc";
                $dbname = "portal";

                // Verbindung herstellen
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verbindung prüfen
                if ($conn->connect_error) {
                    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
                }

                // SQL-Abfrage
                $sql = "SELECT * 
                    FROM firmenkunde AS fk 
                    JOIN kontaktdaten AS kd
                    ON fk.FKundenID = kd.FKundenID
                    JOIN zahlungsinformationen AS zi
                    ON fk.FKundenID = zi.KundenID
                    JOIN user 
                    ON user.KundenID = fk.FKundenID
                    WHERE fk.FKundenID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Prüfen, ob ein Ergebnis vorliegt
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $data = ['success' => true, 'data' => $row];
                    //echo json_encode($data);
    
                    // Daten in query_result.json speichern
                    if (file_put_contents($jsonFilename, json_encode($data)) === false) {
                        error_log("Fehler beim Schreiben der JSON-Datei.");
                    } else {
                        error_log("JSON-Datei erfolgreich geschrieben: " . $jsonFilename);
                    }
                } else {
                    $error = ['success' => false, 'error' => 'Keine Daten gefunden'];
                    echo json_encode($error);

                    // Fehler in query_result.json speichern
                    if (file_put_contents($jsonFilename, json_encode($error)) === false) {
                        error_log("Fehler beim Schreiben der JSON-Datei.");
                    } else {
                        error_log("Fehler in JSON-Datei geschrieben: " . $jsonFilename);
                    }
                }

                $stmt->close();
                $conn->close();
            } else {
                $error = ['error' => 'Fehler beim Öffnen der Datei'];
                echo json_encode($error);
                error_log("Fehler beim Öffnen der Datei: " . $filename);

                // Fehler in query_result.json speichern
                if (file_put_contents($jsonFilename, json_encode($error)) === false) {
                    error_log("Fehler beim Schreiben der JSON-Datei.");
                } else {
                    error_log("Fehler in JSON-Datei geschrieben: " . $jsonFilename);
                }
            }
        } else {
            $error = ['error' => 'Fehler beim Lesen der Datei'];
            echo json_encode($error);
            error_log("Fehler beim Lesen der Datei: " . $filename);

            // Fehler in query_result.json speichern
            if (file_put_contents($jsonFilename, json_encode($error)) === false) {
                error_log("Fehler beim Schreiben der JSON-Datei.");
            } else {
                error_log("Fehler in JSON-Datei geschrieben: " . $jsonFilename);
            }
        }
    } else {
        $error = ['error' => 'Datei nicht gefunden'];
        echo json_encode($error);
        error_log("Datei nicht gefunden: " . $filename);

        // Fehler in query_result.json speichern
        if (file_put_contents($jsonFilename, json_encode($error)) === false) {
            error_log("Fehler beim Schreiben der JSON-Datei.");
        } else {
            error_log("Fehler in JSON-Datei geschrieben: " . $jsonFilename);
        }
    }
    ?>



    <script type="module">
        // Funktion zum Laden der JSON-Daten über eine HTTP-Anfrage
        async function loadData() {
            try {
                // HTTP-Anfrage an die JSON-Datei
                const response = await fetch('./query_result.json');

                // Überprüfen Sie den Status der Antwort
                if (!response.ok) {
                    throw new Error('Netzwerkantwort war nicht ok');
                }

                // Lesen Sie die Textantwort
                const responseText = await response.text();
                //console.log("Empfangene Antwort:", responseText);

                // JSON-Daten aus der Textantwort parsen
                const data = JSON.parse(responseText);
                //console.log("Name:", data.Name);

                // Auf das Attribut "Name" zugreifen und in der Konsole ausgeben
                document.getElementById('name').value = data.data.Name || '';
                document.getElementById('vorname').value = data.data.Vorname || '';
                document.getElementById('benutzername').value = data.data.Benutzername || '';
                document.getElementById('passwort').value = data.data.Passwort || '';
                document.getElementById('geburtsdatum').value = data.data.GebDatum || '';
                document.getElementById('strasse').value = data.data.Straße || '';
                document.getElementById('ort').value = data.data.Ort || '';
                document.getElementById('postleitzahl').value = data.data.PLZ || '';
                document.getElementById('telefonnummer').value = data.data.Telefonnummer || '';
                document.getElementById('mailadresse').value = data.data.Mail || '';
                document.getElementById('blz').value = data.data.BLZ || '';
                document.getElementById('institut').value = data.data.Institut || '';
                document.getElementById('iban').value = data.data.IBAN || '';
                document.getElementById('inhaber').value = data.data.Inhaber || '';
            } catch (error) {
                console.error("Fehler beim Laden der JSON-Daten:", error);
            }
        }

        // Funktion zum Ausführen, wenn das DOM vollständig geladen ist
        document.addEventListener('DOMContentLoaded', loadData);
    </script>

</body>

</html>
