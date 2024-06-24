<?php
$jsonFilePath = 'query_result.json';

// JSON-Datei einlesen
$jsonString = file_get_contents($jsonFilePath);

// Überprüfen, ob das Einlesen erfolgreich war
if ($jsonString === false) {
    die("Fehler beim Einlesen der JSON-Datei.");
}

// JSON-Daten dekodieren
$jsonData = json_decode($jsonString, true);

// Überprüfen, ob das Dekodieren erfolgreich war
if ($jsonData === null) {
    die("Fehler beim Dekodieren der JSON-Daten.");
}

// Wert von FKunde_PKunde extrahieren
$kundentyp = '';
if (isset($jsonData['data']['FKunde_PKunde'])) {
    $kundentyp = $jsonData['data']['FKunde_PKunde'];
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="styleCRM.css" rel="stylesheet">
    <title>CRM-System</title>
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

                <span id="kundenIdDiv"></span><br><br>
                <label><input type="radio" name="kundentyp" value="privat" onclick="toggleFirmaFeld()" <?php if ($kundentyp === 'P')
                    echo 'checked'; ?> disabled>
                    Privat</label>
                <label><input type="radio" name="kundentyp" value="geschaeft" onclick="toggleFirmaFeld()" <?php if ($kundentyp === 'F')
                    echo 'checked'; ?> disabled>
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


                $jsonFilePath = 'query_result.json';

                // JSON-Datei einlesen
                $jsonString = file_get_contents($jsonFilePath);

                // Überprüfen, ob das Einlesen erfolgreich war
                if ($jsonString === false) {
                    die("Fehler beim Einlesen der JSON-Datei.");
                }

                // JSON-Daten dekodieren
                $jsonData = json_decode($jsonString, true);

                // Überprüfen, ob das Dekodieren erfolgreich war
                if ($jsonData === null) {
                    die("Fehler beim Dekodieren der JSON-Daten.");
                }

                // Wert von FKunde_PKunde extrahieren
                $kundentyp = '';
                if (isset($jsonData['data']['FKunde_PKunde'])) {
                    $kundentyp = $jsonData['data']['FKunde_PKunde'];
                }

                if($kundentyp == 'F') {
                    $sql = "SELECT * 
                    FROM firmenkunde AS fk
                    JOIN kontaktdaten AS kd
                    ON fk.FKundenID = kd.FKundenID
                    JOIN zahlungsinformationen AS zi
                    ON fk.FKundenID = zi.KundenID
                    JOIN user 
                    ON user.KundenID = fk.FKundenID
                    WHERE fk.FKundenID = ?";

                }
                else if($kundentyp == 'P')
                {
                    $sql = "SELECT * 
                    FROM privatkunde AS pk
                    JOIN kontaktdaten AS kd
                    ON pk.PKundenID = kd.PKundenID
                    JOIN zahlungsinformationen AS zi
                    ON pk.PKundenID = zi.KundenID
                    JOIN user 
                    ON user.KundenID = pk.PKundenID
                    WHERE pk.PKundenID = ?";
                }

                // SQL-Abfrage
                
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
                document.getElementById('strasse').value = data.data.Strasse || '';
                document.getElementById('ort').value = data.data.Ort || '';
                document.getElementById('postleitzahl').value = data.data.PLZ || '';
                document.getElementById('telefonnummer').value = data.data.Telefonnummer || '';
                document.getElementById('mailadresse').value = data.data.Mail || '';
                document.getElementById('blz').value = data.data.BLZ || '';
                document.getElementById('institut').value = data.data.Institut || '';
                document.getElementById('iban').value = data.data.IBAN || '';
                document.getElementById('inhaber').value = data.data.Inhaber || '';

                document.getElementById('kundenIdDiv').innerHTML = "<b>Kunden-ID: " + data.data.FKundenID + "</b>" || '';
            } catch (error) {
                console.error("Fehler beim Laden der JSON-Daten:", error);
            }
        }

        // Funktion zum Ausführen, wenn das DOM vollständig geladen ist
        document.addEventListener('DOMContentLoaded', loadData);
    </script>

</body>

</html>