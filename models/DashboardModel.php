<?php

class DashboardModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil semua switch yang memiliki koordinat
     */
    public function getSwitchesForMap(): array
    {
        $sql = "
            SELECT
                id,
                name,
                hostname,
                ip_address,
                location,
                latitude,
                longitude,
                status,
                description
            FROM switches
            WHERE latitude IS NOT NULL
              AND longitude IS NOT NULL
            ORDER BY name ASC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua client yang memiliki koordinat
     */
    public function getClientsForMap(): array
    {
        $sql = "
            SELECT
                id,
                name,
                hostname,
                ip_address,
                mac_address,
                location,
                latitude,
                longitude,
                status,
                description
            FROM clients
            WHERE latitude IS NOT NULL
              AND longitude IS NOT NULL
            ORDER BY name ASC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statistik switch
     */
    public function getSwitchStatistics(): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total,
                SUM(status = 'online') AS online,
                SUM(status = 'offline') AS offline,
                SUM(status = 'maintenance') AS maintenance
            FROM switches
        ";

        return $this->pdo
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Statistik client
     */
    public function getClientStatistics(): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total,
                SUM(status = 'online') AS online,
                SUM(status = 'offline') AS offline,
                SUM(status = 'maintenance') AS maintenance
            FROM clients
        ";

        return $this->pdo
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Mengambil seluruh koneksi Switch -> Client
     * beserta koordinat masing-masing perangkat.
     */
    public function getConnectionsForMap(): array
    {
    $sql = "
        SELECT
            c.id,

            c.switch_id,
            c.client_id,

            c.switch_port,
            c.client_port,

            c.status,
            c.description,

            s.name AS switch_name,
            s.hostname AS switch_hostname,
            s.ip_address AS switch_ip,

            s.latitude AS switch_latitude,
            s.longitude AS switch_longitude,

            cl.name AS client_name,
            cl.hostname AS client_hostname,
            cl.ip_address AS client_ip,
            cl.mac_address AS client_mac,

            cl.latitude AS client_latitude,
            cl.longitude AS client_longitude

        FROM connections c

        INNER JOIN switches s
            ON s.id = c.switch_id

        INNER JOIN clients cl
            ON cl.id = c.client_id

        WHERE
            s.latitude IS NOT NULL
            AND s.longitude IS NOT NULL

            AND cl.latitude IS NOT NULL
            AND cl.longitude IS NOT NULL

        ORDER BY
            s.name ASC,
            c.switch_port ASC
    ";

    return $this->pdo
        ->query($sql)
        ->fetchAll(PDO::FETCH_ASSOC);
}
}
