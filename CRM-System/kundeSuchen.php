<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="styleCRM.css" rel="stylesheet">
    <title>Kunde suchen</title>
    <!--<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('hintergrundbild.jpg');
            /* Hintergrundbild einfügen */
            background-size: cover;
            /* Bildgröße anpassen */
            background-repeat: no-repeat;
            /* Wiederholung des Bildes verhindern */
            background-attachment: fixed;
            /* Hintergrundbild fixieren */
            background-color: rgba(255, 255, 255, 0.8);
            /* Transparente Hintergrundfarbe */
            color: #000;
            /* Schriftfarbe schwarz */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #555;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav .link-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
        }

        nav a:hover {
            background-color: #777;
        }

        .container {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            width: 80%;
            /* Set the width to 80% */
            max-width: 900px;
            /* Reduce the max-width */
            margin: 20px auto;
            /* Center the container */
            background-color: rgba(255, 255, 255, 0.9);
            /* Weißer transparenter Hintergrund */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            /* Reduced gap between input fields */
            margin-bottom: 15px;
            /* Added margin-bottom for better spacing */
        }

        .form-group label {
            flex: 1 1 100%;
            font-weight: bold;
            margin-bottom: 5px;
            /* Reduced bottom margin for labels */
        }

        .input-field {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            flex: 1 1 calc(50% - 22px);
            /* Adjusted width for better spacing */
            box-sizing: border-box;
            background-color: #fff;
            /* Hintergrundfarbe der Eingabefelder weiß */
            color: #000;
            /* Schriftfarbe der Eingabefelder schwarz */
        }

        .input-title {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 1.2em;
            /* Increased font size for emphasis */
            text-align: center;
            width: 100%;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: relative;
        }

        button {
            padding: 10px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
            display: block;
            width: 100%;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        button:hover {
            background-color: #ffffff;
            color: #333;
        }
    </style>-->
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
