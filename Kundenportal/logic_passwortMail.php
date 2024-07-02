<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/php/vendor/autoload.php';

// Überprüfen, ob ein POST-Request erfolgt ist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lese die JSON-Daten aus dem Request-Body und dekodiere sie
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data);

    // Zugriff auf den Benutzernamen
    if (isset($data->benutzername)) {
        $username = $data->benutzername;

        // E-Mail-Instanz erstellen
        $mail = new PHPMailer(true);

        // Initialisiere das Antwortarray
        $response = array("success" => false, "message" => "");

        try {
            // Servereinstellungen konfigurieren
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'autovermietung.jomaface@outlook.de';
            $mail->Password = '!krusewhs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Empfänger, Betreff und Nachricht einstellen
            $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
            $mail->addAddress('fschlaghecken@gmx.de', 'Fabian Schlaghecken');
            $mail->Subject = 'Passwort vergessen?';
            $mail->isHTML(true);
            $mail->Body = "
<!DOCTYPE html>
<html lang='de'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Passwort vergessen?</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #777;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class='container'>
        </div>
        <div class='content'>
            <p>Sehr geehrte(r) Frau/Herr Schlaghecken,</p>
            <p>Bitte setzen Sie sich mit einem unserer Mitarbeiter in Verbindung um Ihr Passwort zu ändern!<br>
            <p>Mit freundlichen Grüßen,<br>
            Ihre Autovermietung jomaface</p>
        </div>
        <div class='footer'>
            <p>Diese E-Mail wurde automatisch generiert. Bitte antworten Sie nicht auf diese Nachricht.</p>
        </div>
    </div>
</body>
</html>
";
            // E-Mail senden
            $mail->send();

            // Erfolgsmeldung zurückgeben
            $response["success"] = true;
            $response["message"] = "E-Mail erfolgreich gesendet.";
        } catch (Exception $e) {
            // Fehlermeldung zurückgeben
            $response["message"] = "E-Mail konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}";
        }

        // JSON-Response zurückgeben
        echo json_encode($response);
    } else {
        // Fehlerbehandlung, falls kein Benutzername übergeben wurde
        echo "Kein Benutzername übergeben.";
    }
} else {
    // Fehlerbehandlung, falls kein POST-Request erfolgt ist
    echo "POST-Request erwartet.";
}
?>
