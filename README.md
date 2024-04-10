# CCA - Customer Contact Api

Mit der API können Kontakte der Kunden durch Benutzer des Systems verwaltet werden.
Entwickelt worden ist die API mit Symfony7 und Api Platform 3.


# Installation/Konfiguration

Bitte einfach 'composer install' in dem Terminal ausführen. (Composer Version2)
Neben Symfony7.0 (keine webapp Installation) wird unter anderem das ApiPlatform3 installiert, mit dem die API umgesetzt wurde.

Für die lokale Installation bietet sich LAMP, WAMP oder MAMP an. (Anstatt Apache2 kann auch nginx benutzt werden,) Oder auch Docker.

PHP Version bei der Entwicklung war 8.3.
MySQL als Datenbank in der Version  8.0.36-0ubuntu0.22.04.1.
PHPUnit v9.6 füs Testen als Symfony pack Installation.

Nach der composer Installation sollte keine weitere Einstellungen notwendig sein ausser die Erstellung der .env Files für die lokale- und die Testumgebung.


## Aufruf des Projektes bzw. Endpoints
Sollte Symfony CLI installiert sein, bietet sich an den Server mit  'symfony serve -d' zu starten.

Ohne die Einrichtung eines virtuellen Hostes ist das Projekt dann im Browser unter 'http://127.0.0.1:8000/?api'
zu erreichen, Hier hat man dann eine Übersicht über die Endpoints sowie Informationen darüber.

Die Endpoints können über die Webseite ausgeführt werden. Allerdings eine Authentifikation im Browser ist nicht verfügbar.

**Für die Entwicklung und Interaktion mit den Endpoints wurde der Api Client "Postman" benutzt. Mit Postman wurde auch die eingerichtete Auth getestet.**


## CRUD auf die Endpoins

Die Vorgaben zu der Aufgabe führten zur Entstehung der u.s. Endpoins mit folgenden Funktionalitäten:

- /api/customers  => Customer Entity - CRUD - Role User - *Search- und
  order Funktionalität für Datensätze* *  der Customers*
- /api/addresses   => Address Entity   -  CRUD - Role User
- /api/city 			=> City Entity          -  CRUD - Role User
- /api/country      => Country Entity    -  CRUD - Role User
- /api/apitokens   => ApiToken Entity  -  CRUD authorisiert
  nur für die Role Admin
- /api/users          => User Entity          -  CRUD allerdings
  authorisiert für die Role Admin

Alle Entitäten stehen in Relationen zu anderen Entities.
Das Löschen von einer Country wird beispielweise "orphanRemoval" betr. City und weiter betr. Adressen löschen .


## Auth

Auth wurde letztlich eine eigen Entwicklung.
Fokus lag auf Auth weswegen eine Login Funktionalität aktuell nicht verfügbar ist.

Der Workflow für Auth ist wie folgt:

1. User in der Tabell user erstellen mit mandatory fields: email,
   password und die rolle ['ROLE_USER']
2. Danach in Terminal die Datei generate-token.php im Projekt Verzeichnis aufrufen, die eine
   apitoken generiert und in Terminal ausgibt.
3. Diesen Token zu dem erstellten User (user_id) in das Feld token in
   der apitoken Tabelle hinterlegen.

*Es gibt eine eigens enwickelte "ApiTokenAuthenticator" Klassse im "src/Security" Ordner, die den Workflow bestimmt/kontrolliert.*


## Authorisierung

Users und apitokens Endpoint dürfen nur von Benutzer(user) mit der ROLE_ADMIN benutzt werden.
Diese Einstellung ist eher zweck Veranschaulichung von Authorisierung gedacht und wird im realen Workflow u.U. teils anders umgesetzt. Die Rolle wird zu dem User in der Tabelle "user" und in das Feld "roles" hinterlegt. (json)

## Http Header Einstellungen für die Interaktion mit den Endpoints

Bei der Benutzung eines Rest Clients (Postman) mussen folgende Einstellungen für den Http Header eingetragen werden:

- Accept 			=> application/ld+json
- api       			=> <API_TOKE> (Siehe Abschnitt Auth und Generieung von ApiToken)
- Content-Type => application/ld+json

**Sollte PATCH als Methode benutzt werden, muss der "Content-Type" => "application/merge-patch+json" gesetzt werden.

# PHPUnit Tests

Zur Veranschaulichung sind ApiTests für Endpoints "cities" und "countries" erstellt worden.
Bitte in .env.test DATABASE_URL eintragen.

Es gibt für die beiden Entitie City und Country Data Fixtures vorhanden, die in den Tests verwendet werden.

## Offene Probleme

- Api Platform legt bei der PUT Methode einen neuen Datensatz an, wenn
  email Feld als unique gesetzt ist.  Was letztlich zu
  ConstraintValidation Exception führt, wenn man beim PUT die
  Emailadresse nicht ändert. Das ist ein Bug, den ich nicht mehr
  beheben könnte.
- Post von Embedded Relations wird nicht persistiert. Da Customer in
  einer OneToMany Beziehung zur Adresse steht, bietet sich an beim
  Anlegen des Customers auch die Adresse mit einzutragen, um so weiteren
  Request zu sparen. Leider wird beim Post Methode die Adresse doch
  nicht persistiert. Allerdings bei GET auf Customer wird die
  Collection "Addresse" mit rangehängt.
- Wie bereits erwähnt bietet das System aktuell keine Registrierung
  oder Login an. In den Anforderungen waren diese auch nicht vorgegeben
  allerdings habe ich das tatsächlich  angefangen, da ApiPlatform
  hierfür eine Komponente JWT anbietet mit der darüber hinaus auch die
  Umsetzung von Auth möglich ist. Leider stösste ich bei der
  Installation und Einrichtungen auf Probleme, die ich zeit bedingt
  nicht weiter verfolgen könnte.
- Tests sind unvollständig sind aber leicht zu Ende zu führen.


# Fazit
Das war meine erste Erfahrung mit Api Platform, was mir besonders gut gefallen hat.
Ich musste hier und da viel lesen und ausprobieren aber alles in Allem hat es viel spaß gemacht.
Vorallem, das Zusammenspiel mit Symfony fand ich sehr interessant.

Bei Fragen stehe ich gerne zur Verfügung.


**Nützliche Commands:**
symfony serve -d //Server starten
symfony console doctrin:database:create //Datenbank erstellen
symfony console doctrin:migrations:diff   // Migration erstellen
symfony console doctrin:migrations:migrate //Migration ausführen
php generate-token.php  //ApiToken erstellen

-----Test Umgebung ---------
symfony console --env=test doctrin:migrations:migrate //Migration ausführen
php bin/phpunit tests //Tests ausführen


