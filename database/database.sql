CREATE DATABASE IF NOT EXISTS monitoring_network
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE monitoring_network;


/*
==================================================
TABLE: switches
==================================================
*/

CREATE TABLE IF NOT EXISTS switches (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    hostname VARCHAR(100) NULL,

    ip_address VARCHAR(45) NULL,

    location VARCHAR(255) NULL,

    latitude DECIMAL(10,7) NULL,

    longitude DECIMAL(10,7) NULL,

    status ENUM(
        'online',
        'offline',
        'maintenance'
    ) DEFAULT 'offline',

    description TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);


/*
==================================================
TABLE: clients
==================================================
*/

CREATE TABLE IF NOT EXISTS clients (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    hostname VARCHAR(100) NULL,

    ip_address VARCHAR(45) NULL,

    mac_address VARCHAR(17) NULL,

    location VARCHAR(255) NULL,

    latitude DECIMAL(10,7) NULL,

    longitude DECIMAL(10,7) NULL,

    status ENUM(
        'online',
        'offline',
        'maintenance'
    ) DEFAULT 'offline',

    description TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);


/*
==================================================
TABLE: connections
==================================================
*/

CREATE TABLE IF NOT EXISTS connections (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    switch_id INT UNSIGNED NOT NULL,

    client_id INT UNSIGNED NOT NULL,

    switch_port VARCHAR(50) NULL,

    client_port VARCHAR(50) NULL,

    connection_type VARCHAR(50)
        DEFAULT 'ethernet',

    status ENUM(
        'up',
        'down',
        'unknown'
    ) DEFAULT 'unknown',

    description TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_connection_switch
        FOREIGN KEY (switch_id)
        REFERENCES switches(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_connection_client
        FOREIGN KEY (client_id)
        REFERENCES clients(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);


/*
==================================================
SAMPLE SWITCH
==================================================
*/

INSERT INTO switches
(
    name,
    hostname,
    ip_address,
    location,
    latitude,
    longitude,
    status,
    description
)
VALUES
(
    'SW-CORE-01',
    'core-switch-01',
    '192.168.1.1',
    'Server Room',
    -6.2000000,
    106.8166667,
    'online',
    'Core Switch'
);


/*
==================================================
SAMPLE CLIENT
==================================================
*/

INSERT INTO clients
(
    name,
    hostname,
    ip_address,
    mac_address,
    location,
    latitude,
    longitude,
    status,
    description
)
VALUES
(
    'CLIENT-001',
    'client-001',
    '192.168.1.101',
    '00:11:22:33:44:55',
    'Office 1',
    -6.2010000,
    106.8170000,
    'online',
    'Client contoh'
);


/*
==================================================
SAMPLE CONNECTION
==================================================
*/

INSERT INTO connections
(
    switch_id,
    client_id,
    switch_port,
    client_port,
    connection_type,
    status,
    description
)
VALUES
(
    1,
    1,
    'Gi0/1',
    'eth0',
    'ethernet',
    'up',
    'Koneksi contoh'
);