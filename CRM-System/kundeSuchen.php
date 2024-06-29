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
        <form method="POST" action="kundeAuswaehlen.php">
            <div class="input-title">Suche nach Kunden</div>

            <div class="form-group">
                <label>Kundentyp:</label>
                <span><label>
                    <input type="radio" name="kundentyp" value="privatkunde">
                    Privatkunde
                </label>
                <label>
                    <input type="radio" name="kundentyp" value="geschaeftskunde">
                    Geschäftskunde
                </label></span>
            </div>

            <div class="form-group">
                <label for="kundenId">Kunden-ID:</label>
                <input type="text" class="input-field" id="kundenId" name="kundenId">
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="input-field" id="name" name="name">
            </div>

            <div class="form-group">
                <label for="vorname">Vorname:</label>
                <input type="text" class="input-field" id="vorname" name="vorname">
            </div>

            <div class="form-group">
                <label for="strasse">Straße:</label>
                <input type="text" class="input-field" id="strasse" name="strasse">
            </div>

            <div class="form-group">
                <label for="ort">Ort:</label>
                <input type="text" class="input-field" id="ort" name="ort">
            </div>

            <div class="form-group">
                <label for="postleitzahl">Postleitzahl:</label>
                <input type="text" class="input-field" id="postleitzahl" name="postleitzahl">
            </div>

            <button type="submit">Suchen <i class="bi bi-search"></i></button>
        </form>

    </section>

    <footer>
        <p>&copy; 2024 CRM-System. Alle Rechte vorbehalten.</p>
    </footer>

</body>

</html>
