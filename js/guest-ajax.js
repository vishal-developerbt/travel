jQuery(document).ready(function ($) {
  // ✅ OPEN form popup
  function flightPaymentOpenForm() {
    $('#flightPaymentPopupForm').css('display', 'flex');
  }

  // ✅ CLOSE form popup
  function flightPaymentCloseForm() {
    $('#flightPaymentPopupForm').hide();
    flightresetGuestForm();
  }

  // ✅ FORM VALIDATION
  function flightPaymentValidateForm() {
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
  function flightresetGuestForm() {
    $('#flightPaymentForm')[0].reset();
    $('#g_id').val('');
  }
  

// ✅ Submit Guest Form Start
$('#flightPaymentForm').on('submit', function (e) {

  e.preventDefault();

  if (!flightPaymentValidateForm()) return;

  let guestType = $('#guest_type').val();
  const dob = $('#guestDob').val();
  let valid = true;

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

    // ✅ Auto-set guest type to infant if < 2 years
    if (years < 2 || (years === 2 && months === 0 && days === 0)) {
      $('#guest_type').val('infant');
      guestType = 'infant';
    }

    // ❌ Infant DOB validation
    if (
      guestType === 'infant' &&
      (years > 2 || (years === 2 && (months > 0 || days > 0)))
    ) {
      alert('Infant date of birth must not be more than 2 years ago.');
      return;
    }

    // ❌ Child DOB validation
    if (
      guestType === 'child' &&
      (years > 12 || (years === 12 && (months > 0 || days > 0)))
    ) {
      alert('Child date of birth must not be more than 12 years ago.');
      return;
    }
  }


   const guest_passport_required = $('#guest_passport_required').val();
     let passportNumber = '';
let passportExpiry = '';

  if (guest_passport_required) {
    passportNumber = $('#guest_passport_number').val();
    passportExpiry = $('#guest_passport_expiry').val();
    const passportNumberPattern = /^[a-zA-Z0-9]{8,9}$/;

    $('#guest_passport_number, #guest_passport_expiry').removeClass('is-invalid');

    if (!passportNumberPattern.test(passportNumber)) {
      $('#guest_passport_number').addClass('is-invalid');
      valid = false;
    }

    if (passportExpiry) {
      const expiry = new Date(passportExpiry);
      const today = new Date();
      const sixMonthsFromNow = new Date();
      sixMonthsFromNow.setMonth(today.getMonth() + 6);

      if (expiry <= sixMonthsFromNow) {
        $('#guest_passport_expiry').addClass('is-invalid');
        valid = false;
      }
    } else {
      $('#guest_passport_expiry').addClass('is-invalid');
      valid = false;
    }
  }
  
  if (!valid) {
    return;
  }

  // ✅ Proceed with AJAX submission
  let guestData = {
    action: 'save_guest_data',
    nonce: guestAjax.nonce,
    title: $('#guest_title').val(),
    first_name: $('#first_name').val(),
    last_name: $('#last_name').val(),
    guest_type: guestType,
    dob: dob,
    nationality: $('#guestNationality').val(),
    guest_passport_number: passportNumber,
    //guest_issue_country: $('#guest_issue_country').val(),
    guest_issue_country: $('#guest_issue_country_code').val(),
    guest_passport_expiry: passportExpiry,
    user_id: $('#user_id').val(),
    g_id: $('#g_id').val() || ''
  };

  $.post(guestAjax.ajax_url, guestData, function (response) {
    if (response.success) {
      flightresetGuestForm();
      flightPaymentCloseForm();

      const li = $(`li[data-id="${response.data.id}"]`);
      if (li.length) li.remove();

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
          <div class="selected-guest-section">
            <input type="checkbox" name="selected_guest[]" value="${guest.id}" />
             <strong class="guest-strong-section">${guest.title} ${guest.first_name} ${guest.last_name}</strong>
            <span>
             
              <div class="guest-flight-type">${guest.nationality} | ${guest.guest_type}</div>
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
      action: 'flight_delete_guest',
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
    flightresetGuestForm(); // clear before loading
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
        $('#guest_passport_number').val(g.guest_passport_number);
        $('#guest_issue_country').val(g.guest_issue_country);
        $('#guest_passport_expiry').val(g.guest_passport_expiry);
        $('#g_id').val(g.id);
        flightPaymentOpenForm();
      } else {
        alert("Failed to load guest info.");
      }
    });
  });
  // ✅ Edit Guest Form End(Load into Form)

  // ✅ Make open/close functions globally accessible (if needed outside)
  window.flightPaymentOpenForm = flightPaymentOpenForm;
  window.flightPaymentCloseForm = flightPaymentCloseForm;
});

