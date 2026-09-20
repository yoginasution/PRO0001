<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan.'
    ]);

    exit;
}


$type =
    $_POST['type'] ?? '';

$id =
    (int) ($_POST['id'] ?? 0);

$latitude =
    $_POST['latitude'] ?? '';

$longitude =
    $_POST['longitude'] ?? '';


if (
    !in_array(
        $type,
        ['switch', 'client'],
        true
    )
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Tipe perangkat tidak valid.'
    ]);

    exit;
}


if ($id <= 0) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'ID perangkat tidak valid.'
    ]);

    exit;
}


if (
    !is_numeric($latitude) ||
    !is_numeric($longitude)
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Koordinat tidak valid.'
    ]);

    exit;
}


$latitude =
    (float) $latitude;

$longitude =
    (float) $longitude;


if (
    $latitude < -90 ||
    $latitude > 90 ||
    $longitude < -180 ||
    $longitude > 180
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Koordinat berada di luar batas.'
    ]);

    exit;
}


$table =
    $type === 'switch'
        ? 'switches'
        : 'clients';


try {

    $sql = "
        UPDATE {$table}

        SET
            latitude = :latitude,
            longitude = :longitude

        WHERE id = :id
    ";


    $stmt =
        $pdo->prepare($sql);


    $stmt->execute([

        ':latitude' =>
            $latitude,

        ':longitude' =>
            $longitude,

        ':id' =>
            $id
    ]);


    echo json_encode([

        'success' => true,

        'message' =>
            'Posisi berhasil diperbarui.',

        'data' => [

            'type' =>
                $type,

            'id' =>
                $id,

            'latitude' =>
                $latitude,

            'longitude' =>
                $longitude
        ]
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>
            'Gagal memperbarui posisi.'
    ]);
}