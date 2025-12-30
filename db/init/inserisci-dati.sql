INSERT INTO utenti (ruolo, username, password) VALUES
(1, 'admin', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si'),
(2, 'studente1', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si'),
(2, 'studente2', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si'),
(2, 'studente3', '$2y$10$gYT.l6keERbhJzw/LOuVXOk4BdrLkL15fbFGMr8AiUpBIfD8lT7si');

INSERT INTO ssd (nome, descrizione) VALUES
('MAT/01', 'Matematica'),
('FIS/01', 'Fisica'),
('INF/01', 'Informatica');

INSERT INTO corsi (nome, descrizione, idssd) VALUES
('Analisi Matematica', 'Corso di Analisi Matematica di base', 1),
('Fisica 1', 'Corso di Fisica per studenti di ingegneria', 2),
('Programmazione', 'Introduzione alla programmazione in C', 3);

INSERT INTO articoli (titolo, contenuto, idutente, idcorso, numero_visualizzazioni, data_pubblicazione) VALUES
('Appunti di Analisi Matematica', 'Questi sono gli appunti di Analisi Matematica...', 2, 1, 10, '2024-10-15 09:30:00'),
('Esame primo appello', 'hahahahhh', 3, 1, 5, '2024-10-20 11:15:00'),
('Esame secondo appello', 'hahahahhh', 3, 1, 5, '2024-10-20 11:15:00'),
('Esercizi di Fisica', 'Ecco alcuni esercizi risolti di Fisica Generale...', 3, 2, 15,'2024-11-02 14:00:00'),
('Guida alla Programmazione in C', 'In questa guida vedremo le basi della programmazione in C...', 2, 3, 20, '2024-12-01 18:45:00');


INSERT INTO iscrizioni (idutente, idcorso) VALUES
(2, 1),
(2, 3),
(3, 2);

INSERT INTO recensioni (valutazione, idarticolo, idutente) VALUES
(5, 1, 3),
(4, 2, 2),
(5, 3, 3);
