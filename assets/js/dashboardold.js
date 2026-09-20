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

            attribution:
                '&copy; OpenStreetMap contributors'
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
                ${label}
            </span>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | Line Status Colour
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


    /*
    |--------------------------------------------------------------------------
    | Line Status Style
    |--------------------------------------------------------------------------
    */

function getConnectionStyle(status) {

    const color =
        getConnectionColor(status);

    switch (status) {

        case 'up':

            return {
                color: color,
                weight: 4,
                opacity: 0.9
            };


        case 'down':

            return {
                color: color,
                weight: 4,
                opacity: 0.9,
                dashArray: '10, 8'
            };


        case 'maintenance':

            return {
                color: color,
                weight: 4,
                opacity: 0.9,
                dashArray: '4, 8'
            };


        default:

            return {
                color: color,
                weight: 3,
                opacity: 0.75,
                dashArray: '3, 7'
            };
    }
}

    /*
    |--------------------------------------------------------------------------
    | Line Status Label
    |--------------------------------------------------------------------------
    */

function getConnectionStatusLabel(status) {

    switch (status) {

        case 'up':
            return 'UP';

        case 'down':
            return 'DOWN';

        case 'maintenance':
            return 'MAINTENANCE';

        default:
            return 'UNKNOWN';
    }
}

    /*
    |--------------------------------------------------------------------------
    | Line Status Label Class
    |--------------------------------------------------------------------------
    */

function getConnectionStatusClass(status) {

    switch (status) {

        case 'up':
            return 'connection-up';

        case 'down':
            return 'connection-down';

        case 'maintenance':
            return 'connection-maintenance';

        default:
            return 'connection-unknown';
    }
}


    /*
    |--------------------------------------------------------------------------
    | Flexible line
    |--------------------------------------------------------------------------
    */

function createFlexibleConnectionPoints(
    switchLat,
    switchLng,
    clientLat,
    clientLng
) {

    /*
     * Titik awal
     */
    const start = L.latLng(
        switchLat,
        switchLng
    );


    /*
     * Titik akhir
     */
    const end = L.latLng(
        clientLat,
        clientLng
    );


    /*
     * Titik tengah
     */
    const midLat =
        (switchLat + clientLat) / 2;

    const midLng =
        (switchLng + clientLng) / 2;


    /*
     * Selisih posisi
     */
    const deltaLat =
        clientLat - switchLat;

    const deltaLng =
        clientLng - switchLng;


    /*
     * Offset lengkungan.
     *
     * Nilainya relatif terhadap
     * jarak Switch -> Client.
     */
    const offset =
        Math.max(
            Math.min(
                Math.sqrt(
                    deltaLat * deltaLat +
                    deltaLng * deltaLng
                ) * 0.25,
                0.02
            ),
            0.001
        );


    /*
     * Titik kontrol.
     *
     * Offset tegak lurus terhadap
     * arah Switch -> Client.
     */
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
    | Port Label Connection
    |--------------------------------------------------------------------------
    */
function createPortLabel(connection) {

    const switchPort =
        connection.switch_port || '-';

    const clientPort =
        connection.client_port || '-';

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
                ${status}
            </div>

        </div>
    `;
}

    /*
    |--------------------------------------------------------------------------
    | Port Marker Status Connection
    |--------------------------------------------------------------------------
    */

function createConnectionStatusMarker(
    connection,
    position
) {

    const status =
        connection.status || 'unknown';

    const statusClass =
        getConnectionStatusClass(status);

    const statusLabel =
        getConnectionStatusLabel(status);

    const icon =
        L.divIcon({

            className:
                'connection-status-marker',

            html: `
                <div
                    class="connection-status-dot ${statusClass}"
                    title="${statusLabel}"
                ></div>
            `,

            iconSize: [14, 14],

            iconAnchor: [7, 7]
        });

    return L.marker(
        position,
        {
            icon: icon,
            interactive: false
        }
    ).addTo(connectionLayer);
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
    | Connections Popup
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
                        connection.switch_name
                    )}
                </strong>

                <span>
                    ${escapeHtml(
                        connection.switch_port
                    )}
                </span>

            </div>


            <div class="connection-arrow">
                ↓
            </div>


            <div class="connection-endpoint">

                <strong>
                    💻 ${escapeHtml(
                        connection.client_name
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
    | Index Connections
    |--------------------------------------------------------------------------
    */


const switchMarkers = new Map();
const clientMarkers = new Map();
const connectionsBySwitch = new Map();
const connectionsByClient = new Map();
const connectionLines = new Map();


function buildConnectionIndex() {

    connectionsBySwitch.clear();
    connectionsByClient.clear();

    if (
        typeof connectionData === 'undefined' ||
        !Array.isArray(connectionData)
    ) {
        return;
    }

    connectionData.forEach(function (connection) {

        const switchId =
            Number(connection.switch_id);

        const clientId =
            Number(connection.client_id);


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
    | Marker Switch
    |--------------------------------------------------------------------------
    */

    if (
        Array.isArray(switchData) &&
        switchData.length > 0
    ) {

        switchData.forEach(function (device) {

            if (
                device.latitude === null ||
                device.longitude === null
            ) {
                return;
            }


            const marker = L.marker(
                [
                    Number(device.latitude),
                    Number(device.longitude)
                ],
                {
                    icon: switchIcon,
			draggable : true
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

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Marker Client
    |--------------------------------------------------------------------------
    */

    if (
        Array.isArray(clientData) &&
        clientData.length > 0
    ) {

        clientData.forEach(function (device) {

            if (
                device.latitude === null ||
                device.longitude === null
            ) {
                return;
            }


            const marker = L.marker(
                [
                    Number(device.latitude),
                    Number(device.longitude)
                ],
                {
                    icon: clientIcon,
			draggable : true
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

        });

    }

	/*
	|-----------------------------------------------------------------------
	| Drag marker Switch
	|-----------------------------------------------------------------------
	*/

function enableSwitchDragging(
    marker,
    switchDevice
) {

    marker.on(
        'drag',
        function () {

            const position =
                marker.getLatLng();


            marker.bindTooltip(
                `
                <strong>
                    ${escapeHtml(
                        switchDevice.name
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
                    direction: 'top'
                }
            ).openTooltip();


            updateConnectionsForSwitch(
                switchDevice.id
            );
        }
    );


    marker.on(
        'dragend',
        function () {

            marker.closeTooltip();


            const position =
                marker.getLatLng();


            saveDevicePosition(
                'switch',
                switchDevice.id,
                position.lat,
                position.lng
            );
        }
    );
}

	/*
	|-----------------------------------------------------------------------
	| Drag marker Client
	|-----------------------------------------------------------------------
	*/
function enableClientDragging(
    marker,
    clientDevice
) {

    marker.on(
        'drag',
        function () {

            const position =
                marker.getLatLng();


            marker.bindTooltip(
                `
                <strong>
                    ${escapeHtml(
                        clientDevice.name
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
                    direction: 'top'
                }
            ).openTooltip();


            updateConnectionsForClient(
                clientDevice.id
            );
        }
    );


    marker.on(
        'dragend',
        function () {

            marker.closeTooltip();


            const position =
                marker.getLatLng();


            saveDevicePosition(
                'client',
                clientDevice.id,
                position.lat,
                position.lng
            );
        }
    );
}


	/*
	|-----------------------------------------------------------------------
	| CONNECTION
	|-----------------------------------------------------------------------
	*/

		drawConnections();
		buildConnectionIndex();

		enableSwitchDragging(
    		marker,
    		sw
		);

		enableClientDragging(
   		marker,
    		client
		);


    /*
    |--------------------------------------------------------------------------
    | Membuat line Conncetion
    |--------------------------------------------------------------------------
    */

function drawConnections() {

    /*
     * Bersihkan koneksi lama
     */
    connectionLayer.clearLayers();


    /*
     * Validasi data
     */
    if (
        typeof connectionData === 'undefined' ||
        !Array.isArray(connectionData)
    ) {

        console.warn(
            'connectionData tidak tersedia.'
        );

        return;
    }


    if (connectionData.length === 0) {

        console.info(
            'Tidak ada connection.'
        );

        return;
    }


    /*
     * Loop semua connection
     */
    connectionData.forEach(
        function (connection, index) {

            /*
             * Koordinat Switch
             */
            const switchLat =
                Number(
                    connection.switch_latitude
                );

            const switchLng =
                Number(
                    connection.switch_longitude
                );


            /*
             * Koordinat Client
             */
            const clientLat =
                Number(
                    connection.client_latitude
                );

            const clientLng =
                Number(
                    connection.client_longitude
                );


            /*
             * Validasi koordinat
             */
            if (
                !Number.isFinite(switchLat) ||
                !Number.isFinite(switchLng) ||
                !Number.isFinite(clientLat) ||
                !Number.isFinite(clientLng)
            ) {

                console.warn(
                    'Koordinat tidak valid:',
                    connection
                );

                return;
            }


            /*
             * Buat titik garis fleksibel
             */
            const points =
                createFlexibleConnectionPoints(
                    switchLat,
                    switchLng,
                    clientLat,
                    clientLng
                );


            /*
             * Buat polyline
             */
    const line = L.polyline(
    points,
    getConnectionStyle(
        connection.status
    )
);

    connectionLines.set(
    Number(connection.id),
    line
);

line.addTo(connectionLayer);

            /*
             * ID connection
             */
            line.connectionId =
                connection.id;


            /*
             * Popup
             */
            line.bindPopup(`
                <div class="connection-popup">

                    <div class="popup-title">
                        🔗 Network Connection
                    </div>

                    <hr>

                    <div class="popup-row">
                        <strong>Switch:</strong>
                        ${escapeHtml(
                            connection.switch_name || '-'
                        )}
                    </div>

                    <div class="popup-row">
                        <strong>Port:</strong>
                        ${escapeHtml(
                            connection.switch_port || '-'
                        )}
                    </div>

                    <hr>

                    <div class="popup-row">
                        <strong>Client:</strong>
                        ${escapeHtml(
                            connection.client_name || '-'
                        )}
                    </div>

                    <div class="popup-row">
                        <strong>Port:</strong>
                        ${escapeHtml(
                            connection.client_port || '-'
                        )}
                    </div>

                    <hr>

                    <div class="popup-row">

                        <strong>Status:</strong>

                        <span
                            class="connection-status ${
                                getConnectionStatusClass(
                                    connection.status
                                )
                            }"
                        >
                            <span class="status-dot"></span>

                            ${getConnectionStatusLabel(
                                connection.status
                            )}
                        </span>

                    </div>

                </div>
            `);


            /*
             * Tooltip
             */
            line.bindTooltip(
                createPortLabel(
                    connection
                ),
                {
                    sticky: true,
                    direction: 'top',
                    opacity: 1
                }
            );


            /*
             * Tambahkan garis
             */
            line.addTo(
                connectionLayer
            );


            /*
             * Tentukan titik tengah
             */
            const middleIndex =
                Math.floor(
                    points.length / 2
                );


            const middlePoint =
                points[middleIndex];


            /*
             * Tambahkan indikator status
             */
            createConnectionStatusMarker(
                connection,
                middlePoint
            );

        }
    );
}

    /*
    |--------------------------------------------------------------------------
    | Update Line Connections
    |--------------------------------------------------------------------------
    */

function updateConnectionLine(
    connection
) {

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


    line.setLatLngs(
        points
    );
}


    /*
    |--------------------------------------------------------------------------
    | Update All Switch Connection
    |--------------------------------------------------------------------------
    */

function updateConnectionsForSwitch(
    switchId
) {

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
    | Update All Client Connection
    |--------------------------------------------------------------------------
    */

function updateConnectionsForClient(
    clientId
) {

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
    | Save Position
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
        'type',
        type
    );

    formData.append(
        'id',
        id
    );

    formData.append(
        'latitude',
        latitude
    );

    formData.append(
        'longitude',
        longitude
    );


    try {

        const response =
            await fetch(
                'api/update-position.php',
                {
                    method: 'POST',
                    body: formData
                }
            );


        if (!response.ok) {

            throw new Error(
                'HTTP ' +
                response.status
            );
        }


        const result =
            await response.json();


        if (!result.success) {

            throw new Error(
                result.message ||
                'Gagal menyimpan posisi.'
            );
        }


        console.log(
            'Posisi tersimpan:',
            result
        );


    } catch (error) {

        console.error(
            'Gagal menyimpan posisi:',
            error
        );

        alert(
            'Gagal menyimpan posisi perangkat.'
        );
    }
}


    /*
    |--------------------------------------------------------------------------
    | Fit Map To Devices
    |--------------------------------------------------------------------------
    */

    const allMarkers = [];


    switchLayer.eachLayer(function (layer) {

        allMarkers.push(layer);

    });


    clientLayer.eachLayer(function (layer) {

        allMarkers.push(layer);

    });


    if (allMarkers.length > 0) {

        const group = L.featureGroup(allMarkers);

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
        "🔗 Connection": connectionLayer,
        "🔀 Switch": switchLayer,
        "💻 Client": clientLayer
    },
    {
        collapsed: false
    }
).addTo(map);

});