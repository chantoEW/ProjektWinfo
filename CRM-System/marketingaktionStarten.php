<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo '<script>alert("' . $_SESSION['success_message'] . '");</script>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<script>alert("' . $_SESSION['error_message'] . '");</script>';
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="styleCRM.css" rel="stylesheet">
    <title>Marketingaktion starten</title>
    <script>
        function appendDiscountCode() {
            const emailBody = document.getElementById('emailBody');
            const discountSelect = document.getElementById('discountSelect');
            let discountCode = '';

            if (discountSelect.value !== '') {
                discountCode = 'Rabatt-Code: ' + discountSelect.value;
            }

            emailBody.value += '\n\n' + discountCode;
        }

        function updateDiscountText() {
            const discountSelect = document.getElementById('discountSelect');
            const discountText = discountSelect.options[discountSelect.selectedIndex].text;
            document.getElementById('discountText').value = discountText;
        }
    </script>
    <style>
        /* Allgemeine Stile für das Input-Feld */
        input[type="number"] {
            width: 100px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }

        /* Fokus-Stil */
        input[type="number"]:focus {
            border-color: #66afe9;
            box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
        }

        /* Allgemeine Stile für das Select-Feld */
        select {
            width: 200px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            background-color: white;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
            appearance: none;
            /* Entfernt das Standard-Dropdown-Symbol */
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        /* Fokus-Stil */
        select:focus {
            border-color: #66afe9;
            box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
        }

        /* Stil für das Label */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        /* Stil für das Formular */
        form {
            margin: 20px;
        }

        
    </style>
</head>

<body>
    <header>
        <h1>Marketingaktion durchführen</h1>
    </header>

    <nav>
        <div class="link-container">
            <a href="javascript:history.back()" class="link"><i class="bi bi-arrow-left"></i> Zurück</a>
            <a href="marketingStartseite.html" class="link" onclick="event.preventDefault();"><i class="bi bi-house"></i>
                Startseite</a>
            <a href="index.html" class="link"><i class="bi bi-door-closed"></i> Abmelden</a>
        </div>
    </nav>

    <section class="container">
        <form method="POST" action="marketingaktionMail.php">
            <div class="form-group">
                <label for="emailSubject">Betreff:</label>
                <input type="text" id="emailSubject" name="emailSubject" class="input-field" required>
            </div>
            <div class="form-group">
                <label for="emailBody">E-Mail-Text:</label>
                <textarea id="emailBody" name="emailBody" class="input-field" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label>Empfänger:</label>
                <div>
                    <input type="radio" id="kundenA" name="empfaenger" value="a-kunden" required>
                    <label for="kundenA">A-Kunden</label>
                </div>
                <div>
                    <input type="radio" id="kundenB" name="empfaenger" value="b-kunden">
                    <label for="kundenB">B-Kunden</label>
                </div>
                <div>
                    <input type="radio" id="kundenC" name="empfaenger" value="c-kunden">
                    <label for="kundenC">C-Kunden</label>
                </div>
                <div>
                    <input type="radio" id="kundenAlle" name="empfaenger" value="alle">
                    <label for="kundenAlle">Alle</label>
                </div>
            </div>

            <div class="form-group"><br>
                <label for="discountSelect">Aktionsart:</label>
                <select id="discountSelect" name="discountSelect" class="input-field"
                    onchange="appendDiscountCode(); updateDiscountText();">
                    <option value="">Bitte wählen</option>
                    <?php
                    // Datenbankverbindung herstellen
                    $servername = "localhost";
                    $username = "projektwinfo";
                    $password = "Ocm394Ldmc";
                    $dbname = "portal";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Verbindung überprüfen
                    if ($conn->connect_error) {
                        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
                    }

                    // Daten aus der Tabelle "rabattart" abrufen
                    $sql = "SELECT ID, Art, Rabatt FROM rabattart";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Daten in das Auswahlfeld einfügen
                        while ($row = $result->fetch_assoc()) {
                            $discountCode = generateRandomCode();
                            echo "<option value='$discountCode'>{$row['Art']} ({$row['Rabatt']}%)</option>";
                        }
                    } else {
                        echo "<option value=''>Keine Rabatte verfügbar</option>";
                    }

                    $conn->close();

                    // Funktion zur Generierung eines zufälligen Codes
                    function generateRandomCode($length = 20)
                    {
                        return substr(bin2hex(random_bytes($length)), 0, $length);
                    }
                    ?>
                </select>
                <input type="hidden" id="discountText" name="discountText" value="">
                
                <label for="prozentsatz">Individueller Rabatt (%):</label>
                <input type="number" step="0.01" min="0" max="1" name="prozentsatz" id="prozentsatz">
            </div>
            <button type="submit">E-Mail abschicken</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 CRM-System. Alle Rechte vorbehalten.</p>
    </footer>
</body>

</html>