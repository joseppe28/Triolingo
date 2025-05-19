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
CREATE TABLE Vocab(
    VID INT PriMARY KEY AUTO_INCREMENT,
    EID INT NOT NULL,
    DID INT NOT NULL,
    EinID INT NOT NULL,
    FOREIGN KEY (EID) REFERENCES Englisch_Vocab(EID),
    FOREIGN KEY (DID) REFERENCES Deutsch_Vocab(DID),
    FOREIGN KEY (EinID) REFERENCES Einheit(EinID)
);
CREATE TABLE Einheit(
    EinID INT PriMARY KEY AUTO_INCREMENT,
    Thema VARCHAR(255) NOT NULL,
    Beschreibung VARCHAR(255) NOT NULL
);

CREATE TABLE Exercise(
    ExID INT PriMARY KEY AUTO_INCREMENT,
    EinID INT NOT NULL,
    Typ VARCHAR(255) NOT NULL,
    FOREIGN KEY (EinID) REFERENCES Einheit(EinID)
);
CREATE TABLE Level(
    LID INT PriMARY KEY AUTO_INCREMENT,
    VID INT NOT NULL,
    UID INT NOT NULL,
    Level INT NOT NULL,
    FOREIGN KEY (VID) REFERENCES Vocab(VID),
    FOREIGN KEY (UID) REFERENCES User(UID)
);

CREATE TABLE User(
    UID INT PriMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL,
    Passwort VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL
);

Create Table User_Stats(
    UID INT primary key,
    Lessons_Completed INT,
    Words_Learned INT, 
    FOREIGN KEY (UID) REFERENCES User(UID)
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
