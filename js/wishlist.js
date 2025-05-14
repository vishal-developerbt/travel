jQuery(document).ready(function($) {
    // Delegated click handler for wishlist icons
    $(document).on('click', '.wishlist-icon', function() {
        let $icon = $(this);
        let item_id = $icon.data('item-id');
        let customer_id = $icon.data('customer-id');
        let customer_email = $icon.data('customer-email');
        let type = $icon.data('type');
        let name = $icon.data('hotel-name');

        let action = $icon.hasClass('in-wishlist') ? 'remove_from_wishlist' : 'add_to_wishlist';

        // Perform AJAX request to add or remove the item from the wishlist
        $.ajax({
            url: wishlist_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: action,
                _wpnonce: wishlist_ajax_obj.nonce,
                item_id: item_id,
                type: type,
                customer_id: customer_id,
                customer_email: customer_email,
                hotel_name: name
            },
            success: function(response) {
                if (response.success) {
                    // Toggle the "in-wishlist" class based on action
                    if (action === 'add_to_wishlist') {
                        $icon.addClass('in-wishlist').removeClass('not-in-wishlist');
                        $icon.find('i').removeClass('far').addClass('fas');
                        $icon.attr('title', 'Remove from wishlist');
                    } else {
                        $icon.removeClass('in-wishlist').addClass('not-in-wishlist');
                        $icon.find('i').removeClass('fas').addClass('far');
                        $icon.attr('title', 'Add to wishlist');
                    }
                } else {
                    alert(response.data || 'Something went wrong.');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error, xhr.responseText);
                alert("AJAX request failed: " + error);
            }
        });
    });
});
