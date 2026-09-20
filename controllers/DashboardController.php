<?php

require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController
{
    private PDO $pdo;
    private DashboardModel $model;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->model = new DashboardModel($pdo);
    }

    public function index(): void
{
    $switches =
        $this->model->getSwitchesForMap();

    $clients =
        $this->model->getClientsForMap();

    $connections =
        $this->model->getConnectionsForMap();

    $switchStats =
        $this->model->getSwitchStatistics();

    $clientStats =
        $this->model->getClientStatistics();

    require __DIR__ . '/../views/dashboard/index.php';
}
}