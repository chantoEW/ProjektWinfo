<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/phpMyAdmin/vendor/autoload.php';

// E-Mail-Instanz erstellen
$mail = new PHPMailer(true);

try {
    // Servereinstellungen konfigurieren
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'autovermietung.jomaface@outlook.de';
    $mail->Password = 'projektwinfo!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Empfänger, Betreff und Nachricht einstellen
    $mail->setFrom('autovermietung.jomaface@outlook.de', 'Autovermietung jomaface');
    $mail->addAddress('fschlaghecken@gmx.de', 'Fabian Schlaghecken');
    $mail->Subject = 'Test Email';
    $mail->isHTML(true);
    $mail->Body = 'This is a test email.';

    // E-Mail senden
    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}




// Verbindung zur Datenbank herstellen
//$servername = "localhost";
//$username = "username";
//$password = "password";
//$dbname = "crm_database";

//$conn = new mysqli($servername, $username, $password, $dbname);
//if ($conn->connect_error) {
  //  die("Connection failed: " . $conn->connect_error);
//}

// Datum des aktuellen Tages
//$currentDate = date("Y-m-d");

// Datenbankabfrage, um Kunden mit Geburtstag heute zu finden
//$sql = "SELECT * FROM customers WHERE DATE_FORMAT(birthday, '%m-%d') = DATE_FORMAT('$currentDate', '%m-%d')";
//$result = $conn->query($sql);

//if ($result->num_rows > 0) {
    // Loop durch die Ergebnisse und senden Sie E-Mails an die Kunden
  //  while($row = $result->fetch_assoc()) {
   /*     $to = 'fschlaghecken@gmx.de' //$row["email"];
        $subject = "Treueaktion!";
        $message = "
            <!DOCTYPE html>
            <html lang='de'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Treue-E-Mail</title>
                <style>
                    CSS-Stile für die E-Mail hier einfügen
                </style>
            </head>
            <body style="margin: 0; padding: 0; font-family: Arial, sans-serif;">
    <div style="background-color: #f4f4f4; padding: 20px;">
        <h2 style="color: #333;">Exklusives Angebot für unsere geschätzten Kunden!</h2>
        <p style="color: #666;">Liebe Kundin, lieber Kunde,</p>
        <p style="color: #666;">Wir von der Autovermietung möchten uns bei Ihnen für Ihre Treue bedanken. Als kleines Dankeschön bieten wir Ihnen 10% Rabatt auf Ihre nächste Autovermietung bei uns an.</p>
        <p style="color: #666;">Nutzen Sie diesen Rabattcode bei Ihrer nächsten Buchung auf unserer Website: <strong>LOYALTY10</strong></p>
        <p style="color: #666;">Wir schätzen Ihre langjährige Unterstützung und freuen uns darauf, Sie bald wieder bei uns begrüßen zu dürfen!</p>
        <p style="color: #666;">Mit freundlichen Grüßen,<br>Ihr Autovermietungsteam</p>
        <div style="text-align: center;">
            <img src="treueaktion.png" alt="Treueaktion" style="max-width: 100%; margin-top: 20px;">
            <img src="autovermietung_logo.jpg" alt="Autovermietung" style="max-width: 100%; margin-top: 20px;">
        </div>
    </div>
</body>
</html>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: autovermietung.jomaface@outlook.de";

        // E-Mail senden
        mail($to, $subject, $message, $headers);
    }
} else {
    echo "Keine Kunden mit Geburtstag heute gefunden.";
}

// Verbindung zur Datenbank schließen
$conn->close();*/
?>
