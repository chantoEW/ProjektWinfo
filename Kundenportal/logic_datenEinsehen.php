<?php
session_start(); // Session starten

if (isset($_SESSION['benutzername'])) {
    // Datenbankverbindung herstellen
    $servername = "localhost";
    $username = "Chantal";
    $password = "";
    $dbname = "portal";

    // Verbindung herstellen
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verbindung prüfen
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // ID aus der Datenbank abfragen (Abfrage basierend auf einem Benutzernamen)
    $benutzername = $_SESSION['benutzername'];
    $sqlForId = "SELECT KundenID FROM user WHERE Benutzername = ?";
    $stmtForId = $conn->prepare($sqlForId);
    if (!$stmtForId) {
        die("Vorbereitung fehlgeschlagen: " . $conn->error);
    }
    $stmtForId->bind_param("s", $benutzername);
    $stmtForId->execute();
    $resultForId = $stmtForId->get_result();

    if ($resultForId->num_rows > 0) {
        $rowForId = $resultForId->fetch_assoc();
        $id = $rowForId['KundenID'];
    } else {
        die("Keine ID gefunden für den angegebenen Benutzernamen.");
    }

    $stmtForId->close();

    // Überprüfen, ob die ID gefunden wurde
    if (!isset($id)) {
        die("Keine ID gefunden.");
    }

    //Kundentyp holen
    $sql = "SELECT FKunde_PKunde FROM kunden WHERE KundenID = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Vorbereitung fehlgeschlagen: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $kundentyp = $row["FKunde_PKunde"];


    if ($kundentyp == 'F') {

        $sql = "SELECT * 
        FROM kunden AS k
        JOIN firmenkunde AS fk
        ON k.FKundenID = fk.FKundenID
        JOIN zahlungsinformationen AS zi
        ON fk.ZahlungsID = zi.ZahlungsID
        JOIN user 
        ON user.KundenID = k.KundenID
        JOIN firma
        ON fk.FirmenID = firma.FirmenID
        JOIN kontaktdaten AS kd
        ON firma.KontaktID = kd.KontaktID
        JOIN kundenauswertung AS ka
        ON ka.KundenID = k.KundenID
        WHERE k.KundenID = ?";

    } else if ($kundentyp == 'P') {

        $sql = "SELECT * 
        FROM kunden AS k
        JOIN privatkunde AS pk
        ON pk.PKundenID = k.PKundenID
        JOIN kontaktdaten AS kd
        ON pk.KontaktID = kd.KontaktID
        JOIN zahlungsinformationen AS zi
        ON pk.ZahlungsID = zi.ZahlungsID
        JOIN user 
        ON user.KundenID = k.KundenID
        JOIN kundenauswertung AS ka
        ON ka.KundenID = k.KundenID
        WHERE k.KundenID = ?";
    }

    // SQL-Abfrage für Kundeninformationen
    /*$sql = "SELECT * 
            FROM kunden AS k
            LEFT JOIN firmenkunde AS fk ON k.FKundenID = fk.FKundenID
            LEFT JOIN privatkunde AS pk ON k.PKundenID = pk.PKundenID
            LEFT JOIN zahlungsinformationen AS zi ON (fk.ZahlungsID = zi.ZahlungsID OR pk.ZahlungsID = zi.ZahlungsID)
            LEFT JOIN user ON user.KundenID = k.KundenID
            LEFT JOIN firma ON fk.FirmenID = firma.FirmenID
            LEFT JOIN kontaktdaten AS kd ON (firma.KontaktID = kd.KontaktID OR pk.KontaktID = kd.KontaktID)
            LEFT JOIN kundenauswertung AS ka ON ka.KundenID = k.KundenID
            WHERE k.KundenID = ?";
    */
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Vorbereitung fehlgeschlagen: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prüfen, ob ein Ergebnis vorliegt
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $data = ['success' => true, 'data' => $row];
        $jsonFilename = 'query_result.json';
        if (file_put_contents($jsonFilename, json_encode($data)) === false) {
            error_log("Fehler beim Schreiben der JSON-Datei.");
        } else {
            error_log("JSON-Datei erfolgreich geschrieben: " . $jsonFilename);
        }
    } else {
        $error = ['success' => false, 'error' => 'Keine Daten gefunden'];
        $jsonFilename = 'query_result.json';
        if (file_put_contents($jsonFilename, json_encode($error)) === false) {
            error_log("Fehler beim Schreiben der JSON-Datei.");
        } else {
            error_log("Fehler in JSON-Datei geschrieben: " . $jsonFilename);
        }
    }
   

    $stmt->close();
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
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">-->
    <link href="../CRM-System/styleCRM.css" rel="stylesheet">
    <script>function switchTab(tabName) {
        var tabs = document.querySelectorAll('.content');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].style.display = "none";
        }
        document.getElementById(tabName).style.display = "block";
    }</script>
    <title>CRM-System</title>
</head>
<body>
<header>
    <h1 id="pageTitle">Kundendaten ändern</h1>
</header>
<nav>
    <div class="link-container">
        <a href="javascript:history.back()" class="link"><i class="bi bi-arrow-left"></i> Zurück</a>
        <a href="../Kundenportal/registrierung.html" class="link"><i class="bi bi-pencil"></i> Kunde anlegen</a>
        <a href="#" class="link"><i class="bi bi-door-closed"></i> Abmelden</a>
    </div>
</nav>
<section id="mainContent">
    <div class="container">
        <nav>
            <a href="#" class="link" onclick="event.preventDefault(); switchTab('benutzerdaten')">Benutzerdaten</a>
            <a href="#" class="link" onclick="event.preventDefault(); switchTab('kontaktdaten')">Kontaktdaten</a>
            <a href="#" class="link" onclick="event.preventDefault(); switchTab('zahlungsdaten')">Zahlungsdaten</a>
        </nav>
        <form  method="POST" action="logic_datenSpeichern.php">
            <div id="benutzerdaten" class="content">
                <div class="input-title">Benutzerdaten</div>
                <span id="kundenIdDiv"></span><br><br>
                <input type="text" name="kundenId" id="kundenIdFeld">
                <input type="radio" name="kundentyp" value="privat" onclick="toggleFirmaFeld()">
                <label>Privatkunde</label>
                <input type="radio" name="kundentyp" value="geschaeft" onclick="toggleFirmaFeld()">
                <label>Geschäftskunde</label>
                <br><br>
                <label for="benutzername">Benutzername:</label><br>
                <input type="text" class="input-field" id="benutzername" name="benutzername"><br>
                <label for="passwort">Passwort:</label><br>
                <input type="password" class="input-field" id="passwort" name="passwort"><br>
                <label for="name">Name:</label><br>
                <input type="text" class="input-field" id="name" name="name"><br>
                <label for="vorname">Vorname:</label><br>
                <input type="text" class="input-field" id="vorname" name="vorname"><br>
                <label for="geburtsdatum">Geburtsdatum:</label><br>
                <input type="date" class="input-field" id="geburtsdatum" name="geburtsdatum"><br>
                <div id="firmaContainer">
                    <label for="firma" id="firmenlabel">Firma:</label><br>
                    <input type="text" class="input-field" id="firma" name="firma"><br>
                </div>
            </div>
            <div id="kontaktdaten" class="content" style="display:none;">
                <div class="input-title">Kontaktdaten</div>
                <label for="strasse">Straße:</label><br>
                <input type="text" class="input-field" id="strasse" name="strasse"><br>
                <label for="ort">Ort:</label><br>
                <input type="text" class="input-field" id="ort" name="ort"><br>
                <label for="postleitzahl">Postleitzahl:</label><br>
                <input type="text" class="input-field" id="postleitzahl" name="postleitzahl"><br>
                <label for="telefonnummer">Telefonnummer:</label><br>
                <input type="text" class="input-field" id="telefonnummer" name="telefonnummer"><br>
                <label for="email">E-Mail:</label><br>
                <input type="email" class="input-field" id="email" name="email"><br>
            </div>
            <div id="zahlungsdaten" class="content" style="display:none;">
                    <div class="input-title">Zahlungsdaten</div>
                    <label for="blz">BLZ:</label><br>
                    <input type="text" class="input-field" id="blz" name="blz"><br>
                    <label for="institut">Institut:</label><br>
                    <input type="text" class="input-field" id="institut" name="institut"><br>
                    <label for="iban">IBAN:</label><br>
                    <input type="text" class="input-field" id="iban" name="iban"><br>
                    <label for="inhaber">Inhaber:</label><br>
                    <input type="text" class="input-field" id="inhaber" name="inhaber"><br>
                </div>
            <button type="submit">Änderungen speichern</button>
        </form>
    </div>
</section>

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
            document.getElementById('email').value = data.data.Mail || '';
            document.getElementById('blz').value = data.data.BLZ || '';
            document.getElementById('institut').value = data.data.Institut || '';
            document.getElementById('iban').value = data.data.IBAN || '';
            document.getElementById('inhaber').value = data.data.Inhaber || '';

            if (data.data.Firmenname != null) {
                document.getElementById('firma').value = data.data.Firmenname || '';
                document.getElementById('kundenIdDiv').innerHTML = "<b>Kunden-ID: " + data.data.KundenID + "</b>" || '';
                document.getElementsByName('kundenId')[0].value = data.data.KundenID || '';
                document.getElementsByName('kundentyp')[1].checked = true;
                //document.getElementsByName('kundentyp')[3].checked = true;
            }
            else {
                document.getElementById('kundenIdDiv').innerHTML = "<b>Kunden-ID: " + data.data.KundenID + "</b>" || '';
                document.getElementsByName('kundenId')[0].value = data.data.KundenID || '';
                document.getElementsByName('kundentyp')[0].checked = true;
                //document.getElementsByName('kundentyp')[2].checked = true;
                document.getElementById('firmaContainer').style.display = 'none';

            }
        } catch (error) {
            console.error("Fehler beim Laden der JSON-Daten:", error);
        }
    }

    // Funktion zum Ausführen, wenn das DOM vollständig geladen ist
    document.addEventListener('DOMContentLoaded', loadData);


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

</body>
</html>
