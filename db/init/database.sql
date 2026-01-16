DROP DATABASE IF EXISTS uniNotes;
CREATE DATABASE uniNotes;
USE uniNotes;

CREATE TABLE utenti (
    idutente INTEGER PRIMARY KEY AUTO_INCREMENT,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    username VARCHAR(30) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
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
    FOREIGN KEY (idssd) REFERENCES ssd(idssd) ON DELETE CASCADE
);

CREATE TABLE appunti (
    idappunto INTEGER PRIMARY KEY AUTO_INCREMENT,
    titolo VARCHAR(100) NOT NULL,
    contenuto TEXT NOT NULL,
    data_pubblicazione DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    numero_visualizzazioni INTEGER DEFAULT 0 NOT NULL,
    approvato BOOLEAN DEFAULT FALSE NOT NULL,
    motivo_rifiuto TEXT DEFAULT NULL,
    idutente INTEGER NOT NULL,
    idcorso INTEGER NOT NULL,
    FOREIGN KEY (idutente) REFERENCES utenti(idutente) ON DELETE CASCADE,
    FOREIGN KEY (idcorso) REFERENCES corsi(idcorso) ON DELETE CASCADE
);

CREATE TABLE iscrizioni (
    idiscrizione INTEGER PRIMARY KEY AUTO_INCREMENT,
    idutente INTEGER NOT NULL,
    idcorso INTEGER NOT NULL,
    UNIQUE (idutente, idcorso),
    FOREIGN KEY (idutente) REFERENCES utenti(idutente) ON DELETE CASCADE,
    FOREIGN KEY (idcorso) REFERENCES corsi(idcorso) ON DELETE CASCADE
);

CREATE TABLE recensioni (
    idrecensione INTEGER PRIMARY KEY AUTO_INCREMENT,
    valutazione INTEGER NOT NULL,
    idappunto INTEGER NOT NULL,
    idutente INTEGER NOT NULL,
    FOREIGN KEY (idappunto) REFERENCES appunti(idappunto) ON DELETE CASCADE,
    FOREIGN KEY (idutente) REFERENCES utenti(idutente) ON DELETE CASCADE
);

GRANT ALL PRIVILEGES ON uniNotes.* TO 'user'@'localhost' IDENTIFIED BY 'user_password'; /*gestisce sicurezza*/