import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class MALogin extends HttpServlet {

    // Datenbankverbindung
    private static final String URL = "jdbc:mariadb://localhost:3306/autovermietung";
    private static final String BENUTZERNAME = "projektWINFO";
    private static final String PASSWORT = "admin";

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        // Parameter aus dem Request erhalten
        String benutzername = request.getParameter("username");
        String passwort = request.getParameter("password");

        // Anmeldelogik aufrufen
        boolean anmeldungErfolgreich = pruefeAnmeldung(benutzername, passwort);

        if (anmeldungErfolgreich) {
            // Weiterleitung nach erfolgreicher Anmeldung
            response.sendRedirect("erfolgreich.jsp"); // Hier "erfolgreich.jsp" durch die Seite ersetzen, auf die Sie weiterleiten möchten
        } else {
            // Weiterleitung bei fehlgeschlagener Anmeldung
            response.sendRedirect("fehlgeschlagen.jsp"); // Hier "fehlgeschlagen.jsp" durch die Seite ersetzen, auf die Sie weiterleiten möchten
        }
    }

    // Methode zur Überprüfung der Anmeldedaten
    private static boolean pruefeAnmeldung(String benutzername, String passwort) {
        try {
            // Datenbankverbindung herstellen
            Connection verbindung = DriverManager.getConnection(URL, BENUTZERNAME, PASSWORT);

            // SQL-Abfrage vorbereiten
            String sql = "SELECT * FROM mitarbeiter WHERE benutzername = ? AND passwort = ?";
            PreparedStatement vorbereitung = verbindung.prepareStatement(sql);
            vorbereitung.setString(1, benutzername);
            vorbereitung.setString(2, passwort);

            // Abfrage ausführen
            ResultSet ergebnis = vorbereitung.executeQuery();

            // Überprüfen, ob ein Datensatz gefunden wurde
            boolean erfolgreich = ergebnis.next();

            // Verbindungsressourcen schließen
            ergebnis.close();
            vorbereitung.close();
            verbindung.close();

            return erfolgreich;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }
}
