jQuery(document).ready(function($) {
    $('input[name="departure_airport"]').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: airportSearch.ajax_url,
                dataType: 'json',
                data: {
                    action: 'get_airport_suggestions',
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2
    });
});

jQuery(document).ready(function($) {
    // Apply to both departure input and location field
    $('input[name="departure_airport"], input[name="flight_location"]').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: airportSearch.ajax_url,
                dataType: 'json',
                data: {
                    action: 'get_airport_suggestions',
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2
    });
});
