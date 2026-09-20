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