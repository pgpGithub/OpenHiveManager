$(document).ready(function() {

        var weather = $("#weather").weather({
                latitude: $('#weather').data('latitude'),
                longitude: $('#weather').data('longitude'),
        });

}); 