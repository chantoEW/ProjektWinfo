<?php
session_start();
function logMessage($message, $type = 'INFO') {
    $logFile = 'logfile.txt';
    $formattedMessage = date('Y-m-d H:i:s') . " - [$type] - " . $message . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Verbindung zur Datenbank herstellen
    $servername = "localhost";
    $username = "Chantal";
    $password = "";
    $dbname = "portal";

// Verbindung herstellen
    $conn = new mysqli($servername, $username, $password, $dbname);
// Verbindung überprüfen
    if ($conn->connect_error) {
        logMessage("[Registrierung] Verbindung zur Datenbank kann nicht hergestellt werden" . $conn->connect_error, "ERROR");
    } else {
        logMessage("[Registrierung] Verbindung zur Datenbank wurde hergestellt und überprüft");
    }

// Daten aus dem Formular erhalten
    $benutzername = $_POST['benutzername'];
    $_SESSION['benutzername'] = $benutzername;
    $nachname = $_POST['nachname'];
    $_SESSION['nachname'] = $nachname;
    $vorname = $_POST['vorname'];
    $_SESSION['vorname'] = $vorname;
    $rohGeburtsdatum = $_POST['geburtsdatum'];
    // Datum in das richtige Format konvertieren
    $geburtsdatum = date("Y-m-d", strtotime($rohGeburtsdatum));
    $passwort = $_POST['passwort'];
    // Passwort hashen
    $hashed_password = password_hash($passwort, PASSWORD_DEFAULT);
    $strasse = $_POST['strasse'];
    //$hausnummer = $_POST['hausnummer'];
    $ort = $_POST['ort'];
    $plz = $_POST['postleitzahl'];
    $telefonnummer = $_POST['telefonnummer'];
    $email = $_POST['email'];
    $blz = $_POST['blz'];
    $institut = $_POST['institut'];
    $iban = $_POST['iban'];
    $inhaber = $_POST['inhaber'];
    logMessage("[Registrierung] Alle Daten aus dem Formular erhalten und in Variablen gespeichert");

    // Überprüfe, ob der Benutzername bereits existiert
    $sql = "SELECT * FROM user WHERE Benutzername = '$benutzername'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        // Benutzername existiert bereits, zeige eine Nachricht an und leite dann weiter
        echo "<script>alert('Der Benutzername existiert bereits. Bitte wählen Sie einen anderen Benutzernamen.'); window.location.href='registrierung.html';</script>";
        exit();
    }

    //Eintrittsdatum für Datensatz bereitstellen:
    $eintrittsdatum = date("Y-m-d");

    // Überprüfe, welcher Kundentyp ausgewählt wurde
    if (isset($_POST['kundentyp'])) {
        $kundentyp = $_POST['kundentyp'];

        // Hier kannst du entsprechende Aktionen basierend auf dem Kundentyp durchführen
        if ($kundentyp == 'Privatkunde') {
            // SQL-Query zum Einfügen der Daten in die entsprechende Tabelle

            $sql = "INSERT INTO privatkunde (Name, Vorname, GebDatum, Eintrittsdatum) VALUES ('$nachname', '$vorname', '$geburtsdatum', '$eintrittsdatum')";
            if (mysqli_query($conn, $sql)) {
                $PKundenID = mysqli_insert_id($conn);

                $sql = "INSERT INTO kunden (PKundenID, FKunde_PKunde) VALUES ('$PKundenID', 'P')";
                if (mysqli_query($conn, $sql)) {
                    $KundenID = mysqli_insert_id($conn);

                    $sql = "INSERT INTO kontaktdaten (Strasse, Ort, PLZ, Telefonnummer, Mail, PKundenID, FKunde_PKunde) VALUES ('$strasse', '$ort', '$plz', '$telefonnummer', '$email', '$PKundenID', 'P')";
                    if (mysqli_query($conn, $sql)) {
                        $KontaktID = mysqli_insert_id($conn);

                        $sql = "INSERT INTO zahlungsinformationen (BLZ, Institut, IBAN, Inhaber, KundenID) VALUES ('$blz', '$institut', '$iban', '$inhaber', '$KundenID')";
                        if (mysqli_query($conn, $sql)) {
                            $ZahlungsID = mysqli_insert_id($conn);

                            $sql = "UPDATE privatkunde SET KontaktID='$KontaktID', ZahlungsID='$ZahlungsID', KundenID='$KundenID' WHERE PKundenID='$PKundenID'";
                            if (mysqli_query($conn, $sql)) {
                                $sql = "INSERT INTO user (Benutzername, Passwort, KundenID) VALUES ('$benutzername', '$hashed_password', '$KundenID')";
                                if (mysqli_query($conn, $sql)) {
                                    $sql = "INSERT INTO kundenauswertung (Bonitaetsklasse, ABC_Klasse, KundenID) VALUES ('BBB', 'C', '$KundenID')";
                                    if (mysqli_query($conn, $sql)) {
                                        logmessage("[Registrierung] Datensatz für neuen Privatkunden mit der KundenID " . $KundenID . " erfolgreich erzeugt.");
                                        include 'mail_registrierung_privatkunde.php';
                                    } else {
                                        logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle kundenauswertung: " . mysqli_error($conn), "ERROR");}
                                } else {
                                    logmessage("[Registrierung] Fehler beim Einfügen der Log-In-Daten: " . mysqli_error($conn), "ERROR");
                                }
                            } else {
                                logmessage("[Registrierung] Fehler beim Aktualisieren der Privatkunden-Daten: " . mysqli_error($conn), "ERROR");
                            }
                        } else {
                            logmessage("[Registrierung] Fehler beim Einfügen der Zahlungsinformationen: " . mysqli_error($conn), "ERROR");
                        }
                    } else {
                        logmessage("[Registrierung] Fehler beim Einfügen der Kontaktdaten: " . mysqli_error($conn), "ERROR");
                    }
                } else {
                    logmessage("[Registrierung] Fehler beim Einfügen der Kunden-Daten: " . mysqli_error($conn), "ERROR");
                }
            } else {
                logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle privatkunde: " . mysqli_error($conn), "ERROR");
            }
        } elseif ($kundentyp == 'Geschäftskunde') {
            $firmenname = $_POST['firmenname'];
            $_SESSION['firmenname'] = $firmenname;
            // SQL-Query zum Einfügen der Daten in die entsprechende Tabelle
            $sql = "INSERT INTO firmenkunde (Name, Vorname, GebDatum, Eintrittsdatum) VALUES ('$nachname', '$vorname', '$geburtsdatum', '$eintrittsdatum')";
            if (mysqli_query($conn, $sql)) {
                $FKundenID = mysqli_insert_id($conn);

                $sql = "INSERT INTO kunden (FKundenID, FKunde_PKunde) VALUES ('$FKundenID', 'F')";
                if (mysqli_query($conn, $sql)) {
                    $KundenID = mysqli_insert_id($conn);

                    $sql = "INSERT INTO kontaktdaten (Strasse, Ort, PLZ, Telefonnummer, Mail, FKundenID, FKunde_PKunde) VALUES ('$strasse', '$ort', '$plz', '$telefonnummer', '$email', '$FKundenID', 'F')";
                    if (mysqli_query($conn, $sql)) {
                        $KontaktID = mysqli_insert_id($conn);

                        $sql = "INSERT INTO zahlungsinformationen (BLZ, Institut, IBAN, Inhaber, KundenID) VALUES ('$blz', '$institut', '$iban', '$inhaber', '$KundenID')";
                        if (mysqli_query($conn, $sql)) {
                            $ZahlungsID = mysqli_insert_id($conn);

                            $sql = "INSERT INTO firma (KontaktID, KundenID, Firmenname) VALUES ('$KontaktID', '$KundenID', '$firmenname')";
                            mysqli_query($conn, $sql);
                            $FirmenID = mysqli_insert_id($conn);

                            if (mysqli_query($conn, $sql)) {
                                $sql = "UPDATE firmenkunde SET ZahlungsID='$ZahlungsID', KundenID='$KundenID', FirmenID='$FirmenID' WHERE FKundenID='$FKundenID'";
                                if (mysqli_query($conn, $sql)) {
                                    $sql = "INSERT INTO user (Benutzername, Passwort, KundenID) VALUES ('$benutzername', '$hashed_password', '$KundenID')";
                                    if (mysqli_query($conn, $sql)) {
                                        $sql = "INSERT INTO kundenauswertung (Bonitaetsklasse, ABC_Klasse, KundenID) VALUES ('BBB', 'C', '$KundenID')";
                                        if (mysqli_query($conn, $sql)) {
                                            logmessage("[Registrierung] Datensatz für neuen Firmekunden mi der KundenID " . $KundenID . " erfolgreich erzeugt.");
                                            include 'mail_registrierung_firmenkunde.php';
                                        } else {
                                            logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle kundenauswertung: " . mysqli_error($conn), "ERROR");}
                                    } else {
                                        logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle user: " . mysqli_error($conn), "ERROR");}
                                } else {
                                    logmessage("[Registrierung] Fehler beim Aktualisierung der Tabelle firmenkunde: " . mysqli_error($conn), "ERROR");}
                            } else {
                                logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle firma: " . mysqli_error($conn), "ERROR");}
                        } else {
                            logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle zahlungsinformationen: " . mysqli_error($conn), "ERROR");}
                    } else {
                        logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle kontaktdaten: " . mysqli_error($conn), "ERROR");}
                } else {
                    logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle kunden: " . mysqli_error($conn), "ERROR");}
            } else {
                logmessage("[Registrierung] Fehler beim Einfügen in die Tabelle firmenkunde: " . mysqli_error($conn), "ERROR");}
        } else {
            logmessage("[Registrierung] Kundentyp ist ungültig", "WARNUNG");}
    } else {
        logmessage("[Registrierung] Kundentyp nicht ausgewählt", "WARNUNG");}

    // Verbindung schließen
    mysqli_close($conn);
    logMessage("[Registrierung] Verbindung geschlossen");

} else {
    logMessage("[Registrierung] Das Formular wurde nicht gesendet", "ERROR");
}
?>