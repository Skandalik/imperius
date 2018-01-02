$(document).ready(function () {
    // var sensorRedisCache;
    //
    // (function worker() {
    //     $.get('http://imperius.home:8090/api/cache/sensor', function(data) {
    //         sensorRedisCache = data;
    //     });
    // })();

    function outputUpdate(data) {
        $(this).closest('.sensorStatusOutput').value = data;
    }

    let sensorStatusElement = $(".sensorStatusSet");

    sensorStatusElement.on('input', function () {
        $(this).parent().find('.sensorStatusOutput').val($(this).val());
    });

    sensorStatusElement.on('change', function () {
        let uuid = $(this).closest("#sensor").data('uuid');
        let sensorData = $(this).val();

        window.location.href = Routing.generate('sensor_set_status', {
            'uuid': uuid, 'status': sensorData
        });
    });

    let nameCount = $('.name-not-set').length;
    let roomCount = $('.room-not-set').length;
    console.log(nameCount);
    console.log(roomCount);
    if (nameCount > 0) {
        $.notify("You have " + nameCount + " names not set.", {
            clickToHide: true,
            autoHide: false,
            style: 'bootstrap',
        });
    }

    if (roomCount > 0) {
        $.notify("You have " + roomCount + " rooms not set.", {
            clickToHide: true,
            autoHide: false,
            style: 'bootstrap'
        });
    }

    // (function worker() {
    //     $.ajax({
    //         url: 'http://imperius.home:8090/api/sensor',
    //         success: function(data) {
    //             // if (data.length !== sensorRedisCache.length) {
    //                 $('.new-sensors').show();
    //                 $.get('http://imperius.home:8090/api/cache/sensor/create');
    //             // }
    //         },
    //         complete: function() {
    //             // Schedule the next request when the current one's complete
    //             setTimeout(worker, 5000);
    //         }
    //     });
    // })();
});