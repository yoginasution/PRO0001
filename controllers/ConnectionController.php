<?php

require_once __DIR__ . '/../models/ConnectionModel.php';

class ConnectionController
{
    private PDO $pdo;

    private ConnectionModel $model;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->model = new ConnectionModel($pdo);
    }


    /**
     * Halaman daftar koneksi.
     */
    public function index(): void
    {
        $connections = $this->model->getAll();

        require __DIR__ . '/../views/connection/index.php';
    }


    /**
     * Halaman tambah koneksi.
     */
    public function create(): void
    {
        $switches = $this->model->getSwitches();

        $clients = $this->model->getClients();

        $formData = [
            'switch_id'   => '',
            'client_id'   => '',
            'switch_port' => '',
            'client_port' => '',
            'status'      => 'down',
            'description' => ''
        ];

        $errors = [];

        require __DIR__ . '/../views/connection/create.php';
    }


    /**
     * Menyimpan koneksi baru.
     */
    public function store(): void
    {
	require_csrf();

        $data = $this->getFormData();

        $errors = $this->validate($data);

        if (!empty($errors)) {

            $switches = $this->model->getSwitches();

            $clients = $this->model->getClients();

            require __DIR__ . '/../views/connection/create.php';

            return;
        }


        $this->model->create($data);


        header(
            'Location: '
            . BASE_URL
            . '?page=connection&success=created'
        );

        exit;
    }


    /**
     * Halaman edit.
     */
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->notFound();
        }


        $connection = $this->model->getById($id);

        if (!$connection) {
            $this->notFound();
        }


        $formData = $connection;

        $switches = $this->model->getSwitches();

        $clients = $this->model->getClients();

        $errors = [];

        require __DIR__ . '/../views/connection/edit.php';
    }


    /**
     * Update koneksi.
     */
    public function update(): void
    {
	require_csrf();
	
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->notFound();
        }


        $data = $this->getFormData();

        $errors = $this->validate($data);


        if (!empty($errors)) {

            $formData = array_merge(
                ['id' => $id],
                $data
            );

            $switches = $this->model->getSwitches();

            $clients = $this->model->getClients();

            require __DIR__ . '/../views/connection/edit.php';

            return;
        }


        $this->model->update(
            $id,
            $data
        );


        header(
            'Location: '
            . BASE_URL
            . '?page=connection&success=updated'
        );

        exit;
    }


    /**
     * Hapus koneksi.
     */

    public function delete(): void
{
    require_csrf();

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {

        header(
            'Location: '
            . BASE_URL
            . '?page=connection&error=invalid_id'
        );

        exit;
    }

    $connection = $this->model->getById($id);

    if (!$connection) {

        header(
            'Location: '
            . BASE_URL
            . '?page=connection&error=not_found'
        );

        exit;
    }

    $this->model->delete($id);

    header(
        'Location: '
        . BASE_URL
        . '?page=connection&success=deleted'
    );

    exit;
}


    /**
     * Mengambil data form.
     */
    private function getFormData(): array
    {
        return [

            'switch_id' => (int) (
                $_POST['switch_id'] ?? 0
            ),

            'client_id' => (int) (
                $_POST['client_id'] ?? 0
            ),

            'switch_port' => trim(
                $_POST['switch_port'] ?? ''
            ),

            'client_port' => $this->nullableValue(
                $_POST['client_port'] ?? ''
            ),

            'status' => trim(
                $_POST['status'] ?? 'down'
            ),

            'description' => $this->nullableValue(
                $_POST['description'] ?? ''
            )
        ];
    }


    /**
     * Mengubah string kosong menjadi NULL.
     */
    private function nullableValue($value): ?string
    {
        $value = trim((string) $value);

        return $value === ''
            ? null
            : $value;
    }


    /**
     * Validasi form.
     */
    private function validate(array $data): array
    {
        $errors = [];


        if ($data['switch_id'] <= 0) {

            $errors[] =
                'Switch wajib dipilih.';
        }


        if ($data['client_id'] <= 0) {

            $errors[] =
                'Client wajib dipilih.';
        }


        if ($data['switch_port'] === '') {

            $errors[] =
                'Port Switch wajib diisi.';
        }


        $allowedStatus = [
            'up',
            'down',
            'maintenance'
        ];


        if (
            !in_array(
                $data['status'],
                $allowedStatus,
                true
            )
        ) {

            $errors[] =
                'Status koneksi tidak valid.';
        }


        if (
            $data['switch_id'] > 0 &&
            $data['client_id'] > 0 &&
            $data['switch_id'] === $data['client_id']
        ) {

            // Tidak wajib diberlakukan jika ID switch dan client
            // berasal dari tabel berbeda.
            // Sengaja tidak dianggap error.
        }


        return $errors;
    }


    /**
     * 404.
     */
    private function notFound(): void
    {
        http_response_code(404);

        echo '<h1>404 - Koneksi Tidak Ditemukan</h1>';

        echo '<a href="'
            . BASE_URL
            . '?page=connection'
            . '">Kembali</a>';

        exit;
    }
}