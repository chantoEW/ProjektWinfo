<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kundenId = $_POST['id'];

    // Datei zum Anhängen öffnen
    $file = fopen("kundenID.txt", "a");
    if (!$file) {
        die("Fehler beim Öffnen der Datei.");
    }

    // FKundenID in die Datei schreiben
    fwrite($file, $kundenId . PHP_EOL);

    // Datei schließen
    fclose($file);

    echo "ID gespeichert: " . $kundenId;
}

