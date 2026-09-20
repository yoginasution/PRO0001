<?php

class ConnectionModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil seluruh koneksi
     * beserta informasi Switch dan Client.
     */
    public function getAll(): array
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

                c.created_at,
                c.updated_at,

                s.name AS switch_name,
                s.hostname AS switch_hostname,
                s.ip_address AS switch_ip,

                cl.name AS client_name,
                cl.hostname AS client_hostname,
                cl.ip_address AS client_ip,
                cl.mac_address AS client_mac

            FROM connections c

            INNER JOIN switches s
                ON s.id = c.switch_id

            INNER JOIN clients cl
                ON cl.id = c.client_id

            ORDER BY
                s.name ASC,
                c.switch_port ASC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Mengambil satu koneksi berdasarkan ID.
     */
    public function getById(int $id): ?array
    {
        $sql = "
            SELECT
                c.*,

                s.name AS switch_name,
                s.hostname AS switch_hostname,

                cl.name AS client_name,
                cl.hostname AS client_hostname

            FROM connections c

            INNER JOIN switches s
                ON s.id = c.switch_id

            INNER JOIN clients cl
                ON cl.id = c.client_id

            WHERE c.id = :id

            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }


    /**
     * Membuat koneksi baru.
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO connections
            (
                switch_id,
                client_id,
                switch_port,
                client_port,
                status,
                description
            )
            VALUES
            (
                :switch_id,
                :client_id,
                :switch_port,
                :client_port,
                :status,
                :description
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':switch_id'   => $data['switch_id'],
            ':client_id'   => $data['client_id'],
            ':switch_port' => $data['switch_port'],
            ':client_port' => $data['client_port'],
            ':status'      => $data['status'],
            ':description' => $data['description']
        ]);
    }


    /**
     * Update koneksi.
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE connections
            SET
                switch_id = :switch_id,
                client_id = :client_id,
                switch_port = :switch_port,
                client_port = :client_port,
                status = :status,
                description = :description
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id'          => $id,
            ':switch_id'   => $data['switch_id'],
            ':client_id'   => $data['client_id'],
            ':switch_port' => $data['switch_port'],
            ':client_port' => $data['client_port'],
            ':status'      => $data['status'],
            ':description' => $data['description']
        ]);
    }


    /**
     * Hapus koneksi.
     */
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM connections
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }


    /**
     * Mengambil daftar Switch.
     */
    public function getSwitches(): array
    {
        $sql = "
            SELECT
                id,
                name,
                hostname,
                ip_address,
                status
            FROM switches
            ORDER BY name ASC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Mengambil daftar Client.
     */
    public function getClients(): array
    {
        $sql = "
            SELECT
                id,
                name,
                hostname,
                ip_address,
                mac_address,
                status
            FROM clients
            ORDER BY name ASC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}