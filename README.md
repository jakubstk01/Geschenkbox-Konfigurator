# Geschenkbox-Konfigurator — Entwicklungs-Setup

Starten der Entwicklungsumgebung (Docker):

1. Docker installieren.
2. Im Projektverzeichnis ausführen:

```bash
docker-compose up --build
```

- Web-App erreichbar unter: http://localhost:8080
- phpMyAdmin: http://localhost:8081 (user: root / rootpass)

Die Datenbank wird durch `db/init.sql` initialisiert und enthält Beispielprodukte,
einen Gutscheincode `SAVE10` (10% Rabatt) und drei vorkonfigurierte Boxen.

Kurze Hinweise:
- Registrieren speichert Benutzer in DB; Passwörter werden mit `password_hash` gespeichert.
- Konfigurationen können als eingeloggter Benutzer gespeichert werden.
