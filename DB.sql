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