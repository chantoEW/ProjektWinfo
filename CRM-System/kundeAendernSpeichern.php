<?php
// Datenbankverbindung herstellen
$servername = "localhost";
$username = "Chantal";
$password = "";
$dbname = "portal";

// Verbindung erstellen
$conn = new mysqli($servername, $username, $password, $dbname);
function logMessage($message)
{
    $logFile = 'logfile.json';
    $formattedMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}
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

    } else if ($kundentyp == 'privat') {
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
        if ($existingData['Bonitätsklasse'] != $bonitaetsklasse) {
            $updates[] = "Bonitätsklasse = ?";
            $params[] = $bonitaetsklasse;
            $types .= "s";
        }
        if ($existingData['ABC_Klasse'] != $abc_klassifikation) {
            $updates[] = "ABC_Klasse = ?";
            $params[] = $abc_klassifikation;
            $types .= "s";
        }

      

        if (count($updates) > 0) {
            $params[] = $kundenId;
            $types .= "i";
            if ($kundentyp == 'geschaeft') {
                $sql = "UPDATE kunden AS k
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
            SET " . implode(", ", $updates) . " 
            WHERE fk.FKundenID = ?";
            } else if ($kundentyp == 'privat') {
                $sql = "UPDATE kunden AS k
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
            SET " . implode(", ", $updates) . " 
            WHERE pk.PKundenID = ?";
            }
            $stmt = $conn->prepare($sql);
            // Parametertypen und -werte binden
            $stmt->bind_param($types, ...$params);
            $changes = "";
            if ($stmt->execute()) {
                echo "Die Daten wurden erfolgreich aktualisiert.";
                for( $i = 0; $i < count($updates); $i++ ) {
                    
                    $changes = $changes . explode(' ', $updates[$i])[0] . ', ';
                }
                $changes = rtrim($changes, ', ');
                logMessage("Folgende Daten für Kunde mit der ID $kundenId wurden aktualisiert: $changes");
            }
            else {
                echo "Fehler beim Aktualisieren der Daten: " . $stmt->error;
                logMessage("Fehler beim Aktualisieren für Kunde mit der ID $kundenId: " . $stmt->error);
            }
        } else {
            echo "Keine Änderungen gefunden.";
            logMessage("Keine Änderungen der Kundendaten gefunden.");
        }
    } else {
        echo "Kunde nicht gefunden.";
        logMessage("Der Kunde mit der ID $kundenId wurde nicht gefunden.");
    }
}
// Verbindung schließen
$conn->close();
?>