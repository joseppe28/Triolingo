Create Database Triolingo;
Use Triolingo;

CREATE TABLE Deutsch_Vocab (
    DID INT PriMARY KEY AUTO_INCREMENT,
    Wort VARCHAR(255) NOT NULL,
    Audio VARCHAR(255) NOT NULL,
    Bild VARCHAR(255)
);
CREATE TABLE Englisch_Vocab (
    EID INT PriMARY KEY AUTO_INCREMENT,
    Wort VARCHAR(255) NOT NULL,
    Audio VARCHAR(255) NOT NULL,
    Bild VARCHAR(255)
);

CREATE TABLE Einheit(
    EinID INT PriMARY KEY AUTO_INCREMENT,
    Thema VARCHAR(255) NOT NULL,
    Beschreibung VARCHAR(255) NOT NULL
);

CREATE TABLE Vocab(
    VID INT PriMARY KEY AUTO_INCREMENT,
    EID INT NOT NULL,
    DID INT NOT NULL,
    EinID INT NOT NULL,
    FOREIGN KEY (EID) REFERENCES Englisch_Vocab(EID),
    FOREIGN KEY (DID) REFERENCES Deutsch_Vocab(DID),
    FOREIGN KEY (EinID) REFERENCES Einheit(EinID)
);

CREATE TABLE Exercise(
    ExID INT PriMARY KEY AUTO_INCREMENT,
    EinID INT NOT NULL,
    Typ VARCHAR(255) NOT NULL,
    FOREIGN KEY (EinID) REFERENCES Einheit(EinID)
);

CREATE TABLE User(
    UID INT PriMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL,
    Passwort VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL
);

CREATE TABLE Level(
    LID INT PriMARY KEY AUTO_INCREMENT,
    VID INT NOT NULL,
    UID INT NOT NULL,
    Level INT NOT NULL,
    FOREIGN KEY (VID) REFERENCES Vocab(VID),
    FOREIGN KEY (UID) REFERENCES User(UID)
);


Create Table User_Stats(
    UID INT primary key,
    Lessons_Completed INT,
    Words_Learned INT, 
    FOREIGN KEY (UID) REFERENCES User(UID)
);  

Create Table Lesson(
    LID INT primary key auto_increment,
    BID INT NOT NULL,
    UID INT NOT NULL,
    foreign key (UID) references User(UID)
);

CREATE TABLE FehlerStatistik (
    FehlerID INT PRIMARY KEY AUTO_INCREMENT,
    UID INT NOT NULL,
    VID INT NOT NULL,
    FehlerAnzahl INT NOT NULL DEFAULT 0,
    UNIQUE (UID, VID),
    FOREIGN KEY (UID) REFERENCES User(UID),
    FOREIGN KEY (VID) REFERENCES Vocab(VID)
);

-- Insert test data into Einheit table
INSERT INTO Einheit (Thema, Beschreibung) VALUES 
('Basics', 'Basic vocabulary for beginners'),
('Travel', 'Vocabulary related to travel and transportation'),
('Food', 'Vocabulary related to food and dining'),
('Work', 'Vocabulary related to work and professions');

-- Insert test data into Deutsch_Vocab table
INSERT INTO Deutsch_Vocab (Wort, Audio, Bild) VALUES 
('Haus', 'haus.mp3', 'haus.jpg'),
('Auto', 'auto.mp3', 'auto.jpg'),
('Buch', 'buch.mp3', 'buch.jpg'),
('Apfel', 'apfel.mp3', 'apfel.jpg'),
('Hund', 'hund.mp3', 'hund.jpg'),
('Katze', 'katze.mp3', 'katze.jpg'),
('Tisch', 'tisch.mp3', 'tisch.jpg'),
('Stuhl', 'stuhl.mp3', 'stuhl.jpg'),
('Fenster', 'fenster.mp3', 'fenster.jpg'),
('Tür', 'tuer.mp3', 'tuer.jpg'),
('Baum', 'baum.mp3', 'baum.jpg'),
('Blume', 'blume.mp3', 'blume.jpg'),
('Wasser', 'wasser.mp3', 'wasser.jpg'),
('Milch', 'milch.mp3', 'milch.jpg'),
('Brot', 'brot.mp3', 'brot.jpg');

-- Insert test data into Englisch_Vocab table
INSERT INTO Englisch_Vocab (Wort, Audio, Bild) VALUES 
('House', 'house.mp3', 'house.jpg'),
('Car', 'car.mp3', 'car.jpg'),
('Book', 'book.mp3', 'book.jpg'),
('Apple', 'apple.mp3', 'apple.jpg'),
('Dog', 'dog.mp3', 'dog.jpg'),
('Cat', 'cat.mp3', 'cat.jpg'),
('Table', 'table.mp3', 'table.jpg'),
('Chair', 'chair.mp3', 'chair.jpg'),
('Window', 'window.mp3', 'window.jpg'),
('Door', 'door.mp3', 'door.jpg'),
('Tree', 'tree.mp3', 'tree.jpg'),
('Flower', 'flower.mp3', 'flower.jpg'),
('Water', 'water.mp3', 'water.jpg'),
('Milk', 'milk.mp3', 'milk.jpg'),
('Bread', 'bread.mp3', 'bread.jpg');

-- Insert test data into Vocab table
INSERT INTO Vocab (EID, DID, EinID) VALUES 
(1, 1, 1), -- House - Haus in Basics
(2, 2, 1), -- Car - Auto in Basics
(3, 3, 2), -- Book - Buch in Travel
(4, 4, 3), -- Apple - Apfel in Food
(5, 5, 1), -- Dog - Hund in Basics
(6, 6, 1), -- Cat - Katze in Basics
(7, 7, 1), -- Table - Tisch in Basics
(8, 8, 1), -- Chair - Stuhl in Basics
(9, 9, 1), -- Window - Fenster in Basics
(10, 10, 1), -- Door - Tür in Basics
(11, 11, 2), -- Tree - Baum in Travel
(12, 12, 2), -- Flower - Blume in Travel
(13, 13, 3), -- Water - Wasser in Food
(14, 14, 3), -- Milk - Milch in Food
(15, 15, 3); -- Bread - Brot in Food

-- Insert test data into Exercise table
INSERT INTO Exercise (EinID, Typ) VALUES 
(1, 'Multiple Choice'),
(2, 'Fill in the Blanks'),
(3, 'Matching'),
(4, 'True or False');

-- Insert test data into User table
INSERT INTO User (Name, Passwort, Email) VALUES 
('Max Mustermann', 'password123', 'max@example.com'),
('Erika Musterfrau', 'securepass', 'erika@example.com'),
('John Doe', 'mypassword', 'john@example.com'),
('Jane Smith', 'pass1234', 'jane@example.com');

-- Insert test data into Level table
INSERT INTO Level (VID, UID, Level) VALUES 
(1, 1, 1), -- Max Mustermann knows House - Haus at level 1
(2, 2, 2), -- Erika Musterfrau knows Car - Auto at level 2
(3, 3, 1), -- John Doe knows Book - Buch at level 1
(4, 4, 3), -- Jane Smith knows Apple - Apfel at level 3
(5, 1, 2), -- Max Mustermann knows Dog - Hund at level 2
(6, 2, 1), -- Erika Musterfrau knows Cat - Katze at level 1
(7, 3, 3), -- John Doe knows Table - Tisch at level 3
(8, 4, 2), -- Jane Smith knows Chair - Stuhl at level 2
(9, 1, 1), -- Max Mustermann knows Window - Fenster at level 1
(10, 2, 3), -- Erika Musterfrau knows Door - Tür at level 3
(11, 3, 2), -- John Doe knows Tree - Baum at level 2
(12, 4, 1), -- Jane Smith knows Flower - Blume at level 1
(13, 1, 3), -- Max Mustermann knows Water - Wasser at level 3
(14, 2, 2), -- Erika Musterfrau knows Milk - Milch at level 2
(15, 3, 1); -- John Doe knows Bread - Brot at level 1

-- Insert additional German vocabulary for Einheit 1
INSERT INTO Deutsch_Vocab (Wort, Audio, Bild) VALUES 
('Mann', 'mann.mp3', 'mann.jpg'),
('Frau', 'frau.mp3', 'frau.jpg'),
('Kind', 'kind.mp3', 'kind.jpg'),
('Junge', 'junge.mp3', 'junge.jpg'),
('Mädchen', 'maedchen.mp3', 'maedchen.jpg'),
('Wasser', 'wasser.mp3', 'wasser.jpg'),
('Brot', 'brot.mp3', 'brot.jpg'),
('Straße', 'strasse.mp3', 'strasse.jpg'),
('Stadt', 'stadt.mp3', 'stadt.jpg'),
('Land', 'land.mp3', 'land.jpg'),
('Sonne', 'sonne.mp3', 'sonne.jpg'),
('Mond', 'mond.mp3', 'mond.jpg'),
('Stern', 'stern.mp3', 'stern.jpg'),
('Himmel', 'himmel.mp3', 'himmel.jpg'),
('Erde', 'erde.mp3', 'erde.jpg'),
('Telefon', 'telefon.mp3', 'telefon.jpg'),
('Computer', 'computer.mp3', 'computer.jpg'),
('Schule', 'schule.mp3', 'schule.jpg'),
('Büro', 'buero.mp3', 'buero.jpg'),
('Zeit', 'zeit.mp3', 'zeit.jpg'),
('Tag', 'tag.mp3', 'tag.jpg'),
('Nacht', 'nacht.mp3', 'nacht.jpg'),
('Morgen', 'morgen.mp3', 'morgen.jpg'),
('Abend', 'abend.mp3', 'abend.jpg'),
('Name', 'name.mp3', 'name.jpg'),
('Freund', 'freund.mp3', 'freund.jpg'),
('Familie', 'familie.mp3', 'familie.jpg'),
('Eltern', 'eltern.mp3', 'eltern.jpg'),
('Bruder', 'bruder.mp3', 'bruder.jpg'),
('Schwester', 'schwester.mp3', 'schwester.jpg');

-- Insert corresponding English vocabulary
INSERT INTO Englisch_Vocab (Wort, Audio, Bild) VALUES 
('Man', 'man.mp3', 'man.jpg'),
('Woman', 'woman.mp3', 'woman.jpg'),
('Child', 'child.mp3', 'child.jpg'),
('Boy', 'boy.mp3', 'boy.jpg'),
('Girl', 'girl.mp3', 'girl.jpg'),
('Water', 'water.mp3', 'water.jpg'),
('Bread', 'bread.mp3', 'bread.jpg'),
('Street', 'street.mp3', 'street.jpg'),
('City', 'city.mp3', 'city.jpg'),
('Country', 'country.mp3', 'country.jpg'),
('Sun', 'sun.mp3', 'sun.jpg'),
('Moon', 'moon.mp3', 'moon.jpg'),
('Star', 'star.mp3', 'star.jpg'),
('Sky', 'sky.mp3', 'sky.jpg'),
('Earth', 'earth.mp3', 'earth.jpg'),
('Phone', 'phone.mp3', 'phone.jpg'),
('Computer', 'computer.mp3', 'computer.jpg'),
('School', 'school.mp3', 'school.jpg'),
('Office', 'office.mp3', 'office.jpg'),
('Time', 'time.mp3', 'time.jpg'),
('Day', 'day.mp3', 'day.jpg'),
('Night', 'night.mp3', 'night.jpg'),
('Morning', 'morning.mp3', 'morning.jpg'),
('Evening', 'evening.mp3', 'evening.jpg'),
('Name', 'name.mp3', 'name.jpg'),
('Friend', 'friend.mp3', 'friend.jpg'),
('Family', 'family.mp3', 'family.jpg'),
('Parents', 'parents.mp3', 'parents.jpg'),
('Brother', 'brother.mp3', 'brother.jpg'),
('Sister', 'sister.mp3', 'sister.jpg');

-- Link vocabulary pairs to Einheit 1
INSERT INTO Vocab (EID, DID, EinID) VALUES 
(16, 16, 1), -- Man - Mann
(17, 17, 1), -- Woman - Frau
(18, 18, 1), -- Child - Kind
(19, 19, 1), -- Boy - Junge
(20, 20, 1), -- Girl - Mädchen
(21, 21, 1), -- Water - Wasser
(22, 22, 1), -- Bread - Brot
(23, 23, 1), -- Street - Straße
(24, 24, 1), -- City - Stadt
(25, 25, 1), -- Country - Land
(26, 26, 1), -- Sun - Sonne
(27, 27, 1), -- Moon - Mond
(28, 28, 1), -- Star - Stern
(29, 29, 1), -- Sky - Himmel
(30, 30, 1), -- Earth - Erde
(31, 31, 1), -- Phone - Telefon
(32, 32, 1), -- Computer - Computer
(33, 33, 1), -- School - Schule
(34, 34, 1), -- Office - Büro
(35, 35, 1), -- Time - Zeit
(36, 36, 1), -- Day - Tag
(37, 37, 1), -- Night - Nacht
(38, 38, 1), -- Morning - Morgen
(39, 39, 1), -- Evening - Abend
(40, 40, 1), -- Name - Name
(41, 41, 1), -- Friend - Freund
(42, 42, 1), -- Family - Familie
(43, 43, 1), -- Parents - Eltern
(44, 44, 1), -- Brother - Bruder
(45, 45, 1); -- Sister - Schwester

-- Assign random levels (1-3) for John Doe (UID 3) for the new vocabulary
INSERT INTO Level (VID, UID, Level) VALUES 
(16, 3, 0),
(17, 3, 0),
(18, 3, 0),
(19, 3, 0),
(20, 3, 1),
(21, 3, 1),
(22, 3, 1),
(23, 3, 1),
(24, 3, 1),
(25, 3, 1),
(26, 3, 1),
(27, 3, 1),
(28, 3, 1),
(29, 3, 2),
(30, 3, 2),
(31, 3, 2),
(32, 3, 2),
(33, 3, 2),
(34, 3, 2),
(35, 3, 2),
(36, 3, 2),
(37, 3, 2),
(38, 3, 2),
(39, 3, 3),
(40, 3, 3),
(41, 3, 3),
(42, 3, 3),
(43, 3, 3),
(44, 3, 3),
(45, 3, 3);
-- Insert test data into User_Stats table
INSERT INTO User_Stats (UID, Lessons_Completed, Words_Learned) VALUES 
(1, 5, 10), -- Max Mustermann has completed 5 lessons and learned 10 words
(2, 3, 8), -- Erika Musterfrau has completed 3 lessons and learned 8 words
(3, 4, 12), -- John Doe has completed 4 lessons and learned 12 words
(4, 2, 6); -- Jane Smith has completed 2 lessons and learned 6 words


Insert into Lesson (BID, UID) values
(1, 1), -- Max Mustermann completed lesson 1
(2, 2), -- Erika Musterfrau completed lesson 2
(3, 3); -- John Doe completed lesson 3

-- Insert test data into FehlerStatistik table
INSERT INTO FehlerStatistik (UID, VID, FehlerAnzahl) VALUES
(1, 1, 2),
(1, 5, 1),
(2, 2, 3),
(2, 6, 0),
(3, 3, 4),
(3, 7, 2),
(3, 20, 1),
(4, 4, 0),
(4, 8, 2),
(4, 12, 1);



Insert Into Einheit (Thema, Beschreibung) VALUES
('Family', 'Vocabulary related to family and relationships'),
('Nature', 'Vocabulary related to nature and the environment');

-- === EINHEIT 2: Travel ===
-- Deutsch_Vocab
INSERT INTO Deutsch_Vocab (Wort, Audio, Bild) VALUES
('Flughafen','flughafen.mp3','flughafen.jpg'),
('Bahnhof','bahnhof.mp3','bahnhof.jpg'),
('Bus','bus.mp3','bus.jpg'),
('Taxi','taxi.mp3','taxi.jpg'),
('Fahrrad','fahrrad.mp3','fahrrad.jpg'),
('Straßenbahn','strassenbahn.mp3','strassenbahn.jpg'),
('Fahrkarte','fahrkarte.mp3','fahrkarte.jpg'),
('Karte','karte.mp3','karte.jpg'),
('Reise','reise.mp3','reise.jpg'),
('Koffer','koffer.mp3','koffer.jpg'),
('Pass','pass.mp3','pass.jpg'),
('Visum','visum.mp3','visum.jpg'),
('Hotel','hotel.mp3','hotel.jpg'),
('Zimmer','zimmer.mp3','zimmer.jpg'),
('Schlüssel','schluessel.mp3','schluessel.jpg'),
('Rezeption','rezeption.mp3','rezeption.jpg'),
('Flugzeug','flugzeug.mp3','flugzeug.jpg'),
('Abfahrt','abfahrt.mp3','abfahrt.jpg'),
('Ankunft','ankunft.mp3','ankunft.jpg'),
('Bahnsteig','bahnsteig.mp3','bahnsteig.jpg'),
('Fahrplan','fahrplan.mp3','fahrplan.jpg'),
('Gepäck','gepaeck.mp3','gepaeck.jpg'),
('Ticket','ticket.mp3','ticket.jpg'),
('Abflug','abflug.mp3','abflug.jpg'),
('Anreise','anreise.mp3','anreise.jpg'),
('Abreise','abreise.mp3','abreise.jpg'),
('Buchung','buchung.mp3','buchung.jpg'),
('Reservierung','reservierung.mp3','reservierung.jpg'),
('Zug','zug.mp3','zug.jpg'),
('Fähre','faehre.mp3','faehre.jpg');

-- Englisch_Vocab
INSERT INTO Englisch_Vocab (Wort, Audio, Bild) VALUES
('Airport','airport.mp3','airport.jpg'),
('Train station','trainstation.mp3','trainstation.jpg'),
('Bus','bus.mp3','bus.jpg'),
('Taxi','taxi.mp3','taxi.jpg'),
('Bicycle','bicycle.mp3','bicycle.jpg'),
('Tram','tram.mp3','tram.jpg'),
('Ticket','ticket.mp3','ticket.jpg'),
('Map','map.mp3','map.jpg'),
('Trip','trip.mp3','trip.jpg'),
('Suitcase','suitcase.mp3','suitcase.jpg'),
('Passport','passport.mp3','passport.jpg'),
('Visa','visa.mp3','visa.jpg'),
('Hotel','hotel.mp3','hotel.jpg'),
('Room','room.mp3','room.jpg'),
('Key','key.mp3','key.jpg'),
('Reception','reception.mp3','reception.jpg'),
('Airplane','airplane.mp3','airplane.jpg'),
('Departure','departure.mp3','departure.jpg'),
('Arrival','arrival.mp3','arrival.jpg'),
('Platform','platform.mp3','platform.jpg'),
('Timetable','timetable.mp3','timetable.jpg'),
('Luggage','luggage.mp3','luggage.jpg'),
('Ticket','ticket.mp3','ticket.jpg'),
('Takeoff','takeoff.mp3','takeoff.jpg'),
('Arrival','arrival.mp3','arrival.jpg'),
('Departure','departure.mp3','departure.jpg'),
('Booking','booking.mp3','booking.jpg'),
('Reservation','reservation.mp3','reservation.jpg'),
('Train','train.mp3','train.jpg'),
('Ferry','ferry.mp3','ferry.jpg');

-- Vocab (IDs fortlaufend, z.B. ab 46 für DID/EID/VID)
INSERT INTO Vocab (EID, DID, EinID) VALUES
(46,46,2),(47,47,2),(48,48,2),(49,49,2),(50,50,2),(51,51,2),(52,52,2),(53,53,2),(54,54,2),(55,55,2),
(56,56,2),(57,57,2),(58,58,2),(59,59,2),(60,60,2),(61,61,2),(62,62,2),(63,63,2),(64,64,2),(65,65,2),
(66,66,2),(67,67,2),(68,68,2),(69,69,2),(70,70,2),(71,71,2),(72,72,2),(73,73,2),(74,74,2),(75,75,2);

-- === EINHEIT 3: Food ===
INSERT INTO Deutsch_Vocab (Wort, Audio, Bild) VALUES
('Käse','kaese.mp3','kaese.jpg'),
('Wurst','wurst.mp3','wurst.jpg'),
('Fisch','fisch.mp3','fisch.jpg'),
('Fleisch','fleisch.mp3','fleisch.jpg'),
('Obst','obst.mp3','obst.jpg'),
('Gemüse','gemuese.mp3','gemuese.jpg'),
('Salat','salat.mp3','salat.jpg'),
('Kartoffel','kartoffel.mp3','kartoffel.jpg'),
('Tomate','tomate.mp3','tomate.jpg'),
('Gurke','gurke.mp3','gurke.jpg'),
('Zwiebel','zwiebel.mp3','zwiebel.jpg'),
('Knoblauch','knoblauch.mp3','knoblauch.jpg'),
('Ei','ei.mp3','ei.jpg'),
('Butter','butter.mp3','butter.jpg'),
('Öl','oel.mp3','oel.jpg'),
('Reis','reis.mp3','reis.jpg'),
('Nudel','nudel.mp3','nudel.jpg'),
('Suppe','suppe.mp3','suppe.jpg'),
('Kuchen','kuchen.mp3','kuchen.jpg'),
('Torte','torte.mp3','torte.jpg'),
('Zucker','zucker.mp3','zucker.jpg'),
('Salz','salz.mp3','salz.jpg'),
('Pfeffer','pfeffer.mp3','pfeffer.jpg'),
('Löffel','loeffel.mp3','loeffel.jpg'),
('Gabel','gabel.mp3','gabel.jpg'),
('Messer','messer.mp3','messer.jpg'),
('Teller','teller.mp3','teller.jpg'),
('Glas','glas.mp3','glas.jpg'),
('Tasse','tasse.mp3','tasse.jpg'),
('Becher','becher.mp3','becher.jpg');

INSERT INTO Englisch_Vocab (Wort, Audio, Bild) VALUES
('Cheese','cheese.mp3','cheese.jpg'),
('Sausage','sausage.mp3','sausage.jpg'),
('Fish','fish.mp3','fish.jpg'),
('Meat','meat.mp3','meat.jpg'),
('Fruit','fruit.mp3','fruit.jpg'),
('Vegetable','vegetable.mp3','vegetable.jpg'),
('Salad','salad.mp3','salad.jpg'),
('Potato','potato.mp3','potato.jpg'),
('Tomato','tomato.mp3','tomato.jpg'),
('Cucumber','cucumber.mp3','cucumber.jpg'),
('Onion','onion.mp3','onion.jpg'),
('Garlic','garlic.mp3','garlic.jpg'),
('Egg','egg.mp3','egg.jpg'),
('Butter','butter.mp3','butter.jpg'),
('Oil','oil.mp3','oil.jpg'),
('Rice','rice.mp3','rice.jpg'),
('Noodle','noodle.mp3','noodle.jpg'),
('Soup','soup.mp3','soup.jpg'),
('Cake','cake.mp3','cake.jpg'),
('Pie','pie.mp3','pie.jpg'),
('Sugar','sugar.mp3','sugar.jpg'),
('Salt','salt.mp3','salt.jpg'),
('Pepper','pepper.mp3','pepper.jpg'),
('Spoon','spoon.mp3','spoon.jpg'),
('Fork','fork.mp3','fork.jpg'),
('Knife','knife.mp3','knife.jpg'),
('Plate','plate.mp3','plate.jpg'),
('Glass','glass.mp3','glass.jpg'),
('Cup','cup.mp3','cup.jpg'),
('Mug','mug.mp3','mug.jpg');

INSERT INTO Vocab (EID, DID, EinID) VALUES
(76,76,3),(77,77,3),(78,78,3),(79,79,3),(80,80,3),(81,81,3),(82,82,3),(83,83,3),(84,84,3),(85,85,3),
(86,86,3),(87,87,3),(88,88,3),(89,89,3),(90,90,3),(91,91,3),(92,92,3),(93,93,3),(94,94,3),(95,95,3),
(96,96,3),(97,97,3),(98,98,3),(99,99,3),(100,100,3),(101,101,3),(102,102,3),(103,103,3),(104,104,3),(105,105,3);

-- === EINHEIT 4: Work ===
-- (Analog zu oben, 30 Begriffe wie Büro, Chef, Kollege, etc.)
INSERT INTO Deutsch_Vocab (Wort, Audio, Bild) VALUES
('Büro','buero.mp3','buero.jpg'),
('Chef','chef.mp3','chef.jpg'),
('Kollege','kollege.mp3','kollege.jpg'),
('Mitarbeiter','mitarbeiter.mp3','mitarbeiter.jpg'),
('Projekt','projekt.mp3','projekt.jpg'),
('Meeting','meeting.mp3','meeting.jpg'),
('Präsentation','praesentation.mp3','praesentation.jpg'),
('Bericht','bericht.mp3','bericht.jpg'),
('E-Mail','email.mp3','email.jpg'),
('Telefonat','telefonat.mp3','telefonat.jpg'),
('Arbeitszeit','arbeitszeit.mp3','arbeitszeit.jpg'),
('Urlaub','urlaub.mp3','urlaub.jpg'),
('Kündigung','kuendigung.mp3','kuendigung.jpg'),
('Vertrag','vertrag.mp3','vertrag.jpg'),
('Gehalt','gehalt.mp3','gehalt.jpg'),
('Bonus','bonus.mp3','bonus.jpg'),
('Aufgabe','aufgabe.mp3','aufgabe.jpg'),
('Deadline','deadline.mp3','deadline.jpg'),
('Bewerbung','bewerbung.mp3','bewerbung.jpg'),
('Interview','interview.mp3','interview.jpg'),
('Fortbildung','fortbildung.mp3','fortbildung.jpg'),
('Teamarbeit','teamarbeit.mp3','teamarbeit.jpg'),
('Konferenzraum','konferenzraum.mp3','konferenzraum.jpg'),
('Drucker','drucker.mp3','drucker.jpg'),
('Scanner','scanner.mp3','scanner.jpg'),
('Computerarbeitsplatz','computerarbeitsplatz.mp3', 'computerarbeitsplatz.jpg'),
('Notizblock', 'notizblock.mp3', 'notizblock.jpg');

INSERT INTO Englisch_Vocab (Wort, Audio, Bild) VALUES
('Office','office.mp3','office.jpg'),
('Boss','boss.mp3','boss.jpg'),
('Colleague','colleague.mp3','colleague.jpg'),
('Employee','employee.mp3','employee.jpg'),
('Project','project.mp3','project.jpg'),
('Meeting','meeting.mp3','meeting.jpg'),
('Presentation','presentation.mp3','presentation.jpg'),
('Report','report.mp3','report.jpg'),
('Email','email.mp3','email.jpg'),
('Phone call','phonecall.mp3','phonecall.jpg'),
('Working hours','workinghours.mp3','workinghours.jpg'),
('Vacation','vacation.mp3','vacation.jpg'),
('Termination','termination.mp3','termination.jpg'),
('Contract','contract.mp3','contract.jpg'),
('Salary','salary.mp3','salary.jpg'),
('Bonus','bonus.mp3','bonus.jpg'),
('Task','task.mp3','task.jpg'),
('Deadline','deadline.mp3','deadline.jpg'),
('Application','application.mp3','application.jpg'),
('Interview','interview.mp3','interview.jpg'),
('Training course', 'trainingcourse.mp3', 'trainingcourse.jpg'),
('Teamwork', 'teamwork.mp3', 'teamwork.jpg'),
('Conference room', 'conferenceroom.mp3', 'conferenceroom.jpg'),
('Printer', 'printer.mp3', 'printer.jpg'),
('Scanner', 'scanner.mp3', 'scanner.jpg'),
('Computer workstation', 'computerworkstation.mp3', 'computerworkstation.jpg'),
('Notebook', 'notebook.mp3', 'notebook.jpg');

Insert INTO Vocab (EID, DID, EinID) VALUES
(106,106,4),(107,107,4),(108,108,4),(109,109,4),(110,110,4),(111,111,4),(112,112,4),(113,113,4),(114,114,4),(115,115,4),
(116,116,4),(117,117,4),(118,118,4),(119,119,4),(120,120,4),(121,121,4),(122,122,4),(123,123,4),(124,124,4),(125,125,4),
(126,126,4),(127,127,4),(128,128,4),(129,129,4),(130,130,4),(131,131,4),(132,132,4),(133,133,4),(134,134,4),(135,135,4);

-- === EINHEIT 5: Family ===
-- (Analog zu oben, 30 Begriffe wie Mutter, Vater, Sohn, Tochter, etc.)
Insert INTO Deutsch_Vocab (Wort, Audio, Bild) VALUES
('Mutter','mutter.mp3','mutter.jpg'),
('Vater','vater.mp3','vater.jpg'),
('Sohn','sohn.mp3','sohn.jpg'),
('Tochter','tochter.mp3','tochter.jpg'),
('Großmutter','grossmutter.mp3','grossmutter.jpg'),
('Großvater','grossvater.mp3','grossvater.jpg'),
('Onkel','onkel.mp3','onkel.jpg'),
('Tante','tante.mp3','tante.jpg'),
('Cousin','cousin.mp3','cousin.jpg'),
('Cousine','cousine.mp3','cousine.jpg'),
('Neffe','neffe.mp3','neffe.jpg'),
('Nichte','nichte.mp3','nichte.jpg'),
('Schwester','schwester.mp3','schwester.jpg'),
('Bruder','bruder.mp3','bruder.jpg'),
('Schwiegereltern','schwiegereltern.mp3', 'schwiegereltern.jpg'),
('Schwiegermutter', 'schwiegermutter.mp3', 'schwiegermutter.jpg'),
('Schwiegervater', 'schwiegervater.mp3', 'schwiegervater.jpg'),
('Enkel', 'enkel.mp3', 'enkel.jpg'),
('Enkelin', 'enkelin.mp3', 'enkelin.jpg'),
('Familie', 'familie.mp3', 'familie.jpg'),
('Verwandte', 'verwandte.mp3', 'verwandte.jpg');

Insert INTO Englisch_Vocab (Wort, Audio, Bild) VALUES
('Mother','mother.mp3','mother.jpg'),
('Father','father.mp3','father.jpg'),
('Son','son.mp3','son.jpg'),
('Daughter','daughter.mp3','daughter.jpg'),
('Grandmother','grandmother.mp3','grandmother.jpg'),
('Grandfather','grandfather.mp3','grandfather.jpg'),
('Uncle','uncle.mp3','uncle.jpg'),
('Aunt','aunt.mp3','aunt.jpg'),
('Cousin','cousin.mp3','cousin.jpg'),
('Cousin','cousin.mp3','cousin.jpg'),
('Nephew','nephew.mp3','nephew.jpg'),
('Niece','niece.mp3','niece.jpg'),
('Sister','sister.mp3','sister.jpg'),
('Brother','brother.mp3','brother.jpg'),
('Parents-in-law', 'parentsinlaw.mp3', 'parentsinlaw.jpg'),
('Mother-in-law', 'motherinlaw.mp3', 'motherinlaw.jpg'),
('Father-in-law', 'fatherinlaw.mp3', 'fatherinlaw.jpg'),
('Grandson', 'grandson.mp3', 'grandson.jpg'),
('Granddaughter', 'granddaughter.mp3', 'granddaughter.jpg'),
('Family', 'family.mp3', 'family.jpg'),
('Relatives', 'relatives.mp3', 'relatives.jpg');

Insert INTO Vocab (EID, DID, EinID) VALUES
(136,136,5),(137,137,5),(138,138,5),(139,139,5),(140,140,5),(141,141,5),(142,142,5),(143,143,5),(144,144,5),(145,145,5),
(146,146,5),(147,147,5),(148,148,5),(149,149,5),(150,150,5),(151,151,5),(152,152,5),(153,153,5),(154,154,5),(155,155,5),
(156,156,5),(157,157,5),(158,158,5),(159,159,5),(160,160,5),(161,161,5),(162,162,5),(163,163,5),(164,164,5),(165,165,5);

-- === EINHEIT 6: Nature ===
-- (Analog zu oben, 30 Begriffe wie Baum, Blume, Fluss, Berg, etc.)

Insert Into Deutsch_Vocab (Wort, Audio, Bild) VALUES
('Baum','baum.mp3','baum.jpg'),
('Blume','blume.mp3','blume.jpg'),
('Fluss','fluss.mp3','fluss.jpg'),
('Berg','berg.mp3','berg.jpg'),
('See','see.mp3','see.jpg'),
('Wald','wald.mp3','wald.jpg'),
('Himmel','himmel.mp3','himmel.jpg'),
('Sonne','sonne.mp3','sonne.jpg'),
('Mond','mond.mp3','mond.jpg'),
('Stern','stern.mp3','stern.jpg'),
('Wolke','wolke.mp3','wolke.jpg'),
('Regen','regen.mp3','regen.jpg'),
('Schnee','schnee.mp3','schnee.jpg'),
('Wind','wind.mp3','wind.jpg'),
('Luft','luft.mp3','luft.jpg'),
('Erde','erde.mp3','erde.jpg'),
('Stein','stein.mp3','stein.jpg'),
('Sand','sand.mp3','sand.jpg'),
('Gras','gras.mp3','gras.jpg'),
('Tierwelt', 'tierwelt.mp3', 'tierwelt.jpg');

Insert Into Englisch_Vocab (Wort, Audio, Bild) VALUES
('Tree','tree.mp3','tree.jpg'),
('Flower','flower.mp3','flower.jpg'),
('River','river.mp3','river.jpg'),
('Mountain','mountain.mp3','mountain.jpg'),
('Lake','lake.mp3','lake.jpg'),
('Forest','forest.mp3','forest.jpg'),
('Sky','sky.mp3','sky.jpg'),
('Sun','sun.mp3','sun.jpg'),
('Moon','moon.mp3','moon.jpg'),
('Star','star.mp3','star.jpg'),
('Cloud','cloud.mp3','cloud.jpg'),
('Rain','rain.mp3','rain.jpg'),
('Snow','snow.mp3','snow.jpg'),
('Wind','wind.mp3','wind.jpg'),
('Air','air.mp3','air.jpg'),
('Earth','earth.mp3','earth.jpg'),
('Stone', 'stone.mp3', 'stone.jpg'),
('Sand', 'sand.mp3', 'sand.jpg'),
('Grass', 'grass.mp3', 'grass.jpg'),
('Wildlife', 'wildlife.mp3', 'wildlife.jpg');

Insert Into Vocab (EID, DID, EinID) VALUES
(166,166,6),(167,167,6),(168,168,6),(169,169,6),(170,170,6),(171,171,6),(172,172,6),(173,173,6),(174,174,6),(175,175,6),
(176,176,6),(177,177,6),(178,178,6),(179,179,6),(180,180,6),(181,181,6),(182,182,6),(183,183,6),(184,184,6),(185,185,6),
(186,186,6),(187,187,6),(188,188,6),(189,189,6),(190,190,6),(191,191,6),(192,192,6),(193,193,6),(194,194,6),(195,195,6);
