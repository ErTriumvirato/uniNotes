DROP DATABASE IF EXISTS uniNotes;
CREATE DATABASE uniNotes;
USE uniNotes;

CREATE TABLE utenti (
    idutente INTEGER PRIMARY KEY AUTO_INCREMENT,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
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
    descrizione MEDIUMTEXT NOT NULL,
    idssd INTEGER NOT NULL,
    FOREIGN KEY (idssd) REFERENCES ssd(idssd)
);

CREATE TABLE articoli (
    idarticolo INTEGER PRIMARY KEY AUTO_INCREMENT,
    titolo VARCHAR(100) NOT NULL,
    contenuto TEXT NOT NULL,
    data_pubblicazione DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    numero_visualizzazioni INTEGER DEFAULT 0 NOT NULL,
    approvato BOOLEAN DEFAULT FALSE NOT NULL,
    idutente INTEGER NOT NULL,
    idcorso INTEGER NOT NULL,
    FOREIGN KEY (idutente) REFERENCES utenti(idutente),
    FOREIGN KEY (idcorso) REFERENCES corsi(idcorso)
);

CREATE TABLE iscrizioni (
    idiscrizione INTEGER PRIMARY KEY AUTO_INCREMENT,
    idutente INTEGER NOT NULL,
    idcorso INTEGER NOT NULL,
    UNIQUE (idutente, idcorso),
    FOREIGN KEY (idutente) REFERENCES utenti(idutente),
    FOREIGN KEY (idcorso) REFERENCES corsi(idcorso)
);

CREATE TABLE recensioni (
    idrecensione INTEGER PRIMARY KEY AUTO_INCREMENT,
    valutazione INTEGER NOT NULL,
    idarticolo INTEGER NOT NULL,
    idutente INTEGER NOT NULL,
    FOREIGN KEY (idarticolo) REFERENCES articoli(idarticolo),
    FOREIGN KEY (idutente) REFERENCES utenti(idutente)
);

GRANT ALL PRIVILEGES ON uniNotes.* TO 'user'@'localhost' IDENTIFIED BY 'user_password';