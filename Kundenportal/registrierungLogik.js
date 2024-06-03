document.addEventListener("DOMContentLoaded", function() {
    // Rufe die toggleFirmaFeld Funktion auf, um den Initialzustand des Formulars festzulegen
    toggleFirmaFeld();
});

function validiereFormular() {
    var passwort = document.getElementById('passwort').value;
    var passwortWiederholen = document.getElementById('passwort_wiederholen').value;
    var email = document.getElementById('email').value;
    var firmaContainer = document.getElementById('firmaContainer');

    var error = false;

    if (passwort !== passwortWiederholen) {
        document.getElementById('passwort_fehlermeldung').style.display = 'inline';
        document.getElementById('passwort_uebereinstimmung').style.display = 'none';
        error = true;
    } else {
        document.getElementById('passwort_fehlermeldung').style.display = 'none';
        document.getElementById('passwort_uebereinstimmung').style.display = 'inline';
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.(?:com|de)$/;
    if (!emailRegex.test(email)) {
        alert('Bitte geben Sie eine gültige E-Mail-Adresse im Format "Text@Text.de" oder "Text@Text.com" ein.');
        error = true;
    }

    var kundentyp = document.querySelector('input[name="kundentyp"]:checked').value;
    if (kundentyp === "Geschäftskunde" && firmaContainer.style.display === "none") {
        alert('Bitte geben Sie den Firmennamen ein.');
        error = true;
    }

    markiereFehlendeFelder(); // Alle Felder markieren, die leer sind

    if (!error) {
        document.getElementById("registrierungsformular").submit(); // Formular absenden, wenn keine Fehler vorhanden sind
    }
}

function markiereFehlendeFelder() {
    var inputFields = document.querySelectorAll('.input-field[required]');
    inputFields.forEach(function(field) {
        if (!field.value) {
            field.style.border = '1px solid red';
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