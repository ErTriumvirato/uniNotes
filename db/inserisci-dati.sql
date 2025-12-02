INSERT INTO utenti (ruolo, username, password) VALUES
(1, 'admin', 'adminpass'),
(2, 'studente1', 'studpass1'),
(2, 'studente2', 'studpass2'),
(2, 'studente3', 'studpass3');

INSERT INTO ssd (nome, descrizione) VALUES
('MAT/01', 'Matematica'),
('FIS/01', 'Fisica'),
('INF/01', 'Informatica');

INSERT INTO corsi (nome, descrizione, idssd) VALUES
('Analisi Matematica', 'Corso di Analisi Matematica di base', 1),
('Fisica 1', 'Corso di Fisica per studenti di ingegneria', 2),
('Programmazione', 'Introduzione alla programmazione in C', 3);

INSERT INTO articoli (titolo, contenuto, idutente, idcorso, numero_visualizzazioni) VALUES
('Appunti di Analisi Matematica', 'Questi sono gli appunti di Analisi Matematica...', 2, 1, 10),
('Esercizi di Fisica', 'Ecco alcuni esercizi risolti di Fisica Generale...', 3, 2, 15),
('Guida alla Programmazione in C', 'In questa guida vedremo le basi della programmazione in C...', 2, 3, 20);

INSERT INTO iscrizioni (idutente, idcorso) VALUES
(2, 1),
(2, 3),
(3, 2);

INSERT INTO recensioni (valutazione, commento, idarticolo, idutente) VALUES
(5, 'Ottimi appunti, molto chiari!', 1, 3),
(4, 'Gli esercizi sono utili per la preparazione.', 2, 2),
(5, 'La guida è stata fondamentale per imparare C.', 3, 3);