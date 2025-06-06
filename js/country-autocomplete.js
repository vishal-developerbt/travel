jQuery(document).ready(function($) {
    $('#passport_issue_country').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'search_countries',
                    keyword: request.term
                },
                success: function(data) {
                    // Make sure your server returns: [{ label: 'Afghanistan', value: 'AF' }, ...]
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            // Set visible field
            $('#passport_issue_country').val(ui.item.label);
            // Set hidden field
            $('#passport_issue_country_code').val(ui.item.value);
            return false;
        }
    });


    $('#guest_issue_country').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'search_countries',
                    keyword: request.term
                },
                success: function(data) {
                    response(data); // format: [{ label: 'India', value: 'IN' }, ...]
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            $('#guest_issue_country').val(ui.item.label); // visible field
            $('#guest_issue_country_code').val(ui.item.value); // hidden field
            return false;
        }
    });

});
