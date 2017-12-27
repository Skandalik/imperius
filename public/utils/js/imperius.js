$(document).ready(function () {
    function outputUpdate(data) {
        document.querySelector('#sensorStatusOutput').value = data;
    }

    var sensorStatusElement = $("#sensorStatusSet");

    sensorStatusElement.on('input', function (element) {
        outputUpdate($(this).val());
    });

    sensorStatusElement.on('change', function (element) {
        var uuid = $(this).closest("#sensor").data('uuid');
        var sensorData = $(this).val();

        window.location.href = Routing.generate('sensor_set_status', {
            'uuid': uuid, 'status': sensorData
        });
    });
});