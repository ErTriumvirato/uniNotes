DROP DATABASE IF EXISTS uniNotes;
CREATE DATABASE uniNotes;
USE uniNotes;

CREATE TABLE utenti (
    idutente INTEGER PRIMARY KEY AUTO_INCREMENT,
    ruolo INTEGER NOT NULL,
    username VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE ssd (
    idssd INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) UNIQUE NOT NULL,
    descrizione TEXT NOT NULL
);

CREATE TABLE corsi (
    idcorso INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) UNIQUE NOT NULL,
    descrizione TEXT NOT NULL,
    idssd INTEGER,
    FOREIGN KEY (idssd) REFERENCES ssd(idssd)
);

CREATE TABLE articoli (
    idarticolo INTEGER PRIMARY KEY AUTO_INCREMENT,
    titolo VARCHAR(100) NOT NULL,
    contenuto TEXT NOT NULL,
    data_pubblicazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    numero_visualizzazioni INTEGER DEFAULT 0,
    idutente INTEGER,
    idcorso INTEGER,
    FOREIGN KEY (idutente) REFERENCES utenti(idutente),
    FOREIGN KEY (idcorso) REFERENCES corsi(idcorso)
);

CREATE TABLE iscrizioni (
    idiscrizione INTEGER PRIMARY KEY AUTO_INCREMENT,
    idutente INTEGER,
    idcorso INTEGER,
    FOREIGN KEY (idutente) REFERENCES utenti(idutente),
    FOREIGN KEY (idcorso) REFERENCES corsi(idcorso)
);

CREATE TABLE recensioni (
    idrecensione INTEGER PRIMARY KEY AUTO_INCREMENT,
    valutazione INTEGER NOT NULL,
    commento TEXT,
    idarticolo INTEGER,
    idutente INTEGER,
    FOREIGN KEY (idarticolo) REFERENCES articoli(idarticolo),
    FOREIGN KEY (idutente) REFERENCES utenti(idutente)
);

GRANT ALL PRIVILEGES ON uniNotes.* TO 'user'@'localhost' IDENTIFIED BY 'user_password';