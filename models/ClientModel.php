<?php

class ClientModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /**
     * Mengambil semua client
     */
    public function getAll(): array
    {
        $sql = "
            SELECT *
            FROM clients
            ORDER BY id DESC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }


    /**
     * Mengambil client berdasarkan ID
     */
    public function getById(int $id): ?array
    {
        $sql = "
            SELECT *
            FROM clients
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        $client = $stmt->fetch();

        return $client ?: null;
    }


    /**
     * Menambahkan client
     */
    public function create(array $data): bool
    {
        $sql = "
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
                :name,
                :hostname,
                :ip_address,
                :mac_address,
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
            ':mac_address' => $data['mac_address'],
            ':location'    => $data['location'],
            ':latitude'    => $data['latitude'],
            ':longitude'   => $data['longitude'],
            ':status'      => $data['status'],
            ':description' => $data['description']
        ]);
    }


    /**
     * Update client
     */
    public function update(
        int $id,
        array $data
    ): bool {

        $sql = "
            UPDATE clients
            SET
                name = :name,
                hostname = :hostname,
                ip_address = :ip_address,
                mac_address = :mac_address,
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
            ':mac_address' => $data['mac_address'],
            ':location'    => $data['location'],
            ':latitude'    => $data['latitude'],
            ':longitude'   => $data['longitude'],
            ':status'      => $data['status'],
            ':description' => $data['description']
        ]);
    }


    /**
     * Hapus client
     */
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM clients
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }
}