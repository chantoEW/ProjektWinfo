<?php
// Datenbankverbindung herstellen
$servername = "localhost";
$username = "projektwinfo";
$password = "Ocm394Ldmc";
$dbname = "portal";

// Verbindung erstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

if (
    isset($_POST['benutzername']) &&
    isset($_POST['passwort']) &&
    isset($_POST['name']) &&
    isset($_POST['vorname']) &&
    isset($_POST['geburtsdatum']) &&
    isset($_POST['strasse']) &&
    isset($_POST['ort']) &&
    isset($_POST['postleitzahl']) &&
    isset($_POST['telefonnummer']) &&
    isset($_POST['mailadresse']) &&
    isset($_POST['blz']) &&
    isset($_POST['institut']) &&
    isset($_POST['iban']) &&
    isset($_POST['inhaber']) &&
    isset($_POST['bonitaetsklasse']) &&
    isset($_POST['abc_klassifikation'])
) {

    // POST-Daten empfangen
    $kundentyp = $_POST['kundentyp'];
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort'];
    $name = $_POST['name'];
    $vorname = $_POST['vorname'];
    $geburtsdatum = $_POST['geburtsdatum'];
    $firma = $_POST['firma'];
    $strasse = $_POST['strasse'];
    $ort = $_POST['ort'];
    $postleitzahl = $_POST['postleitzahl'];
    $telefonnummer = $_POST['telefonnummer'];
    $mailadresse = $_POST['mailadresse'];
    $blz = $_POST['blz'];
    $institut = $_POST['institut'];
    $iban = $_POST['iban'];
    $inhaber = $_POST['inhaber'];
    $bonitaetsklasse = $_POST['bonitaetsklasse'];
    $abc_klassifikation = $_POST['abc_klassifikation'];
    $kundenId = $_POST['kundenId'];
    // Vorherige Daten aus der Datenbank abrufen
    $sql = '';
    if ($kundentyp == 'geschaeft') {
        $sql = "SELECT * 
    FROM firmenkunde AS fk
    JOIN kontaktdaten AS kd
    ON fk.FKundenID = kd.FKundenID
    JOIN zahlungsinformationen AS zi
    ON fk.FKundenID = zi.KundenID
    JOIN user 
    ON user.KundenID = fk.FKundenID
    JOIN firma
    ON fk.FKundenID = firma.kundenID
    WHERE fk.FKundenID = ?";

    } else if ($kundentyp == 'privat') {
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


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kundenId);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingData = $result->fetch_assoc();

    if ($existingData) {
        // Überprüfen, ob sich die Daten geändert haben
        $updates = [];
        $params = [];
        $types = "";

        if ($existingData["Benutzername"] != $benutzername) {
            $updates[] = "Benutzername = ?";
            $params[] = $benutzername;
            $types .= "s";
        }
        if ($existingData['Passwort'] != $passwort) {
            $updates[] = "Passwort = ?";
            $params[] = $passwort;
            $types .= "s";
        }
        if ($existingData['Name'] != $name) {
            $updates[] = "Name = ?";
            $params[] = $name;
            $types .= "s";
        }
        if ($existingData['Vorname'] != $vorname) {
            $updates[] = "Vorname = ?";
            $params[] = $vorname;
            $types .= "s";
        }
        if ($existingData['GebDatum'] != $geburtsdatum) {
            $updates[] = "GebDatum = ?";
            $params[] = $geburtsdatum;
            $types .= "s";
        }

        if ($kundentyp == 'geschaeft') {
            if ($existingData['Firmenname'] != $firma) {
                $updates[] = "Firmenname = ?";
                $params[] = $firma;
                $types .= "s";
            }
        }
        if ($existingData['Strasse'] != $strasse) {
            $updates[] = "Strasse = ?";
            $params[] = $strasse;
            $types .= "s";
        }
        if ($existingData['Ort'] != $ort) {
            $updates[] = "Ort = ?";
            $params[] = $ort;
            $types .= "s";
        }
        if ($existingData['PLZ'] != $postleitzahl) {
            $updates[] = "PLZ = ?";
            $params[] = $postleitzahl;
            $types .= "s";
        }
        if ($existingData['Telefonnummer'] != $telefonnummer) {
            $updates[] = "Telefonnummer = ?";
            $params[] = $telefonnummer;
            $types .= "s";
        }
        if ($existingData['Mail'] != $mailadresse) {
            $updates[] = "Mail = ?";
            $params[] = $mailadresse;
            $types .= "s";
        }
        if ($existingData['BLZ'] != $blz) {
            $updates[] = "BLZ = ?";
            $params[] = $blz;
            $types .= "s";
        }
        if ($existingData['Institut'] != $institut) {
            $updates[] = "Institut = ?";
            $params[] = $institut;
            $types .= "s";
        }
        if ($existingData['IBAN'] != $iban) {
            $updates[] = "IBAN = ?";
            $params[] = $iban;
            $types .= "s";
        }
        if ($existingData['Inhaber'] != $inhaber) {
            $updates[] = "Inhaber = ?";
            $params[] = $inhaber;
            $types .= "s";
        }
        /*if ($existingData['bonitaetsklasse'] != $bonitaetsklasse) {
            $updates[] = "bonitaetsklasse = ?";
            $params[] = $bonitaetsklasse;
            $types .= "s";
        }
        if ($existingData['abc_klassifikation'] != $abc_klassifikation) {
            $updates[] = "abc_klassifikation = ?";
            $params[] = $abc_klassifikation;
            $types .= "s";
        }*/



        if (count($updates) > 0) {
            $params[] = $kundenId;
            $types .= "i";
            if ($kundentyp == 'geschaeft') {
                $sql = "UPDATE firmenkunde AS fk
            JOIN kontaktdaten AS kd ON fk.FKundenID = kd.FKundenID
            JOIN zahlungsinformationen AS zi ON fk.FKundenID = zi.KundenID
            JOIN user ON user.KundenID = fk.FKundenID
            JOIN firma ON fk.FKundenID = firma.kundenID
            SET " . implode(", ", $updates) . " 
            WHERE fk.FKundenID = ?";
            } else if ($kundentyp == 'privat') {
                $sql = "UPDATE privatkunde AS pk
            JOIN kontaktdaten AS kd ON pk.PKundenID = kd.PKundenID
            JOIN zahlungsinformationen AS zi ON pk.PKundenID = zi.KundenID
            JOIN user ON user.KundenID = pk.PKundenID
            SET " . implode(", ", $updates) . " 
            WHERE pk.PKundenID = ?";
            }
            $stmt = $conn->prepare($sql);
            // Parametertypen und -werte binden
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                echo "Die Daten wurden erfolgreich aktualisiert.";
            } else {
                echo "Fehler beim Aktualisieren der Daten: " . $stmt->error;
            }
        } else {
            echo "Keine Änderungen gefunden.";
        }
    } else {
        echo "Kunde nicht gefunden.";
    }
}
// Verbindung schließen
$conn->close();
?>