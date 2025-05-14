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
    const requiredFields = ['#guest_type', '#first_name', '#last_name'];
    for (let selector of requiredFields) {
      const value = $(selector).val();
      if (!value || !value.trim()) {
        alert("Please fill all required fields.");
        return false;
      }
    }
    return true;
  }

  // ✅ Reset form safely
 function resetGuestForm() {
    $('#hotelGuestForm')[0].reset();
    $('#g_id').val('');
  }


  // Submit form
  $('#hotelGuestForm').on('submit', function (e) {
    e.preventDefault();
    if (!hotelGuestValidateForm()) return;

    let guestData = {
      action: 'save_hotel_guest_data',
      nonce: hotelguestAjax.nonce,
      guest_type: $('#guest_type').val(),
      first_name: $('#first_name').val(),
      last_name: $('#last_name').val(),
      user_id: $('#user_id').val(),
      g_id: $('#g_id').val() || ''
    };

    $.post(hotelguestAjax.ajax_url, guestData, function (response) {
      if (response.success) {
        resetGuestForm();
        hotelPaymentCloseForm();

        const li = $(`li[data-id="${response.data.id}"]`);
        if (li.length) li.remove(); // replace if editing

        appendGuest(response.data);
      } else {
        alert(response.data || 'Error saving guest.');
      }
    });
  });

  // Add guest to list
  function appendGuest(guest) {
    let li = `
      <li data-id="${guest.id}">
        <label style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
          <div>
            <input type="checkbox" name="selected_guest[]" value="${guest.guest_type}_${guest.id}" />
            <span>
              <strong>${guest.first_name} ${guest.last_name} ${guest.guest_type}</strong>
            </span>
          </div>
          <div>
            <button type="button" class="edithotelGuestBtn btn btn-sm btn-warning" data-id="${guest.id}">Edit</button>
            <button type="button" class="removeGuestBtn btn btn-sm btn-danger" data-id="${guest.id}">Remove</button>
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
  $(document).on('click', '.removeGuestBtn', function () {
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

  // ✅ Edit guest - safely reset form and open popup
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
        $('#first_name').val(g.first_name);
        $('#last_name').val(g.last_name);
        $('#g_id').val(g.id);
        hotelPaymentOpenForm();
      } else {
        alert("Failed to load guest info.");
      }
    });
  });

  // Optional: Expose globally if needed
  window.hotelPaymentOpenForm = hotelPaymentOpenForm;
  window.hotelPaymentCloseForm = hotelPaymentCloseForm;
});
