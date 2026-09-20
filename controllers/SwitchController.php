<?php

require_once __DIR__ . '/../models/SwitchModel.php';

class SwitchController
{
    private SwitchModel $model;

    public function __construct(PDO $db)
    {
        $this->model = new SwitchModel($db);
    }


    /**
     * Daftar switch
     */
    public function index(): void
    {
        $switches = $this->model->getAll();

        require __DIR__ . '/../views/switch/index.php';
    }


    /**
     * Form tambah switch
     */
    public function create(): void
    {
        $errors = [];

        require __DIR__ . '/../views/switch/create.php';
    }


    /**
     * Simpan switch baru
     */
    public function store(): void
    {
	require_csrf();
	
        $data = $this->getFormData();

        $errors = $this->validate($data);

        if (!empty($errors)) {

            require __DIR__ . '/../views/switch/create.php';

            return;
        }

        $this->model->create($data);

        header(
            'Location: ' .
            BASE_URL .
            '?page=switch&success=created'
        );

        exit;
    }


    /**
     * Form edit
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

        $switch = $this->model->getById($id);

        if (!$switch) {
            $this->notFound();

            return;
        }

        $errors = [];

        require __DIR__ . '/../views/switch/edit.php';
    }


    /**
     * Update switch
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

            $switch = $data;

            require __DIR__ . '/../views/switch/edit.php';

            return;
        }

        $this->model->update($id, $data);

        header(
            'Location: ' .
            BASE_URL .
            '?page=switch&success=updated'
        );

        exit;
    }


    /**
     * Hapus switch
     */

    public function delete(): void
{
    require_csrf();

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {

        header(
            'Location: '
            . BASE_URL
            . '?page=switch&error=invalid_id'
        );

        exit;
    }

    $switch = $this->model->getById($id);

    if (!$switch) {

        header(
            'Location: '
            . BASE_URL
            . '?page=switch&error=not_found'
        );

        exit;
    }

    $this->model->delete($id);

    header(
        'Location: '
        . BASE_URL
        . '?page=switch&success=deleted'
    );

    exit;
}


    /**
     * Ambil data form
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
     * Ubah string kosong menjadi NULL
     */
    private function nullableValue($value): ?string
    {
        $value = trim((string) $value);

        return $value === ''
            ? null
            : $value;
    }


    /**
     * Validasi
     */
    private function validate(array $data): array
    {
        $errors = [];

        if ($data['name'] === '') {

            $errors[] =
                'Nama switch wajib diisi.';
        }

        if (
            $data['status'] !== 'online' &&
            $data['status'] !== 'offline' &&
            $data['status'] !== 'maintenance'
        ) {

            $errors[] =
                'Status switch tidak valid.';
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

        echo '<h1>404 - Switch Tidak Ditemukan</h1>';

        echo '<a href="' .
            BASE_URL .
            '?page=switch">
            Kembali
        </a>';
    }
}