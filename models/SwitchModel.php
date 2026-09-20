<?php

class SwitchModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /**
     * Mengambil semua switch
     */
    public function getAll(): array
    {
        $sql = "
            SELECT *
            FROM switches
            ORDER BY id DESC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    /**
     * Mengambil switch berdasarkan ID
     */
    public function getById(int $id): ?array
    {
        $sql = "
            SELECT *
            FROM switches
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        $result = $stmt->fetch();

        return $result ?: null;
    }


    /**
     * Menambahkan switch
     */
    public function create(array $data): bool
    {
        $sql = "
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
                :name,
                :hostname,
                :ip_address,
                :location,
                :latitude,
                :longitude,
                :status,
                :description
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name'        => $data['name'],
            ':hostname'    => $data['hostname'],
            ':ip_address'  => $data['ip_address'],
            ':location'    => $data['location'],
            ':latitude'    => $data['latitude'],
            ':longitude'   => $data['longitude'],
            ':status'      => $data['status'],
            ':description' => $data['description']
        ]);
    }


    /**
     * Update switch
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE switches
            SET
                name = :name,
                hostname = :hostname,
                ip_address = :ip_address,
                location = :location,
                latitude = :latitude,
                longitude = :longitude,
                status = :status,
                description = :description
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':hostname'    => $data['hostname'],
            ':ip_address'  => $data['ip_address'],
            ':location'    => $data['location'],
            ':latitude'    => $data['latitude'],
            ':longitude'   => $data['longitude'],
            ':status'      => $data['status'],
            ':description' => $data['description']
        ]);
    }


    /**
     * Hapus switch
     */
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM switches
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }
}