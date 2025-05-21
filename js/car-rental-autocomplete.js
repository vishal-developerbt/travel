jQuery(function ($) {
    $("#pickup-location, #dropoff-location").autocomplete({
        source: function (request, response) {
            $.get(airportSearch.ajax_url, {
                action: 'get_car_rental_suggestions',
                term: request.term
            }, function (data) {
                response(data);
            }, 'json');
        },
        minLength: 2,
        select: function (event, ui) {
            if (this.id === 'pickup-location') {
                $('#pickup-location-id').val(ui.item.id);
            } else {
                $('#dropoff-location-id').val(ui.item.id);
            }
        }
    });
});
