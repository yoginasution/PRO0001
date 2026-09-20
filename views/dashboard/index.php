<?php

$pageTitle = 'Dashboard Monitoring';

require __DIR__ . '/../layouts/header.php';

?>

<div class="dashboard-container">

    <!-- Header -->
    <div class="page-header">

        <div>
            <h1>Network Monitoring</h1>

            <p>
                Monitoring Switch dan Client
            </p>
        </div>

    </div>


    <!-- Statistik -->
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-title">
                Total Switch
            </div>

            <div class="stat-value">
                <?= (int) ($switchStats['total'] ?? 0) ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Switch Online
            </div>

            <div class="stat-value">
                <?= (int) ($switchStats['online'] ?? 0) ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Total Client
            </div>

            <div class="stat-value">
                <?= (int) ($clientStats['total'] ?? 0) ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Client Online
            </div>

            <div class="stat-value">
                <?= (int) ($clientStats['online'] ?? 0) ?>
            </div>

        </div>

    </div>


    <!-- MAP -->
    <div class="map-card">

        <div class="map-header">

            <div>
                <h2>Network Map</h2>

                <p>
                    Lokasi Switch dan Client
                </p>
            </div>


            <div class="map-legend">

                <span class="legend-item">
                    <span class="legend-marker switch-marker"></span>
                    Switch
                </span>

                <span class="legend-item">
                    <span class="legend-marker client-marker"></span>
                    Client
                </span>

            </div>

        </div>


        <div
            id="networkMap"
            class="network-map"
        ></div>

    </div>

</div>


<!-- Data Switch -->
<script>
    const switchData = <?= json_encode(
        $switches ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) ?>;



<!-- Data Client -->

    const clientData = <?= json_encode(
        $clients ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) ?>;


<!-- Data Connection -->

    const connectionData = <?= json_encode(
        $connections ?? [],
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    ) ?>;

   console.log(
        "Switch:",
        switchData
    );


    console.log(
        "Client:",
        clientData
    );


    console.log(
        "Connection:",
        connectionData
    );
</script>


<!-- Leaflet JS -->
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>


<!-- Dashboard JS -->
<script
    src="<?= BASE_URL ?>assets/js/dashboard.js">
</script>


<?php require __DIR__ . '/../layouts/footer.php'; ?>