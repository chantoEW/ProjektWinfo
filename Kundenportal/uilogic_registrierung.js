document.addEventListener("DOMContentLoaded", function() {
    // Rufe die toggleFirmaFeld Funktion auf, um den Initialzustand des Formulars festzulegen
    toggleFirmaFeld();

    // Event Listener für den "Weiter"-Button in jedem Formularabschnitt
    var weiterButtons = document.querySelectorAll('.weiter-button');
    weiterButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Verhindert das Standardverhalten des Buttons

            // Leere den Cache, indem die Eingabefelder aktualisiert werden
            aktualisiereEingabefelder();

            // Je nach Tab-Namen die entsprechende Validierungsfunktion aufrufen
            var tabName = button.dataset.tab;
            switch (tabName) {
                case 'benutzerdaten':
                    validiereFormularBenutzer();
                    break;
                case 'kontaktdaten':
                    validiereFormularKontakt();
                    break;
                case 'zahlungsdaten':
                    validiereFormularZahlung();
                    break;
                default:
                    console.error('Ungültiger Tab-Name');
            }
        });
    });
});

function logMessage(message, type = 'INFO') {
    console.log(`[${type}] ${message}`); // Log to the console
    sendLogToServer(message, type);
}

function sendLogToServer(message, type) {
    fetch('logic_logging.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ log: message, type: type })
    })
        .then(response => response.json())
        .then(data => console.log('Server response:', data))
        .catch(error => console.error('Error:', error));
}

function validiereFormularBenutzer() {
    var passwort = document.getElementById('passwort').value;
    var passwortWiederholen = document.getElementById('passwort_wiederholen').value;
    var firmaContainer = document.getElementById('firmaContainer');
    var benutzername = document.getElementById('benutzername').value;

    var error = false;

    // Simulierte Validierung des Benutzernamens ohne AJAX
    // Annahme: checkUsername() ist eine Funktion, die asynchron den Benutzernamen prüft
    checkUsername(benutzername, function(exists) {
        if (exists) {
            document.getElementById('username_error').style.display = 'inline';
            error = true; // Setzt den Fehlerstatus auf true, wenn der Benutzername existiert
        } else {
            document.getElementById('username_error').style.display = 'none';
            error = false; // Setzt den Fehlerstatus auf false, wenn der Benutzername nicht existiert
        }

        // Validierung nur ausführen, wenn keine Fehler aufgetreten sind
        if (!error) {
            // Validierung der Passwörter
            if (passwort.length < 8) { // Überprüfung auf Mindestlänge von 8 Zeichen
                document.getElementById('passwort_fehlermeldung').style.display = 'inline';
                document.getElementById('passwort_uebereinstimmung').style.display = 'none';
                document.getElementById('passwort_fehlermeldung').innerText = 'Das Passwort muss mindestens 8 Zeichen lang sein.';
                error = true;
            } else if (passwort !== passwortWiederholen) {
                document.getElementById('passwort_fehlermeldung').style.display = 'inline';
                document.getElementById('passwort_uebereinstimmung').style.display = 'none';
                document.getElementById('passwort_fehlermeldung').innerText = 'Die Passwörter stimmen nicht überein.';
                error = true;
            } else {
                document.getElementById('passwort_fehlermeldung').style.display = 'none';
                document.getElementById('passwort_uebereinstimmung').style.display = 'inline';
            }

            // Validierung des Firmennamens, falls Geschäftskunde ausgewählt ist
            var kundentyp = document.querySelector('input[name="kundentyp"]:checked').value;
            if (kundentyp === "Geschäftskunde" && firmaContainer.style.display === "none") {
                alert('Bitte geben Sie den Firmennamen ein.');
                error = true;
            }

            // Markiere alle leeren Pflichtfelder
            markiereFehlendeFelder();

            // Wenn keine Fehler vorhanden sind, öffne das Erfolgspopup
            if (!error) {
                switchTab('kontaktdaten');
            }
        }
    });
}

function validiereFormularKontakt() {
    var email = document.getElementById('login.email').value;
    var error = false;

    var emailRegex = /^[^\s@]+@[^\s@]+\.(?:com|de)$/; // Beispielhaftes Regex-Muster
    if (!emailRegex.test(email)) {
        alert('Bitte geben Sie eine gültige E-Mail-Adresse im Format "Text@Text.de" oder "Text@Text.com" ein.');
        error = true;
    }

    markiereFehlendeFelder(); // Markiert alle leeren Pflichtfelder

    if (!error) {
        switchTab('zahlungsdaten');
    } else {
        logMessage("Das Kontaktformular ist nicht vollständig");
    }
}

function validiereFormularZahlung() {
    var passwort = document.getElementById('passwort').value;
    var passwortWiederholen = document.getElementById('passwort_wiederholen').value;
    var firmaContainer = document.getElementById('firmaContainer');
    var benutzername = document.getElementById('benutzername').value;

    var error = false;

    // Simulierte Validierung des Benutzernamens ohne AJAX
    // Annahme: checkUsername() ist eine Funktion, die asynchron den Benutzernamen prüft
    checkUsername(benutzername, function(exists) {
        if (exists) {
            document.getElementById('username_error').style.display = 'inline';
            error = true; // Setzt den Fehlerstatus auf true, wenn der Benutzername existiert
        } else {
            document.getElementById('username_error').style.display = 'none';
            error = false; // Setzt den Fehlerstatus auf false, wenn der Benutzername nicht existiert
        }

        // Weitere Validierung nur ausführen, wenn keine Fehler aufgetreten sind
        if (!error) {
            // Validierung der Passwörter
            if (passwort !== passwortWiederholen) {
                document.getElementById('passwort_fehlermeldung').style.display = 'inline';
                document.getElementById('passwort_uebereinstimmung').style.display = 'none';
                error = true;
            } else {
                document.getElementById('passwort_fehlermeldung').style.display = 'none';
                document.getElementById('passwort_uebereinstimmung').style.display = 'inline';
            }

            // Validierung des Firmennamens, falls Geschäftskunde ausgewählt ist
            var kundentyp = document.querySelector('input[name="kundentyp"]:checked').value;
            if (kundentyp === "Geschäftskunde" && firmaContainer.style.display === "none") {
                alert('Bitte geben Sie den Firmennamen ein.');
                error = true;
            }

            // Markiere alle leeren Pflichtfelder
            markiereFehlendeFelder();

            // Wenn keine Fehler vorhanden sind, öffne das Erfolgspopup
            if (!error) {
                openSuccessMessageModal();
            }
        }
    });
}

// Simulierte Funktion für die Überprüfung des Benutzernamens
function checkUsername(username, callback) {
    // Hier könnte eine asynchrone Überprüfung des Benutzernamens erfolgen
    // Beispiel: Hier wird der Benutzername "testuser" als existierend angenommen
    var exists = (username === "testuser");
    callback(exists);
}

// Funktion zum Öffnen des Popup-Fensters für die Erfolgsmeldung
function openSuccessMessageModal() {
    var modal = document.getElementById("successMessageModal");
    if (modal) {
        modal.style.display = "block";
    } else {
        console.error('Das Modal-Element wurde nicht gefunden.');
    }
}

// Funktion zum Schließen des Popup-Fensters für die Erfolgsmeldung
function closeSuccessMessageModal() {
    var modal = document.getElementById("successMessageModal");
    modal.style.display = "none";
    window.location.href = "login.html"; // Weiterleitung zur Login-Seite
}

// Funktion zum Markieren aller leeren Pflichtfelder
function markiereFehlendeFelder() {
    var inputFields = document.querySelectorAll('.input-field[required]');
    inputFields.forEach(function(field) {
        if (!field.value) {
            field.style.border = '1px solid red';
            logMessage("Nicht alle Felder wurden ausgefüllt", 'ERROR')
        } else {
            field.style.border = '1px solid #ccc';
        }
    });
}

// Funktion zum Wechseln zwischen den verschiedenen Datenkategorien
function switchTab(tabName) {
    var tabs = document.querySelectorAll('#mainContent > div');
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
}

// Funktion zum Wechseln zwischen dem Anzeigen- und dem Ändern-Modus
function toggleBearbeitungsmodus() {
    var inputs = document.querySelectorAll('#mainContent input');
    var speichernLink = document.getElementById("speichernLink");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].disabled = !inputs[i].disabled;
    }
    if (speichernLink.style.display === "none") {
        speichernLink.style.display = "block";
    } else {
        speichernLink.style.display = "none";
    }
}

// Funktion zum Wechseln des Titels
function toggleTitel() {
    var pageTitle = document.getElementById("pageTitle");
    if (pageTitle.innerText === "Kunde ändern") {
        pageTitle.innerText = "Kunde anzeigen";
    } else {
        pageTitle.innerText = "Kunde ändern";
    }
}

// Funktion zum Ein-/Ausblenden des Firmenfeldes
function toggleFirmaFeld() {
    var firmaContainer = document.getElementById("firmaContainer");
    var kundentyp = document.querySelector('input[name="kundentyp"]:checked').value;
    if (kundentyp === "Geschäftskunde") {
        firmaContainer.style.display = "block";
    } else {
        firmaContainer.style.display = "none";
    }
}

// Funktion zum Aktualisieren der Eingabefelder
function aktualisiereEingabefelder() {
    var inputs = document.querySelectorAll('#mainContent input, #mainContent select');
    inputs.forEach(function(input) {
        input.value = input.value; // Setze den Wert erneut, um den Cache zu leeren
    });
}
