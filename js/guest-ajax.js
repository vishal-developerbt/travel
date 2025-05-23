jQuery(document).ready(function ($) {
  // ✅ OPEN form popup
  function hotelPaymentOpenForm() {
    $('#hotelPaymentPopupForm').css('display', 'flex');
  }

  // ✅ CLOSE form popup
  function hotelPaymentCloseForm() {
    $('#hotelPaymentPopupForm').hide();
    resetGuestForm();
  }

  // ✅ FORM VALIDATION
  function hotelPaymentValidateForm() {
    const requiredFields = ['#guest_title', '#first_name', '#last_name', '#guest_type', '#guestDob', '#guestNationality'];
    for (let selector of requiredFields) {
      if (!$(selector).val().trim()) {
        alert("Please fill all required fields.");
        return false;
      }
    }
    return true;
  }

  // ✅ RESET form fields
  function resetGuestForm() {
    $('#hotelPaymentForm')[0].reset();
    $('#g_id').val('');
  }
  

  // ✅ Submit Guest Form Start
  $('#hotelPaymentForm').on('submit', function (e) {
    e.preventDefault();
    if (!hotelPaymentValidateForm()) return;

    let guestType = $('#guest_type').val();
    const dob = $('#guestDob').val();

    if (dob) {
      const currentDate = new Date();
      const dobDate = new Date(dob);

      let years = currentDate.getFullYear() - dobDate.getFullYear();
      let months = currentDate.getMonth() - dobDate.getMonth();
      let days = currentDate.getDate() - dobDate.getDate();

      if (months < 0) {
        years--;
        months += 12;
      }

      if (days < 0) {
        months--;
        const lastMonthDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
        days += lastMonthDate.getDate();
      }

      // ✅ Auto-select 'infants' if age is less than 2 years
      if (
        years < 2 ||
        (years === 2 && months === 0 && days === 0) // exactly 2 years still valid
      ) {
        $('#guest_type').val('infant');
        guestType = 'infant';
      }

      // ❌ Infant validation
      if (
        guestType === 'infant' &&
        (years > 2 || (years === 2 && months > 0) || (years === 2 && months === 0 && days > 0))
      ) {
        alert('Infant date of birth must not be more than 2 years ago.');
        return;
      }

      // ❌ Child validation
      if (
        guestType === 'child' &&
        (years > 12 || (years === 12 && months > 0) || (years === 12 && months === 0 && days > 0))
      ) {
        alert('Child date of birth must not be more than 12 years ago.');
        return;
      }
    }

    let guestData = {
      action: 'save_guest_data',
      nonce: guestAjax.nonce,
      title: $('#guest_title').val(),
      first_name: $('#first_name').val(),
      last_name: $('#last_name').val(),
      guest_type: guestType,
      dob: dob,
      nationality: $('#guestNationality').val(),
      user_id: $('#user_id').val(),
      g_id: $('#g_id').val() || ''
    };

    $.post(guestAjax.ajax_url, guestData, function (response) {
      if (response.success) {
        resetGuestForm();
        hotelPaymentCloseForm();

        const li = $(`li[data-id="${response.data.id}"]`);
        if (li.length) li.remove(); // Replace if exists

        appendGuest(response.data);
      } else {
        alert(response.data || 'Error saving guest.');
      }
    });
  });
  // ✅ Submit Guest Form End

  // ✅ Append Guest to List Start
  function appendGuest(guest) {
    let li = `
      <li data-id="${guest.id}">
        <label style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
          <div>
            <input type="checkbox" name="selected_guest[]" value="${guest.id}" />
            <span>
              <strong>${guest.title} ${guest.first_name} ${guest.last_name}</strong><br>
              ${guest.nationality} | ${guest.guest_type}
            </span>
          </div>
          <div>
            <button type="button" class="editGuestBtn btn btn-sm btn-warning" data-id="${guest.id}">Edit</button>
            <button type="button" class="removeGuestBtn btn btn-sm btn-danger" data-id="${guest.id}">Remove</button>
          </div>
        </label>
      </li>`;
    $('#guestList').append(li);
  }
  // ✅ Append Guest to List End

  // ✅ Load Guests on Page Load Start
  function loadSavedGuests() {
    $.post(guestAjax.ajax_url, {
      action: 'get_flight_booking_guests',
      nonce: guestAjax.nonce
    }, function (response) {
      if (response.success && Array.isArray(response.data)) {
        $('#guestList').empty();
        response.data.forEach(appendGuest);
      } else {
        console.warn('No guests found or failed to load.');
      }
    });
  }
  // ✅ Load Guests on Page Load End

  loadSavedGuests();

  // ✅ Delete Guest Start
  $(document).on('click', '.removeGuestBtn', function () {
    const guestId = $(this).data('id');
    if (!confirm("Are you sure you want to delete this guest?")) return;

    $.post(guestAjax.ajax_url, {
      action: 'delete_guest',
      nonce: guestAjax.nonce,
      guest_id: guestId
    }, function (response) {
      if (response.success) {
        $(`li[data-id="${guestId}"]`).remove();
      } else {
        alert(response.data || "Failed to delete guest.");
      }
    });
  });
  // ✅ Delete Guest End

  // ✅ Edit Guest Form Start(Load into Form)
  $(document).on('click', '.editGuestBtn', function () {
    const guestId = $(this).data('id');
    resetGuestForm(); // clear before loading
    $.post(guestAjax.ajax_url, {
      action: 'get_guest_by_id',
      nonce: guestAjax.nonce,
      guest_id: guestId
    }, function (response) {
      if (response.success) {
        const g = response.data;
        $('#guest_title').val(g.title);
        $('#first_name').val(g.first_name);
        $('#last_name').val(g.last_name);
        $('#guest_type').val(g.guest_type);
        $('#guestDob').val(g.dob);
        $('#guestNationality').val(g.nationality);
        $('#g_id').val(g.id);
        hotelPaymentOpenForm();
      } else {
        alert("Failed to load guest info.");
      }
    });
  });
  // ✅ Edit Guest Form End(Load into Form)

  // ✅ Make open/close functions globally accessible (if needed outside)
  window.hotelPaymentOpenForm = hotelPaymentOpenForm;
  window.hotelPaymentCloseForm = hotelPaymentCloseForm;
});

