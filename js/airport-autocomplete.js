if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
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

}


jQuery(document).ready(function($) {
    function bindAutoComplete(displaySelector, hiddenSelector) {
        $(displaySelector).autocomplete({
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
            minLength: 2,
            select: function(event, ui) {
                // Set display text
                $(displaySelector).val(ui.item.label); // e.g., "New York (JFK)"
                // Set hidden value
                $(hiddenSelector).val(ui.item.value); // e.g., "JFK"
                return false;
            }
        });
    }

    bindAutoComplete('#departure_airport_display', '#departure_airport');
    bindAutoComplete('#flight_location_display', '#flight_location');
});
