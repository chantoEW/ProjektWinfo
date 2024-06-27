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

            if (discountSelect.value === 'sommerferien') {
                discountCode = 'Rabatt-Code: SOMMER2024';
            } else if (discountSelect.value === 'fruehlingsanfang') {
                discountCode = 'Rabatt-Code: FRUEHLING2024';
            }

            emailBody.value += '\n\n' + discountCode;
        }
    </script>
</head>

<body>

    <header>
        <h1>Marketingaktion durchführen</h1>
    </header>

    <nav>
        <div class="link-container">
            <a href="javascript:history.back()" class="link"><i class="bi bi-arrow-left"></i> Zurück</a>
            <a href="startseite.html" class="link" onclick="event.preventDefault();"><i class="bi bi-house"></i> Startseite</a>
            <a href="#" class="link"><i class="bi bi-door-closed"></i> Abmelden</a>
        </div>
    </nav>
    
    <section class="container">
        <form method="POST" action="mailVersenden.php">
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
                    <input type="radio" id="kundenAlle" name="empfaenger" value="alle">
                    <label for="kundenAlle">Alle</label>
                </div>
            </div>
            <div class="form-group"><br>
                <label for="discountSelect">Aktionsart:</label>
                <select id="discountSelect" name="discountSelect" class="input-field" onchange="appendDiscountCode()">
                    <option value="">Bitte wählen</option>
                    <option value="sommerferien">Sommerferien</option>
                    <option value="fruehlingsanfang">Frühlingsanfang</option>
                    <option value="sonstige">Sonstige</option>
                </select>
            </div>
            <button type="submit">E-Mail abschicken</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 CRM-System. Alle Rechte vorbehalten.</p>
    </footer>

</body>

</html>
