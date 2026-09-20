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