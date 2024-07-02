<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="styleCRM.css" rel="stylesheet">
    <title>Kunde suchen</title>

</head>

<body>

    <header>
        <h1>Kunde suchen</h1>
    </header>

    <nav>
        <div class="link-container">
            <a href="javascript:history.back()" class="link"><i class="bi bi-arrow-left"></i> Zurück</a>
            <a href="verwaltungStartseite.html" class="link" onclick="event.preventDefault();"><i class="bi bi-house"></i> Startseite</a>
            <a href="../Kundenportal/registrierung.html" class="link"><i class="bi bi-pencil"></i> Kunde anlegen</a>
            <a href="#" class="link"><i class="bi bi-door-closed"></i> Abmelden</a>
            <div id="kundenIdDiv">
                <!-- Kunden-ID: <span id="kundenIdSpan"></span> -->
            </div>
        </div>
    </nav>

    <section class="container">
        <?php
        // Verbindungsdaten
        $servername = "localhost";
        $username = "Chantal";
        $password = "";
        $dbname = "portal";

        // Verbindung herstellen
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verbindung prüfen
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Überprüfung nach dem Absenden des Formulars
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['kundentyp'])) {
                
                $kundentyp = $_POST['kundentyp'];
                
                if ($kundentyp == 'privatkunde') {

                    // SQL-Abfrage
                    $sql = "SELECT k.KundenID, pk.Name, pk.Vorname, pk.GebDatum, kd.Strasse, kd.Ort, kd.PLZ FROM kunden AS k JOIN privatkunde AS pk ON k.PKundenID = pk.PKundenID JOIN kontaktdaten AS kd ON kd.KontaktID = pk.KontaktID WHERE 1=1";
                    //echo  $_POST['kundenId'];
                    // Filter hinzufügen
                    if (!empty($_POST['kundenId'])) {
                        $kundenId = $conn->real_escape_string($_POST['kundenId']);
                        $sql .= " AND k.KundenID = '$kundenId'";
                    }

                    if (!empty($_POST['name'])) {
                        $name = $conn->real_escape_string($_POST['name']);
                        $sql .= " AND Name LIKE '%$name%'";
                    }

                    if (!empty($_POST['vorname'])) {
                        $vorname = $conn->real_escape_string($_POST['vorname']);
                        $sql .= " AND Vorname LIKE '%$vorname%'";
                    }

                    if (!empty($_POST['strasse'])) {
                        $strasse = $conn->real_escape_string($_POST['strasse']);
                        $sql .= " AND kd.Strasse LIKE '%$strasse%'";
                    }

                    if (!empty($_POST['ort'])) {
                        $ort = $conn->real_escape_string($_POST['ort']);
                        $sql .= " AND Ort LIKE '%$ort%'";
                    }

                    if (!empty($_POST['postleitzahl'])) {
                        $plz = $conn->real_escape_string($_POST['postleitzahl']);
                        $sql .= " AND PLZ LIKE '%$plz%'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Ausgabe der Ergebnisse als HTML-Tabelle
                        echo "<style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 20px 0;
                    font-family: Arial, sans-serif;
                }
                th, td {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #e0e0e0;
                    cursor: pointer;
                }
              </style>";

                        echo "<table>";
                        echo "<tr>
                <th>ID</th>
                <th>Name</th>
                <th>Vorname</th>
                <th>Geburtsdatum</th>
                <th>Straße</th>
                <th>Ort</th>
                <th>Postleitzahl</th>
              </tr>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=\"speichereID(" . $row["KundenID"] . ")\">";
                            echo "<td>" . $row["KundenID"] . "</td>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Vorname"] . "</td>";
                            echo "<td>" . $row["GebDatum"] . "</td>";
                            echo "<td>" . $row["Strasse"] . "</td>";
                            echo "<td>" . $row["Ort"] . "</td>";
                            echo "<td>" . $row["PLZ"] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "0 results";
                    }

                } elseif ($kundentyp == 'geschaeftskunde') {
                    // SQL-Abfrage
                    $sql = "SELECT k.KundenID, fk.Name, fk.Vorname, fk.GebDatum, kd.Strasse, kd.Ort, kd.PLZ FROM kunden AS k JOIN firmenkunde AS fk ON fk.FKundenID = k.FKundenID JOIN firma ON firma.FirmenID = fk.FirmenID JOIN kontaktdaten AS kd ON kd.KontaktID = firma.KontaktID WHERE 1=1";

                    // Filter hinzufügen
                    if (!empty($_POST['kundenId'])) {
                        $kundenId = $conn->real_escape_string($_POST['kundenId']);
                        $sql .= " AND k.KundenID = '$kundenId'";
                    }

                    if (!empty($_POST['name'])) {
                        $name = $conn->real_escape_string($_POST['name']);
                        $sql .= " AND Name LIKE '%$name%'";
                    }

                    if (!empty($_POST['vorname'])) {
                        $vorname = $conn->real_escape_string($_POST['vorname']);
                        $sql .= " AND Vorname LIKE '%$vorname%'";
                    }

                    if (!empty($_POST['strasse'])) {
                        $strasse = $conn->real_escape_string($_POST['strasse']);
                        $sql .= " AND kd.Strasse LIKE '%$strasse%'";
                    }

                    if (!empty($_POST['ort'])) {
                        $ort = $conn->real_escape_string($_POST['ort']);
                        $sql .= " AND Ort LIKE '%$ort%'";
                    }

                    if (!empty($_POST['postleitzahl'])) {
                        $plz = $conn->real_escape_string($_POST['postleitzahl']);
                        $sql .= " AND PLZ LIKE '%$plz%'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Ausgabe der Ergebnisse als HTML-Tabelle
                        echo "<style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 20px 0;
                    font-family: Arial, sans-serif;
                }
                th, td {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
                th {
                    background-color: #f2f2f2;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #e0e0e0;
                    cursor: pointer;
                }
              </style>";

                        echo "<table>";
                        echo "<tr>
                <th>ID</th>
                <th>Name</th>
                <th>Vorname</th>
                <th>Geburtsdatum</th>
                <th>Straße</th>
                <th>Ort</th>
                <th>Postleitzahl</th>
              </tr>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=\"speichereID(" . $row["KundenID"] . ")\">";
                            echo "<td>" . $row["KundenID"] . "</td>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["Vorname"] . "</td>";
                            echo "<td>" . $row["GebDatum"] . "</td>";
                            echo "<td>" . $row["Strasse"] . "</td>";
                            echo "<td>" . $row["Ort"] . "</td>";
                            echo "<td>" . $row["PLZ"] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "0 results";
                    }
                }
            } else {
                echo "Kein Kundentyp ausgewählt.";
            }
        }


        $conn->close();
        ?>
        <script>
            function speichereID(kundenId) {
                fetch('speichereID.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + kundenId
                })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                        // Weiterleiten zur Kundendatenanzeige
                        window.location.href = 'kundeAnzeigen.php?id=' + kundenId;
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>
    </section>

    <footer>
        <p>&copy; 2024 CRM-System. Alle Rechte vorbehalten.</p>
    </footer>

</body>

</html>