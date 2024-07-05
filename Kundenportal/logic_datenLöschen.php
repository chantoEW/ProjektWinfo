<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>CRM-System</title>
</head>

<body>
<header>
    <h1 id="pageTitle">Kundendaten ändern</h1>
</header>
<nav>
    <div class="link-container">
        <a href="dashboard.php" class="link"><i class="bi bi-house"></i> Startseite</a>
        <a href="login.html" class="link"><i class="bi bi-door-closed"></i> Abmelden</a>
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
            <button type="button" class="button" onclick="switchTab('kontaktdaten')">Weiter</button>
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
            <button type="button" class="button" onclick="switchTab('benutzerdaten')">Zurück</button>
            <button type="button" class="button" onclick="switchTab('zahlungsdaten')">Weiter</button>
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
            <button type="button" class="button" onclick="switchTab('kontaktdaten')">Zurück</button>
            <button type="button" class="button" onclick="switchTab('kennzahlen')">Weiter</button>
        </div>
        <div id="kennzahlen" class="content" style="display:none;">
            <div class="input-title">Kennzahlen</div>
            <label for="bonitaetsklasse">Bonitätsklasse:</label><br>
            <input type="text" class="input-field" id="bonitaetsklasse"><br>
            <label for="abc_klassifikation">ABC-Klassifikation:</label><br>
            <input type="text" class="input-field" id="abc_klassifikation"><br>
            <button type="button" class="button" onclick="switchTab('zahlungsdaten')">Zurück</button>
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

function logMessage($message, $type = 'INFO') {
    $logFile = 'logfile.txt';
    $formattedMessage = date('Y-m-d H:i:s') . " - [$type] - " . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

$filename = 'kundenID.txt';
$jsonFilename = 'query_result.json';

// Überprüfen, ob die Datei existiert
if (file_exists($filename)) {
    // ID aus der Datei lesen
    $id = file_get_contents($filename);

    // Prüfen, ob die Datei erfolgreich gelesen wurde
    if ($id !== false) {
        // Debugging: Anzeigen der gelesenen ID
        logMessage("[DatenLöschen] Datei für folgende ID konnte nicht gelesen werden: " . $id, "ERROR");

        // Datei leeren
        $file = fopen($filename, 'w');
        if ($file !== false) {
            fclose($file);

            // Debugging: Bestätigung, dass die Datei geleert wurde
            logMessage("[DatenLöschen] Datei erfolgreich geleert: " . $filename);

            // Datenbankverbindung herstellen
            $servername = "localhost";
            $username = "projektwinfo";
            $password = "Ocm394Ldmc";
            $dbname = "portal";

            // Verbindung herstellen
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verbindung prüfen
            if ($conn->connect_error) {
                logMessage("[DatenLöschen] Verbindung zur Datenbank kann nicht hergestellt werden" . $conn->connect_error, "ERROR");
            } else {
                logMessage("[DatenLöschen] Verbindung zur Datenbank wurde hergestellt und überprüft");
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
                    logMessage("[DatenLöschen] Fehler beim Schreiben der JSON-Datei für ID: " . $id, "ERROR");
                } else {
                    logMessage("[DatenLöschen] JSON-Datei erfolgreich geschrieben: " . $jsonFilename);
                }
            } else {
                $error = ['success' => false, 'error' => 'Keine Daten gefunden'];
                echo json_encode($error);

                // Fehler in query_result.json speichern
                if (file_put_contents($jsonFilename, json_encode($error)) === false) {
                    logMessage("[DatenLöschen] Fehler beim Schreiben der JSON-Datei für ID: " . $id, "ERROR");
                } else {
                    logMessage("[DatenLöschen] JSON-Datei erfolgreich geschrieben: " . $jsonFilename);
                }
            }

            $stmt->close();
            $conn->close();
        } else {
            $error = ['error' => 'Fehler beim Öffnen der Datei'];
            echo json_encode($error);
            logMessage("[DatenLöschen] Fehler beim Öffnen der Datei: " . $filename, "ERROR");

            // Fehler in query_result.json speichern
            if (file_put_contents($jsonFilename, json_encode($error)) === false) {
                logMessage("[DatenLöschen] Fehler beim Schreiben der JSON-Datei." , "ERROR");
            } else {
                logMessage("[DatenLöschen] Fehler in JSON-Datei geschrieben: " . $jsonFilename);
            }
        }
    } else {
        $error = ['error' => 'Fehler beim Lesen der Datei'];
        echo json_encode($error);
        logMessage("[DatenLöschen] Fehler beim Lesen der Datei: " . $filename, "ERROR");

        // Fehler in query_result.json speichern
        if (file_put_contents($jsonFilename, json_encode($error)) === false) {
            logMessage("[DatenLöschen] Fehler beim Schreiben der JSON-Datei.", "ERROR");
        } else {
            logMessage("[DatenLöschen] Fehler in JSON-Datei geschrieben: " . $jsonFilename, "ERROR");
        }
    }
} else {
    $error = ['error' => 'Datei nicht gefunden'];
    echo json_encode($error);
    logMessage("[DatenLöschen] Datei nicht gefunden: " . $filename, "ERROR");

    // Fehler in query_result.json speichern
    if (file_put_contents($jsonFilename, json_encode($error)) === false) {
        logMessage("[DatenLöschen] Fehler beim Schreiben der JSON-Datei.", "ERROR");
    } else {
        logMessage("[DatenLöschen] Fehler in JSON-Datei geschrieben: " . $jsonFilename, "ERROR");
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
