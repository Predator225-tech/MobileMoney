-- Création de la base de données SQLite
CREATE TABLE IF NOT EXISTS prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS types_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL -- ex: Dépôt, Retrait, Transfert
);

CREATE TABLE IF NOT EXISTS tranches_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER,
    montant_min REAL,
    montant_max REAL,
    frais_fixe REAL,
    frais_pourcentage REAL,
    FOREIGN KEY(id_type_operation) REFERENCES types_operation(id)
);

CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone TEXT NOT NULL UNIQUE,
    solde REAL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client INTEGER,
    id_type_operation INTEGER,
    montant REAL,
    frais REAL,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_client) REFERENCES clients(id),
    FOREIGN KEY(id_type_operation) REFERENCES types_operation(id)
);

-- Données initiales par défaut
INSERT INTO prefixes (code) VALUES ('033'), ('037');
INSERT INTO types_operation (nom) VALUES ('Dépôt'), ('Retrait'), ('Transfert');