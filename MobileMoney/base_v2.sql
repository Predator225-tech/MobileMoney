-- ========================================================
-- 1. CONFIGURATION OPÉRATEUR (Version 1 & 2)
-- ========================================================

-- Préfixes de votre propre réseau (Interne)
CREATE TABLE IF NOT EXISTS prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE
);

-- Autres opérateurs (ex: Orange, Telma, Airtel)
CREATE TABLE IF NOT EXISTS autres_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,       -- ex: Orange, Telma
    prefixe TEXT NOT NULL    -- ex: 034, 032
);

-- Commissions additionnelles pour les transferts vers l'extérieur
CREATE TABLE IF NOT EXISTS commissions_operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_autre_operateur INTEGER,
    pourcentage REAL NOT NULL, 
    FOREIGN KEY (id_autre_operateur) REFERENCES autres_operateurs(id)
);

-- ========================================================
-- 2. GESTION DES OPÉRATIONS & FRAIS
-- ========================================================

CREATE TABLE IF NOT EXISTS types_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL -- Dépôt, Retrait, Transfert
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

-- ========================================================
-- 3. CLIENTS & TRANSACTIONS
-- ========================================================

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
    commission_supp REAL DEFAULT 0, -- Pour la V2 (commission externe)
    numero_destination TEXT,
    id_autre_operateur INTEGER DEFAULT NULL, -- NULL = Interne
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(id_client) REFERENCES clients(id),
    FOREIGN KEY(id_type_operation) REFERENCES types_operation(id),
    FOREIGN KEY(id_autre_operateur) REFERENCES autres_operateurs(id)
);

-- ========================================================
-- 4. DONNÉES INITIALES
-- ========================================================

-- Préfixes internes
INSERT OR IGNORE INTO prefixes (code) VALUES ('033'), ('037');

-- Types d'opérations
INSERT OR IGNORE INTO types_operation (nom) VALUES ('Dépôt'), ('Retrait'), ('Transfert');

-- Exemple d'autres opérateurs
INSERT OR IGNORE INTO autres_operateurs (nom, prefixe) VALUES ('Orange', '032'), ('Telma', '034');
INSERT OR IGNORE INTO commissions_operateurs (id_autre_operateur, pourcentage) VALUES (1, 2.0), (2, 2.5);