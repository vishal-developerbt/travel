jQuery(document).ready(function($) {
    $('.search-city-property').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: citySearch.ajax_url,
                dataType: 'json',
                data: {
                    action: 'get_city_suggestions',
                    keyword: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 3 // Trigger search after typing 3 characters
    });
});
