jQuery(document).ready(function ($) {
  // Open the popup form
  function hotelPaymentOpenForm() {
    $('#hotelPaymentPopupForm').css('display', 'flex');
  }

  // Close the popup form and reset it
  function hotelPaymentCloseForm() {
    $('#hotelPaymentPopupForm').hide();
    resetGuestForm();
  }

  // Validate form fields
  function hotelGuestValidateForm() {
    const requiredFields = ['#guest_type', '#guest_title', '#h_guest_first_name', '#h_guest_last_name'];
    for (let selector of requiredFields) {
      const value = $(selector).val();
      if (!value || !value.trim()) {
        alert("Please fill all required fields.");
        return false;
      }
    }
    return true;
  }

  // ✅ Reset form and re-enable submit button
  function resetGuestForm() {
    $('#hotelGuestForm')[0].reset();
    $('#g_id').val('');
    $('.hotel-payment-btn-submit').prop('disabled', false).text('Submit'); // ✅ ADDED
  }

  // Submit form
$('#hotelGuestForm').on('submit', function (e) {
    e.preventDefault(); // Prevent form submission by default

    // Get user ID and email
    const user_id = $('#user_id').val();
    const guest_email = $('#email').val().trim();

    // for guest user check start
    if (user_id == 0) {
        const email = guest_email;

        // Check if the email is empty
        if (!email) {
            alert('Please enter your email before submitting.');
            return false; // Stop form submission if email is empty
        }

        $('#g_email').val(email); // Set email into the hidden field

        // AJAX request to check if email exists in the system
        $.post(hotelguestAjax.ajax_url, {
            action: 'check_user_by_email', // Same action as for flight
            email: email // Email to check
        }, function (response) {
            if (response.exists) {
                alert("This Email already exists, please login first.");
                return false; // Stop here if the email exists in the system
            } else {
                submitForm(); // Continue with form submission after email check
            }
        });

        return false; 
    }
    // if user is not a guest (has user_id), continue with form submission directly
    else {
        submitForm(); // Continue with form submission
    }
});

// Function to handle form submission after email validation
function submitForm() {
    // Validate the form first
    if (!hotelGuestValidateForm()) return;

    // Disable the submit button while the form is being submitted
    $('.hotel-payment-btn-submit').prop('disabled', true).text('Submitting...');

    let guestData = {
        action: 'save_hotel_guest_data',
        nonce: hotelguestAjax.nonce, // Include nonce for form submission
        guest_type: $('#guest_type').val(),
        guest_title: $('#guest_title').val(),
        first_name: $('#h_guest_first_name').val(),
        last_name: $('#h_guest_last_name').val(),
        user_id: $('#user_id').val() == 0 ? $('#email').val().trim() : $('#user_id').val(),
        g_id: $('#g_id').val() || ''
    };

    // Submit the form data via AJAX
    $.post(hotelguestAjax.ajax_url, guestData, function (response) {
        // Enable the submit button once the process is complete
        $('.hotel-payment-btn-submit').prop('disabled', false).text('Submit');

        if (response.success) {
            resetGuestForm();
            hotelPaymentCloseForm();

            const li = $(`li[data-id="${response.data.id}"]`);
            if (li.length) li.remove();

            appendGuest(response.data);
        } else {
            alert(response.data || 'Error saving guest.');
        }
    });
}


  // Add guest to list
  function appendGuest(guest) {
    let li = `
      <li data-id="${guest.id}">
        <label style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
          <div>
            <input type="checkbox" name="selected_guest[]" value="${guest.guest_type}_${guest.id}" />
             <strong class="guest-selct-sections-hotel">${guest.guest_title} ${guest.first_name} ${guest.last_name} </strong>
            <span>
            <div class="guest-section">${guest.guest_type.charAt(0).toUpperCase() + guest.guest_type.slice(1)}</div>
            </span>
          </div>
          <div>
            <button type="button" class="edithotelGuestBtn btn btn-sm btn-warning" data-id="${guest.id}">Edit</button>
            <button type="button" class="removeGuestHotelBtn btn btn-sm btn-danger" data-id="${guest.id}">Remove</button>
          </div>
        </label>
      </li>`;
    $('#guestList1').append(li);
  }

  // Load saved guests
  function loadSavedGuests() {
    $.post(hotelguestAjax.ajax_url, {
      action: 'get_hotel_booking_guests',
      nonce: hotelguestAjax.nonce
    }, function (response) {
      if (response.success && Array.isArray(response.data)) {
        $('#guestList1').empty();
        response.data.forEach(appendGuest);
      } else {
        console.warn('No guests found or failed to load.');
      }
    });
  }

  loadSavedGuests();

  // Delete guest
  $(document).on('click', '.removeGuestHotelBtn', function () {
    const guestId = $(this).data('id');
    if (!confirm("Are you sure you want to delete this guest?")) return;

    $.post(hotelguestAjax.ajax_url, {
      action: 'delete_guest',
      nonce: hotelguestAjax.nonce,
      guest_id: guestId
    }, function (response) {
      if (response.success) {
        $(`li[data-id="${guestId}"]`).remove();
      } else {
        alert(response.data || "Failed to delete guest.");
      }
    });
  });

  // Edit guest
  $(document).on('click', '.edithotelGuestBtn', function () {
    const guestId = $(this).data('id');
    resetGuestForm();
    $.post(hotelguestAjax.ajax_url, {
      action: 'get_hotel_guest_by_id',
      nonce: hotelguestAjax.nonce,
      guest_id: guestId
    }, function (response) {
      if (response.success) {
        const g = response.data;
        $('#guest_type').val(g.guest_type);
        $('#guest_title').val(g.guest_title);
        $('#h_guest_first_name').val(g.first_name);
        $('#h_guest_last_name').val(g.last_name);
        $('#g_id').val(g.id);
        hotelPaymentOpenForm();
      } else {
        alert("Failed to load guest info.");
      }
    });
  });

  // Expose open/close globally
  window.hotelPaymentOpenForm = hotelPaymentOpenForm;
  window.hotelPaymentCloseForm = hotelPaymentCloseForm;
});
