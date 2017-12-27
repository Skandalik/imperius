$(document).ready(function () {
    function outputUpdate(data) {
        $(this).closest('.sensorStatusOutput').value = data;
    }

    var sensorStatusElement = $(".sensorStatusSet");

    sensorStatusElement.on('input', function () {
        $(this).parent().find('.sensorStatusOutput').val($(this).val());
    });

    sensorStatusElement.on('change', function () {
        var uuid = $(this).closest("#sensor").data('uuid');
        var sensorData = $(this).val();

        window.location.href = Routing.generate('sensor_set_status', {
            'uuid': uuid, 'status': sensorData
        });
    });
});