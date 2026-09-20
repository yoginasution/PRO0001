<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars(APP_NAME) ?>
    </title>

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/switch.css"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/client.css"
    >

    <link
       rel="stylesheet"
       href="<?= BASE_URL ?>assets/css/connection.css"
    >

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/responsive.css"
    >

   <link
       rel="stylesheet"
       href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
   />

</head>

<body>

<?php require __DIR__ . '/navbar.php'; ?>

<?php require __DIR__ . '/sidebar.php'; ?>

<main class="main-content">

<div class="app">