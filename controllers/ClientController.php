<?php

require_once __DIR__ . '/../models/ClientModel.php';

class ClientController
{
    private ClientModel $model;


    public function __construct(PDO $db)
    {
        $this->model = new ClientModel($db);
    }


    /**
     * Daftar client
     */
    public function index(): void
    {
        $clients = $this->model->getAll();

        require __DIR__ . '/../views/client/index.php';
    }


    /**
     * Form tambah client
     */
    public function create(): void
    {
        $data = [
            'name'        => '',
            'hostname'    => '',
            'ip_address'  => '',
            'mac_address' => '',
            'location'    => '',
            'latitude'    => '',
            'longitude'   => '',
            'status'      => 'offline',
            'description' => ''
        ];

        $errors = [];

        require __DIR__ . '/../views/client/create.php';
    }


    /**
     * Simpan client baru
     */
    public function store(): void
    {
	require_csrf();
	
        $data = $this->getFormData();

        $errors = $this->validate($data);

        if (!empty($errors)) {

            require __DIR__ . '/../views/client/create.php';

            return;
        }

        $this->model->create($data);

        header(
            'Location: ' .
            BASE_URL .
            '?page=client&success=created'
        );

        exit;
    }


    /**
     * Form edit client
     */
    public function edit(): void
    {
        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {

            $this->notFound();

            return;
        }

        $client = $this->model->getById($id);

        if (!$client) {

            $this->notFound();

            return;
        }

        $data = $client;

        $errors = [];

        require __DIR__ . '/../views/client/edit.php';
    }


    /**
     * Update client
     */
    public function update(): void
    {
	require_csrf();

        $id = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$id) {

            $this->notFound();

            return;
        }

        $data = $this->getFormData();

        $errors = $this->validate($data);

        if (!empty($errors)) {

            require __DIR__ . '/../views/client/edit.php';

            return;
        }

        $this->model->update(
            $id,
            $data
        );

        header(
            'Location: ' .
            BASE_URL .
            '?page=client&success=updated'
        );

        exit;
    }


    /**
     * Hapus client
     */
    public function delete(): void
{
    require_csrf();

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {

        header(
            'Location: '
            . BASE_URL
            . '?page=client&error=invalid_id'
        );

        exit;
    }

    $client = $this->model->getById($id);

    if (!$client) {

        header(
            'Location: '
            . BASE_URL
            . '?page=client&error=not_found'
        );

        exit;
    }

    $this->model->delete($id);

    header(
        'Location: '
        . BASE_URL
        . '?page=client&success=deleted'
    );

    exit;
}


    /**
     * Mengambil data dari form
     */
    private function getFormData(): array
    {
        return [

            'name' => trim(
                $_POST['name'] ?? ''
            ),

            'hostname' => trim(
                $_POST['hostname'] ?? ''
            ),

            'ip_address' => trim(
                $_POST['ip_address'] ?? ''
            ),

            'mac_address' => trim(
                $_POST['mac_address'] ?? ''
            ),

            'location' => trim(
                $_POST['location'] ?? ''
            ),

            'latitude' => $this->nullableValue(
                $_POST['latitude'] ?? ''
            ),

            'longitude' => $this->nullableValue(
                $_POST['longitude'] ?? ''
            ),

            'status' => $_POST['status']
                ?? 'offline',

            'description' => trim(
                $_POST['description'] ?? ''
            )
        ];
    }


    /**
     * Nilai kosong menjadi NULL
     */
    private function nullableValue($value): ?string
    {
        $value = trim((string) $value);

        return $value === ''
            ? null
            : $value;
    }


    /**
     * Validasi form
     */
    private function validate(array $data): array
    {
        $errors = [];


        if ($data['name'] === '') {

            $errors[] =
                'Nama client wajib diisi.';
        }


        if (
            $data['status'] !== 'online' &&
            $data['status'] !== 'offline' &&
            $data['status'] !== 'maintenance'
        ) {

            $errors[] =
                'Status client tidak valid.';
        }


        if (
            $data['latitude'] !== null &&
            !is_numeric($data['latitude'])
        ) {

            $errors[] =
                'Latitude harus berupa angka.';
        }


        if (
            $data['longitude'] !== null &&
            !is_numeric($data['longitude'])
        ) {

            $errors[] =
                'Longitude harus berupa angka.';
        }


        return $errors;
    }


    /**
     * 404
     */
    private function notFound(): void
    {
        http_response_code(404);

        echo '<h1>404 - Client Tidak Ditemukan</h1>';

        echo '<a href="' .
            BASE_URL .
            '?page=client">
            Kembali ke Client
        </a>';
    }
}