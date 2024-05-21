<?php
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
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    } else {
        echo("Verbindung zur DB wurde hergestellt");
    }

// Daten aus dem Formular erhalten
    $benutzername = $_POST['benutzername'];
    $nachname = $_POST['nachname'];
    $vorname = $_POST['vorname'];
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


    // Überprüfe, welcher Kundentyp ausgewählt wurde
    if (isset($_POST['kundentyp'])) {
        $kundentyp = $_POST['kundentyp'];

        // Hier kannst du entsprechende Aktionen basierend auf dem Kundentyp durchführen
        if ($kundentyp == 'Privatkunde') {
            // SQL-Query zum Einfügen der Daten in die entsprechende Tabelle
            // $sql = "INSERT INTO privatkunde (Benutzername, Vorname, Nachname, Passwort, eMail, Geburtsdatum, Kundentyp) VALUES ('$benutzername', '$vorname', '$nachname', '$hashed_password', '$email', '$geburtsdatum', '$kundentyp')";
            $sql = "INSERT INTO privatkunde (Name, Vorname, GebDatum) VALUES ('$nachname', '$vorname', '$geburtsdatum')";
            if (mysqli_query($conn, $sql)) {
                $PKundenID = mysqli_insert_id($conn);
                $sql = "INSERT INTO kunden (PKundenID, FKunde_PKunde) VALUES ('$PKundenID', 'p')";
                if (mysqli_query($conn, $sql)) {
                    $KundenID = mysqli_insert_id($conn);
                    $sql = "INSERT INTO kontaktdaten (Strasse, Ort, PLZ, Telefonnummer, Mail, PKundenID, FKunde_PKunde) VALUES ('$strasse', '$ort', '$plz', '$telefonnummer', '$email', '$PKundenID', 'p')";
                    if (mysqli_query($conn, $sql)) {
                        $KontaktID = mysqli_insert_id($conn);
                        $sql = "INSERT INTO zahlungsinformationen (BLZ, Institut, IBAN, Inhaber, KundenID) VALUES ('$blz', '$institut', '$iban', '$inhaber', '$KundenID')";
                        if (mysqli_query($conn, $sql)) {
                            $ZahlungsID = mysqli_insert_id($conn);
                            $sql = "UPDATE privatkunde SET KontaktID='$KontaktID', ZahlungsID='$ZahlungsID' WHERE PKundenID='$PKundenID'";
                            if (mysqli_query($conn, $sql)) {
                                echo "Daten erfolgreich in die Datenbank eingefügt.";
                            } else {
                                echo "Fehler beim Aktualisieren der Privatkunden-Daten: " . mysqli_error($conn);
                            }
                        } else {
                            echo "Fehler beim Einfügen der Zahlungsinformationen: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Fehler beim Einfügen der Kontaktdaten: " . mysqli_error($conn);
                    }
                } else {
                    echo "Fehler beim Einfügen der Kunden-Daten: " . mysqli_error($conn);
                }
            } else {
                echo "Fehler beim Einfügen in die Tabelle privatkunde: " . mysqli_error($conn);
            }
        } elseif ($kundentyp == 'Geschäftskunde') {
            $firmenname = $_POST['firmenname'];
        // SQL-Query zum Einfügen der Daten in die entsprechende Tabelle
        $sql = "INSERT INTO geschäftskunde (Benutzername, Vorname, Nachname, Passwort, eMail, Geburtsdatum, Firma, Kundentyp) VALUES ('$benutzername', '$vorname', '$nachname', '$hashed_password', '$email', '$geburtsdatum', '$firmenname', '$kundentyp')";
        } else {
            echo("Kundentyp ist ungültig");
        }
    } else {
        echo("Kundentyp nicht ausgewählt");
    }


// SQL-Query ausführen
    if (mysqli_query($conn, $sql)) {
        echo "Daten erfolgreich in die Tabelle eingefügt.";
    } else {
        echo "Fehler beim Einfügen der Daten: " . mysqli_error($conn);
    }

// Verbindung schließen
    mysqli_close($conn);

} else {
    echo "Formular wurde nicht gesendet";
}
?>
