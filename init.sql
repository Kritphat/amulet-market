-- MedBoard internal portal database
CREATE DATABASE IF NOT EXISTS medboard;
USE medboard;

-- Staff portal accounts (Stage 1 SQLi targets this table ONLY - no admin here)
CREATE TABLE staff (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(64) NOT NULL,   -- md5(password)
    fullname VARCHAR(100),
    role     VARCHAR(20) DEFAULT 'staff'
);

-- First row is intentionally a low-priv staff account:
-- any `' OR 1=1 ...` injection returns THIS row, never admin.
INSERT INTO staff (username, password, fullname, role) VALUES
('n.ayinomoto', MD5('Tr33house!'), 'Ayinomoto N.', 'staff'),
('p.araya',   MD5('qwerty88'),   'Araya P.',   'staff'),
('reception', MD5('frontdesk1'), 'Front Desk', 'staff');

-- NOTE: there is no admin table. The Administrator Console (/admin/) is
-- protected by Apache HTTP Basic Auth, with credentials in the file
-- private/.htpasswd (APR1 hash). You must recover and crack that file to log in.
