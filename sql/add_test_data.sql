INSERT INTO Loggedin (name, password) VALUES ('Antero', 'oretna');
INSERT INTO Loggedin (name, password) VALUES ('Pekka', 'Juhani');
INSERT INTO Thread (time, topic, starter) VALUES (SELECT NOW(), 'Harvinaisia pepejä 3€/kpl', 1); 

