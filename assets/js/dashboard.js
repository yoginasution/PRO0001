document.addEventListener("DOMContentLoaded", function () {

    const mapElement = document.getElementById("networkMap");

    if (!mapElement) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Default Map
    |--------------------------------------------------------------------------
    */

    const defaultCenter = [1.497847, 99.051503];

    const map = L.map("networkMap").setView(
        defaultCenter,
        12
    );


    /*
    |--------------------------------------------------------------------------
    | OpenStreetMap
    |--------------------------------------------------------------------------
    */

    L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    /*
    |--------------------------------------------------------------------------
    | Layer Group
    |--------------------------------------------------------------------------
    */

    const switchLayer = L.layerGroup().addTo(map);
    const clientLayer = L.layerGroup().addTo(map);
    const connectionLayer = L.layerGroup().addTo(map);


    /*
    |--------------------------------------------------------------------------
    | Marker / Connection Index
    |--------------------------------------------------------------------------
    */

    const switchMarkers = new Map();
    const clientMarkers = new Map();

    const connectionsBySwitch = new Map();
    const connectionsByClient = new Map();

    const connectionLines = new Map();
    const connectionStatusMarkers = new Map();


    /*
    |--------------------------------------------------------------------------
    | Switch Icon
    |--------------------------------------------------------------------------
    */

    const switchIcon = L.divIcon({

        className: "custom-map-marker",

        html: `
            <div class="map-marker switch-icon">
                <span>🔀</span>
            </div>
        `,

        iconSize: [42, 42],
        iconAnchor: [21, 42],
        popupAnchor: [0, -42]

    });


    /*
    |--------------------------------------------------------------------------
    | Client Icon
    |--------------------------------------------------------------------------
    */

    const clientIcon = L.divIcon({

        className: "custom-map-marker",

        html: `
            <div class="map-marker client-icon">
                <span>💻</span>
            </div>
        `,

        iconSize: [42, 42],
        iconAnchor: [21, 42],
        popupAnchor: [0, -42]

    });


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div = document.createElement("div");

        div.textContent = value ?? "";

        return div.innerHTML;
    }


    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    function getStatusBadge(status) {

        let label = status || "unknown";
        let className = "status-unknown";

        if (status === "online") {

            label = "Online";
            className = "status-online";

        } else if (status === "offline") {

            label = "Offline";
            className = "status-offline";

        } else if (status === "maintenance") {

            label = "Maintenance";
            className = "status-maintenance";

        }

        return `
            <span class="map-status ${className}">
                ${escapeHtml(label)}
            </span>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Connection Status
    |--------------------------------------------------------------------------
    */

    function getConnectionColor(status) {

        switch (status) {

            case "up":
                return "#16a34a";

            case "down":
                return "#dc2626";

            case "maintenance":
                return "#d97706";

            default:
                return "#6b7280";
        }
    }


    function getConnectionStyle(status) {

        const color = getConnectionColor(status);

        switch (status) {

            case "up":

                return {
                    color: color,
                    weight: 4,
                    opacity: 0.9
                };

            case "down":

                return {
                    color: color,
                    weight: 4,
                    opacity: 0.9,
                    dashArray: "10, 8"
                };

            case "maintenance":

                return {
                    color: color,
                    weight: 4,
                    opacity: 0.9,
                    dashArray: "4, 8"
                };

            default:

                return {
                    color: color,
                    weight: 3,
                    opacity: 0.75,
                    dashArray: "3, 7"
                };
        }
    }


    function getConnectionStatusLabel(status) {

        switch (status) {

            case "up":
                return "UP";

            case "down":
                return "DOWN";

            case "maintenance":
                return "MAINTENANCE";

            default:
                return "UNKNOWN";
        }
    }


    function getConnectionStatusClass(status) {

        switch (status) {

            case "up":
                return "connection-up";

            case "down":
                return "connection-down";

            case "maintenance":
                return "connection-maintenance";

            default:
                return "connection-unknown";
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Flexible Connection Points
    |--------------------------------------------------------------------------
    */

    function createFlexibleConnectionPoints(
        switchLat,
        switchLng,
        clientLat,
        clientLng
    ) {

        const start = L.latLng(
            switchLat,
            switchLng
        );

        const end = L.latLng(
            clientLat,
            clientLng
        );

        const midLat =
            (switchLat + clientLat) / 2;

        const midLng =
            (switchLng + clientLng) / 2;

        const deltaLat =
            clientLat - switchLat;

        const deltaLng =
            clientLng - switchLng;

        const distance =
            Math.sqrt(
                deltaLat * deltaLat +
                deltaLng * deltaLng
            );

        const offset =
            Math.max(
                Math.min(
                    distance * 0.25,
                    0.02
                ),
                0.001
            );

        const controlLat =
            midLat - deltaLng * offset;

        const controlLng =
            midLng + deltaLat * offset;

        return [
            start,
            L.latLng(
                controlLat,
                controlLng
            ),
            end
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Connection Label
    |--------------------------------------------------------------------------
    */

    function createPortLabel(connection) {

        const switchPort =
            connection.switch_port || "-";

        const clientPort =
            connection.client_port || "-";

        const status =
            getConnectionStatusLabel(
                connection.status
            );

        const statusClass =
            getConnectionStatusClass(
                connection.status
            );

        return `
            <div class="connection-label">

                <div class="connection-port">
                    ${escapeHtml(switchPort)}
                    →
                    ${escapeHtml(clientPort)}
                </div>

                <div class="connection-status ${statusClass}">
                    <span class="status-dot"></span>
                    ${escapeHtml(status)}
                </div>

            </div>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Connection Status Marker
    |--------------------------------------------------------------------------
    */

    function createConnectionStatusMarker(
        connection,
        position
    ) {

        const status =
            connection.status || "unknown";

        const statusClass =
            getConnectionStatusClass(status);

        const statusLabel =
            getConnectionStatusLabel(status);

        const icon =
            L.divIcon({

                className:
                    "connection-status-marker",

                html: `
                    <div
                        class="connection-status-dot ${statusClass}"
                        title="${escapeHtml(statusLabel)}"
                    ></div>
                `,

                iconSize: [14, 14],
                iconAnchor: [7, 7]

            });

        const marker =
            L.marker(
                position,
                {
                    icon: icon,
                    interactive: false,
                    keyboard: false
                }
            );

        marker.addTo(connectionLayer);

        connectionStatusMarkers.set(
            Number(connection.id),
            marker
        );

        return marker;
    }


    /*
    |--------------------------------------------------------------------------
    | Switch Popup
    |--------------------------------------------------------------------------
    */

    function switchPopup(device) {

        return `
            <div class="map-popup">

                <div class="popup-title">
                    🔀 ${escapeHtml(device.name)}
                </div>

                <div class="popup-type">
                    NETWORK SWITCH
                </div>

                <div class="popup-row">
                    <strong>Hostname:</strong>
                    ${escapeHtml(device.hostname || "-")}
                </div>

                <div class="popup-row">
                    <strong>IP:</strong>
                    ${escapeHtml(device.ip_address || "-")}
                </div>

                <div class="popup-row">
                    <strong>Lokasi:</strong>
                    ${escapeHtml(device.location || "-")}
                </div>

                <div class="popup-row">
                    <strong>Status:</strong>
                    ${getStatusBadge(device.status)}
                </div>

            </div>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Client Popup
    |--------------------------------------------------------------------------
    */

    function clientPopup(device) {

        return `
            <div class="map-popup">

                <div class="popup-title">
                    💻 ${escapeHtml(device.name)}
                </div>

                <div class="popup-type">
                    NETWORK CLIENT
                </div>

                <div class="popup-row">
                    <strong>Hostname:</strong>
                    ${escapeHtml(device.hostname || "-")}
                </div>

                <div class="popup-row">
                    <strong>IP:</strong>
                    ${escapeHtml(device.ip_address || "-")}
                </div>

                <div class="popup-row">
                    <strong>MAC:</strong>
                    ${escapeHtml(device.mac_address || "-")}
                </div>

                <div class="popup-row">
                    <strong>Lokasi:</strong>
                    ${escapeHtml(device.location || "-")}
                </div>

                <div class="popup-row">
                    <strong>Status:</strong>
                    ${getStatusBadge(device.status)}
                </div>

            </div>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Connection Popup
    |--------------------------------------------------------------------------
    */

    function connectionPopup(connection) {

        return `
            <div class="map-popup">

                <div class="popup-title">
                    🔗 Network Connection
                </div>

                <div class="popup-type">
                    SWITCH → CLIENT
                </div>

                <div class="connection-endpoint">

                    <strong>
                        🔀 ${escapeHtml(
                            connection.switch_name || "-"
                        )}
                    </strong>

                    <span>
                        ${escapeHtml(
                            connection.switch_port || "-"
                        )}
                    </span>

                </div>

                <div class="connection-arrow">
                    ↓
                </div>

                <div class="connection-endpoint">

                    <strong>
                        💻 ${escapeHtml(
                            connection.client_name || "-"
                        )}
                    </strong>

                    <span>
                        ${escapeHtml(
                            connection.client_port || "-"
                        )}
                    </span>

                </div>

                <div class="popup-row">

                    <strong>Status:</strong>

                    ${getStatusBadge(
                        connection.status
                    )}

                </div>

                <div class="popup-row">

                    <strong>Keterangan:</strong>

                    ${escapeHtml(
                        connection.description || "-"
                    )}

                </div>

            </div>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Build Connection Index
    |--------------------------------------------------------------------------
    */

    function buildConnectionIndex() {

        connectionsBySwitch.clear();
        connectionsByClient.clear();

        if (
            typeof connectionData === "undefined" ||
            !Array.isArray(connectionData)
        ) {
            return;
        }

        connectionData.forEach(function (connection) {

            const switchId =
                Number(connection.switch_id);

            const clientId =
                Number(connection.client_id);

            if (
                !Number.isFinite(switchId) ||
                !Number.isFinite(clientId)
            ) {
                return;
            }

            if (!connectionsBySwitch.has(switchId)) {

                connectionsBySwitch.set(
                    switchId,
                    []
                );
            }

            if (!connectionsByClient.has(clientId)) {

                connectionsByClient.set(
                    clientId,
                    []
                );
            }

            connectionsBySwitch
                .get(switchId)
                .push(connection);

            connectionsByClient
                .get(clientId)
                .push(connection);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Update One Connection Line
    |--------------------------------------------------------------------------
    */

    function updateConnectionLine(connection) {

        const switchMarker =
            switchMarkers.get(
                Number(connection.switch_id)
            );

        const clientMarker =
            clientMarkers.get(
                Number(connection.client_id)
            );

        const line =
            connectionLines.get(
                Number(connection.id)
            );

        if (
            !switchMarker ||
            !clientMarker ||
            !line
        ) {
            return;
        }

        const switchPosition =
            switchMarker.getLatLng();

        const clientPosition =
            clientMarker.getLatLng();

        const points =
            createFlexibleConnectionPoints(
                switchPosition.lat,
                switchPosition.lng,
                clientPosition.lat,
                clientPosition.lng
            );

        line.setLatLngs(points);


        /*
         * Update status marker.
         */

        const statusMarker =
            connectionStatusMarkers.get(
                Number(connection.id)
            );

        if (statusMarker) {

            const middlePoint =
                points[
                    Math.floor(
                        points.length / 2
                    )
                ];

            statusMarker.setLatLng(
                middlePoint
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Update All Connections For Switch
    |--------------------------------------------------------------------------
    */

    function updateConnectionsForSwitch(switchId) {

        const connections =
            connectionsBySwitch.get(
                Number(switchId)
            ) || [];

        connections.forEach(
            function (connection) {

                updateConnectionLine(
                    connection
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update All Connections For Client
    |--------------------------------------------------------------------------
    */

    function updateConnectionsForClient(clientId) {

        const connections =
            connectionsByClient.get(
                Number(clientId)
            ) || [];

        connections.forEach(
            function (connection) {

                updateConnectionLine(
                    connection
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Save Device Position
    |--------------------------------------------------------------------------
    */

    async function saveDevicePosition(
        type,
        id,
        latitude,
        longitude
    ) {

        const formData =
            new FormData();

        formData.append(
            "type",
            type
        );

        formData.append(
            "id",
            String(id)
        );

        formData.append(
            "latitude",
            latitude.toFixed(7)
        );

        formData.append(
            "longitude",
            longitude.toFixed(7)
        );


        /*
         * CSRF token jika tersedia.
         */

        if (
            typeof window.CSRF_TOKEN !== "undefined" &&
            window.CSRF_TOKEN
        ) {

            formData.append(
                "csrf_token",
                window.CSRF_TOKEN
            );
        }


        try {

            const response =
                await fetch(
                    "api/update-position.php",
                    {
                        method: "POST",

                        body: formData,

                        headers: {
                            "X-Requested-With":
                                "XMLHttpRequest"
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    "HTTP " +
                    response.status
                );
            }


            const result =
                await response.json();


            if (!result.success) {

                throw new Error(
                    result.message ||
                    "Gagal menyimpan posisi."
                );
            }


            console.log(
                "Posisi tersimpan:",
                result
            );


            return true;

        } catch (error) {

            console.error(
                "Gagal menyimpan posisi:",
                error
            );


            alert(
                "Gagal menyimpan posisi perangkat. " +
                "Periksa api/update-position.php."
            );


            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Switch Dragging
    |--------------------------------------------------------------------------
    */

    function enableSwitchDragging(
        marker,
        switchDevice
    ) {

        marker.on(
            "drag",
            function () {

                const position =
                    marker.getLatLng();


                marker.unbindTooltip();


                marker.bindTooltip(
                    `
                    <strong>
                        ${escapeHtml(
                            switchDevice.name || "Switch"
                        )}
                    </strong>

                    <br>

                    Lat:
                    ${position.lat.toFixed(7)}

                    <br>

                    Lng:
                    ${position.lng.toFixed(7)}
                    `,
                    {
                        permanent: true,
                        direction: "top",
                        offset: [0, -42]
                    }
                ).openTooltip();


                /*
                 * Update connection realtime.
                 */

                updateConnectionsForSwitch(
                    switchDevice.id
                );
            }
        );


        marker.on(
            "dragend",
            async function () {

                marker.unbindTooltip();


                const position =
                    marker.getLatLng();


                /*
                 * Update final line position.
                 */

                updateConnectionsForSwitch(
                    switchDevice.id
                );


                /*
                 * Save to database.
                 */

                await saveDevicePosition(
                    "switch",
                    switchDevice.id,
                    position.lat,
                    position.lng
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Client Dragging
    |--------------------------------------------------------------------------
    */

    function enableClientDragging(
        marker,
        clientDevice
    ) {

        marker.on(
            "drag",
            function () {

                const position =
                    marker.getLatLng();


                marker.unbindTooltip();


                marker.bindTooltip(
                    `
                    <strong>
                        ${escapeHtml(
                            clientDevice.name || "Client"
                        )}
                    </strong>

                    <br>

                    Lat:
                    ${position.lat.toFixed(7)}

                    <br>

                    Lng:
                    ${position.lng.toFixed(7)}
                    `,
                    {
                        permanent: true,
                        direction: "top",
                        offset: [0, -42]
                    }
                ).openTooltip();


                /*
                 * Update connection realtime.
                 */

                updateConnectionsForClient(
                    clientDevice.id
                );
            }
        );


        marker.on(
            "dragend",
            async function () {

                marker.unbindTooltip();


                const position =
                    marker.getLatLng();


                /*
                 * Update final line position.
                 */

                updateConnectionsForClient(
                    clientDevice.id
                );


                /*
                 * Save to database.
                 */

                await saveDevicePosition(
                    "client",
                    clientDevice.id,
                    position.lat,
                    position.lng
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Marker Switch
    |--------------------------------------------------------------------------
    */

    if (
        typeof switchData !== "undefined" &&
        Array.isArray(switchData) &&
        switchData.length > 0
    ) {

        switchData.forEach(function (device) {

            const latitude =
                Number(device.latitude);

            const longitude =
                Number(device.longitude);


            if (
                !Number.isFinite(latitude) ||
                !Number.isFinite(longitude)
            ) {
                return;
            }


            const marker =
                L.marker(
                    [
                        latitude,
                        longitude
                    ],
                    {
                        icon: switchIcon,

                        /*
                         * Switch dapat digeser.
                         */

                        draggable: true
                    }
                );


            marker
                .bindPopup(
                    switchPopup(device),
                    {
                        maxWidth: 320
                    }
                )
                .addTo(switchLayer);


            /*
             * Simpan marker berdasarkan ID.
             */

            switchMarkers.set(
                Number(device.id),
                marker
            );


            /*
             * Aktifkan dragging.
             */

            enableSwitchDragging(
                marker,
                device
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Marker Client
    |--------------------------------------------------------------------------
    */

    if (
        typeof clientData !== "undefined" &&
        Array.isArray(clientData) &&
        clientData.length > 0
    ) {

        clientData.forEach(function (device) {

            const latitude =
                Number(device.latitude);

            const longitude =
                Number(device.longitude);


            if (
                !Number.isFinite(latitude) ||
                !Number.isFinite(longitude)
            ) {
                return;
            }


            const marker =
                L.marker(
                    [
                        latitude,
                        longitude
                    ],
                    {
                        icon: clientIcon,

                        /*
                         * Client dapat digeser.
                         */

                        draggable: true
                    }
                );


            marker
                .bindPopup(
                    clientPopup(device),
                    {
                        maxWidth: 320
                    }
                )
                .addTo(clientLayer);


            /*
             * Simpan marker berdasarkan ID.
             */

            clientMarkers.set(
                Number(device.id),
                marker
            );


            /*
             * Aktifkan dragging.
             */

            enableClientDragging(
                marker,
                device
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Build Connection Index
    |--------------------------------------------------------------------------
    */

    buildConnectionIndex();


    /*
    |--------------------------------------------------------------------------
    | Draw Connections
    |--------------------------------------------------------------------------
    */

    function drawConnections() {

        connectionLayer.clearLayers();

        connectionLines.clear();

        connectionStatusMarkers.clear();


        if (
            typeof connectionData === "undefined" ||
            !Array.isArray(connectionData)
        ) {

            console.warn(
                "connectionData tidak tersedia."
            );

            return;
        }


        if (connectionData.length === 0) {

            console.info(
                "Tidak ada connection."
            );

            return;
        }


        connectionData.forEach(
            function (connection) {

                /*
                 * Ambil marker aktual.
                 */

                const switchMarker =
                    switchMarkers.get(
                        Number(connection.switch_id)
                    );

                const clientMarker =
                    clientMarkers.get(
                        Number(connection.client_id)
                    );


                let switchLat;
                let switchLng;

                let clientLat;
                let clientLng;


                /*
                 * Switch position.
                 */

                if (switchMarker) {

                    const position =
                        switchMarker.getLatLng();

                    switchLat =
                        position.lat;

                    switchLng =
                        position.lng;

                } else {

                    switchLat =
                        Number(
                            connection.switch_latitude
                        );

                    switchLng =
                        Number(
                            connection.switch_longitude
                        );
                }


                /*
                 * Client position.
                 */

                if (clientMarker) {

                    const position =
                        clientMarker.getLatLng();

                    clientLat =
                        position.lat;

                    clientLng =
                        position.lng;

                } else {

                    clientLat =
                        Number(
                            connection.client_latitude
                        );

                    clientLng =
                        Number(
                            connection.client_longitude
                        );
                }


                /*
                 * Validasi koordinat.
                 */

                if (
                    !Number.isFinite(switchLat) ||
                    !Number.isFinite(switchLng) ||
                    !Number.isFinite(clientLat) ||
                    !Number.isFinite(clientLng)
                ) {

                    console.warn(
                        "Koordinat connection tidak valid:",
                        connection
                    );

                    return;
                }


                /*
                 * Flexible points.
                 */

                const points =
                    createFlexibleConnectionPoints(
                        switchLat,
                        switchLng,
                        clientLat,
                        clientLng
                    );


                /*
                 * Polyline.
                 */

                const line =
                    L.polyline(
                        points,
                        getConnectionStyle(
                            connection.status
                        )
                    );


                line.connectionId =
                    Number(connection.id);


                /*
                 * Popup.
                 */

                line.bindPopup(
                    connectionPopup(
                        connection
                    )
                );


                /*
                 * Port + status tooltip.
                 */

                line.bindTooltip(
                    createPortLabel(
                        connection
                    ),
                    {
                        sticky: true,
                        direction: "top",
                        opacity: 1
                    }
                );


                /*
                 * Add line.
                 */

                line.addTo(
                    connectionLayer
                );


                /*
                 * Save line reference.
                 */

                connectionLines.set(
                    Number(connection.id),
                    line
                );


                /*
                 * Status marker.
                 */

                const middleIndex =
                    Math.floor(
                        points.length / 2
                    );


                const middlePoint =
                    points[middleIndex];


                createConnectionStatusMarker(
                    connection,
                    middlePoint
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Draw Connections
    |--------------------------------------------------------------------------
    |
    | Harus dilakukan setelah marker dibuat.
    |
    */

    drawConnections();


    /*
    |--------------------------------------------------------------------------
    | Fit Map To Device Markers
    |--------------------------------------------------------------------------
    */

    const allMarkers = [];


    switchLayer.eachLayer(
        function (layer) {

            if (
                layer instanceof L.Marker
            ) {

                allMarkers.push(layer);
            }
        }
    );


    clientLayer.eachLayer(
        function (layer) {

            if (
                layer instanceof L.Marker
            ) {

                allMarkers.push(layer);
            }
        }
    );


    if (allMarkers.length > 0) {

        const group =
            L.featureGroup(
                allMarkers
            );


        map.fitBounds(
            group.getBounds().pad(0.2)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Layer Control
    |--------------------------------------------------------------------------
    */

    L.control.layers(
        null,
        {
            "🔗 Connection":
                connectionLayer,

            "🔀 Switch":
                switchLayer,

            "💻 Client":
                clientLayer
        },
        {
            collapsed: false
        }
    ).addTo(map);

});