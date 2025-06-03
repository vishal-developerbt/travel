<?php 
/**
 * Template Name: Hotel payment Page
 * Description: A custom page template for special layouts.
 */

get_header(); ?>

<?php
// Redirect if user is not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/my-account/'));
    exit;
}

// Required URL parameters
$required_params = ['checkin', 'checkout', 'rooms', 'hotel_id',
'session_id','product_id','token_id','rate_basis_id','price'];
$missing_params = false;

foreach ($required_params as $param) {
    if (empty($_GET[$param])) {
        echo "<p style='color:red;'>Missing or empty: $param</p>";
        $missing_params = true;
    }
}

if ($missing_params) {
    echo '<div style="padding: 20px; margin: 20px auto; background: #ffe8e8; border: 1px solid #ff0000; color: #b30000; max-width: 600px; font-weight: bold; text-align: center;">
        ⚠️ Please search for a hotel first.
    </div>';
    return; 
}
?>
<?php
$checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
$dateObject = new DateTime($checkin);
$checkoutdateObject = new DateTime($checkout);
$checkinDate = $dateObject->format('D, d M, Y');
$checkoutDate = $checkoutdateObject->format('D, d M, Y');
$roomData = isset($_GET['rooms']) ? sanitize_text_field($_GET['rooms']) : '';
$hotelId = isset($_GET['hotel_id']) ? sanitize_text_field($_GET['hotel_id']) : '';
$sessionId = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';
$productId = isset($_GET['product_id']) ? sanitize_text_field($_GET['product_id']) : '';
$tokenId = isset($_GET['token_id']) ? sanitize_text_field($_GET['token_id']) : '';
$rateBasisId = isset($_GET['rate_basis_id']) ? sanitize_text_field($_GET['rate_basis_id']) : '';
$fareType = isset($_GET['fare_type']) ? sanitize_text_field($_GET['fare_type']) : '';
$roomPrice = isset($_GET['price']) ? sanitize_text_field($_GET['price']) : '';
$hotelDetails = fetch_hotel_details_by_id($hotelId,$productId,$tokenId,$sessionId);
//echo "<pre>"; print_r($hotelDetails); die;
// Calculate number of nights
$day_checkIn = new DateTime($checkin);
$day_checkOut = new DateTime($checkout);
$interval = $day_checkIn->diff($day_checkOut);
$nightCount = $interval->days;
$nightText = $nightCount == 1 ? "1 Night" : "$nightCount Nights";

// Room formatting
function formatRoomData($roomData) {
    $parts = explode('-', $roomData);
    $rooms = $parts[0];
    $adults = $parts[1];
    $children = $parts[2];

    $output = $rooms == 1 ? "1 Room" : "$rooms Rooms";
    $output .= $adults == 1 ? ", 1 Adult" : ", $adults Adults";

    if ($children > 0) {
        $output .= $children == 1 ? ", 1 Child" : ", $children Children";
    }
    return $output;
}
 ?>

<!-- Confirmation Bar -->
<div class="container-fluid confirmation-bar-wrapper confirmation-bar"></div>

<!-- Main Content -->
<div class="main-content container">
    <div class="container conform-booking-upper-part-1">
      <h1 class="mb-4 conform-booking-flight-detail-travel-1">Confirm Your Booking</h1>
    </div>
    <div class="content-wrapper row g-4">
      <!-- Left Column -->
      <div class="main-column col-md-8">
      <!-- Tour Card -->
        <div class="tour-card card mb-4">
          <div class="tour-card-body card-body d-flex gap-4">
            <div class="tour-image-wrapper tour-image">
              <?php

                $hotelImages = isset($hotelDetails['hotelImages']) ? $hotelDetails['hotelImages'] : [];
                foreach (array_slice($hotelImages, 0, 1) as $hotelImage) {
                  $imageUrl = isset($hotelImage['url']) ? esc_url($hotelImage['url']) : '';
                  $imageCaption = isset($hotelImage['caption']) ? esc_attr($hotelImage['caption']) : 'Hotel Image';
                  echo "<img src='$imageUrl' alt='$imageCaption' class='htour-img'>";
                }
              ?>
            </div>
            <div class="tour-info tour-details">
              <h3 class="tour-title title-about-hotel-booking-hotels"><?php echo $hotelDetails['name']; ?></h3>
              <div class="tour-rating rating-items-about-hotel-and-more">
                <?php for ($i = 0; $i < 5; $i++): ?>
                  <img src="<?php echo get_template_directory_uri(); ?>/photos/star.png" alt="star" class="rating-star">
                <?php endfor; ?>
              </div>
              <div class="tour-location location">
                <img src="<?php echo get_template_directory_uri(); ?>/photos/location.png" alt="">
                <span class="location-text-eiffel-tower-items">
                  <?php
                    $location_parts = array_filter([
                        $hotelDetails['locality'] ?? '',
                        $hotelDetails['city'] ?? '',
                        $hotelDetails['country'] ?? ''
                    ]);
                    echo !empty($location_parts) ? implode(', ', $location_parts) : 'Location not available';
                  ?>
                </span>
              </div>
              <div class="tour-review review-score">
                <span class="review-badge badge bg-primary"><?php echo $hotelDetails['hotelRating']; ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Booking Details -->
        <div class="booking-details-card card mb-4">
          <div class="booking-card-body card-body">
            <h2 class="booking-title-of-hotel-uh mb-4">Your Booking Details</h2>
            <div class="booking-info-wrapper booking-info d-flex gap-4 mb-4">
              <div class="check-in-details check-in">
                <small class="guests-label-hotel">Check-in</small>
                <p class="check-in-date-hotels fw-bold mb-0"><?php echo $checkinDate; ?></p>
                <small class="durition-time-untill-up">From 02:00 PM</small>
              </div>
              <div class="booking-divider vr"></div>
              <div class="check-out-details check-out">
                <small class="guests-label-hotel">Check-out</small>
                <p class="check-out-date-hotels fw-bold mb-0"><?php echo $checkoutDate; ?></p>
                <small class="durition-time-untill-up">Until 02:00 PM</small>
              </div>
              <div class="booking-divider vr"></div>
              <div class="guests-details guests">
                <small class="guests-label-hotel">Guests</small>
                <p class="guests-info-hotels fw-bold mb-0"><?php echo formatRoomData($roomData); ?></p>
                <small class="durition-time-untill-up"><?php echo $nightText; ?></small>
              </div>
            </div>
            <div class="room-details-wrapper room-details">
              <h3 class="room-type-many-type-bed-and-room"><?php echo $room['roomType']; ?></h3>
              <ul class="room-benefits benefits list-unstyled">
                <?php if (!empty($hotelDetails['facilities'])): ?>
                  <?php foreach ($hotelDetails['facilities'] as $index => $facility): ?>
                    <li class="benefit-item tick-sign-items-more <?php echo $index >= 5 ? 'hidden-facility' : ''; ?>" style="<?php echo $index >= 5 ? 'display: none;' : ''; ?>">
                      <img src="<?php echo get_template_directory_uri(); ?>/photos/tick.png" alt="tick">
                      <?php echo htmlspecialchars($facility); ?>
                    </li>
                  <?php endforeach; ?>

                  <?php if (count($hotelDetails['facilities']) > 5): ?>
                    <li><a href="javascript:void(0);" class="show-more-facilities text-primary">Show more</a></li>
                  <?php endif; ?>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
        <?php
        $first_name = '';
        $last_name  = '';
        $email      = '';
        $phone      = '';

        if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();

            $email      = esc_attr( $current_user->user_email );
            $first_name = esc_attr( get_user_meta( $current_user->ID, 'first_name', true ) );
            $last_name  = esc_attr( get_user_meta( $current_user->ID, 'last_name', true ) );
            $phone      = esc_attr( get_user_meta( $current_user->ID, 'user_registration_phone', true ) ); // WooCommerce
        }
        ?>
        <!-- User Form -->
          <div class="rounded p-4 Traveller-details-in-booking-sec">
              <div class="rounded Traveller-details-in-booking-sec">
                <h2 class="fs-5 fw-bold mb-3 details-fare-det-tra-offert">Traveller Details</h2>
                <form id="travelerForm">
                  <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <label class="form-label small form-name-email-detail-all-th">Gender<span class="star-section-red-color">*</span></label>
                      <select name="title" id="title" class="form-select hotel-payment-form-control">
                          <option value="Mr">Mr</option>
                          <option value="Mrs">Mrs</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                      <label class="form-label small form-name-email-detail-all-th">First Name<span class="star-section-red-color">*</span></label>
                      <input type="text" class="form-control" id="firstName" value="<?php echo $first_name; ?>"required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small form-name-email-detail-all-th">Last Name<span class="star-section-red-color">*</span></label>
                      <input type="text" class="form-control" id="lastName" value="<?php echo $last_name; ?>" required>
                    </div>
               

                  
                    <div class="col-md-6 mb-3 mb-md-0">
                      <label class="form-label small form-name-email-detail-all-th">Phone Number<span class="star-section-red-color">*</span></label>
                      <input type="tel" class="form-control" id="phone" value="<?php echo $phone; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small form-name-email-detail-all-th">Email Address<span class="star-section-red-color">*</span></label>
                      <input type="email" class="form-control" id="email" value="<?php echo $email; ?>" required>
                    </div>

                

                  <div class="col-md-6 special-requests-wrapper mb-4">
                    <label class="form-name-email-detail-all-th">Special requests to hotel</label>
                    <input type="text" class="form-control requests-input ">
                    <!-- <textarea class="requests-input form-control" rows="4"></textarea> -->
                  </div>

                  <div class="terms-wrapper form-check mb-4">
                    <input type="checkbox" class="terms-checkbox form-check-input" id="terms" required>
                    <label class="terms-label form-check-label" for="terms">
                      By proceeding, I agree to BookATravel Privacy Policy, User Agreement & Terms of Service
                    </label>
                  </div>
                     </div>
                  <input type="hidden" name="pid" value="<?php echo $productId ; ?>">
                  <input type="hidden" name="rateBasisId" value="<?php echo $rateBasisId; ?>">
                  <input type="hidden" name="netPrice" value="<?php echo $roomPrice; ?>">
                  <input type="hidden" name="faretype" value="<?php echo $fareType; ?>">
               </form>

               <!-- Guest Form start -->
                        <div class="add-guest-only-sec">
              <!-- Trigger Button -->
              <button class="hotel-btn-submit add-guest-main-btn-1" onclick="hotelPaymentOpenForm()">
                Add Guest <span style="margin-right: 8px; font-size: 14px;">➕</span>
              </button>

              <!-- Display Submitted Guest Name -->
              <div id="guestNameDisplay" class="appear-guest-name"></div>

              <!-- Guest List -->
              <div id="guestListContainer">
                <ul id="guestList1" style="list-style: none; padding: 0;"></ul>
              </div>

              <!-- Popup Form -->
              <div id="hotelPaymentPopupForm" class="hotel-payment-popup-form-container">
                <div class="hotel-payment-popup-form-content">
                  <span class="hotel-payment-close-btn" onclick="hotelPaymentCloseForm()">&times;</span>
                  <h4>Add Guest for Hotel Booking</h4>
                  <form id="hotelGuestForm">
                    <div class="hotel-payment-form-group">
                      <label>Type:</label>
                      <select class="form-select" id="guest_type" name="guest_type" required>
                        <option value="" selected disabled>Select</option>
                        <option value="adult">Adult</option>
                        <option value="child">Child</option>
                      </select>
                    </div>
                     <div class="hotel-payment-form-group">
                      <label>Title (Mr, Mrs):</label>
                      <select id="guest_title" class="hotel-payment-form-control">
                          <option value="">Select</option> <!-- Default empty option -->
                          <option value="Mr">Mr</option>
                          <option value="Mrs">Mrs</option>
                          <option value="Master">Boy (Master)</option>
                          <option value="Miss">Girl (Mis)</option>
                      </select>
                    </div>
                    <div class="hotel-payment-form-group">
                      <label>First Name:</label>
                      <input type="text" id="first_name" name="first_name" class="hotel-payment-form-control" required />
                    </div>
                    <div class="hotel-payment-form-group">
                      <label>Last Name:</label>
                      <input type="text" id="last_name" name="last_name" class="hotel-payment-form-control" required />
                    </div>
                    <input type="hidden" id="user_id" value="<?php echo esc_attr(get_current_user_id()); ?>" />
                    <input type="hidden" id="g_id" value="" />
                    <button type="submit" class="hotel-payment-btn-submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
                  <!-- Guest Form End -->
                  <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-primary btn-lg py-3 px-4 w-100 w-md-auto submit-payment-btn-more-mon book-now-button-confirm-page"
                      style="max-width: 500px;" name="payment_method" value="stripe" id="confirmBtn">
                      Pay with Stripe
                    </button>
                     <button class="btn btn-primary btn-lg py-3 px-4 w-100 w-md-auto submit-payment-btn-more-mon book-now-button-confirm-page"
                      style="max-width: 500px;" name="payment_method" value="crypto" id="confirmBtn">
                     Pay with Crypto
                    </button>
                  </div> 

                  <div class="d-flex justify-content-between">
                  <div class="total-amount-be-paid">Total Amount</div>
                  <div class="amount-money-count-only-items">
                      <?php
                      $currency = get_option('travelx_required_currency');
                      $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                     
                      $roomCount = 1;
                      $nightCount = 1;

                      if (isset($_GET['checkin']) && isset($_GET['checkout'])) {
                          $checkinDate = new DateTime($_GET['checkin']);
                          $checkoutDate = new DateTime($_GET['checkout']);
                          $nightCount = $checkoutDate->diff($checkinDate)->days;
                      }
                      $basePrice = $roomPrice * $roomCount * $nightCount;
                      $tax = get_option('travel_tax_and_service_fees');
                      $totalAmount = $basePrice + $tax;
                      echo $symbol .number_format($totalAmount, 2);
                      ?>
                  </div>
            </div>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <?php
        
        $roomCount = 1;
        $nightCount = 1;

        if (isset($_GET['checkin']) && isset($_GET['checkout'])) {
            $checkinDate = new DateTime($_GET['checkin']);
            $checkoutDate = new DateTime($_GET['checkout']);
            $nightCount = $checkoutDate->diff($checkinDate)->days;
        }

        // Total base price
        $basePrice = $roomPrice * $roomCount * $nightCount;

        // Tax & fees (example)
        $tax = get_option('travel_tax_and_service_fees');

        // Final total
        $totalAmount = $basePrice + $tax;?>

        <div class="bg-white rounded p-4 mb-4 price-summery-cencillation-price">
          <h2 class="fs-5 fw-bold mb-3 details-fare-det-tra-offert">Price Summary</h2>
          <div>
            <div class="d-flex justify-content-between mb-2">
              <div class="count-fare-flight-mode-money-co">
                  <?php echo $roomCount . ' room x ' . $nightCount . ' night' . ($nightCount > 1 ? 's' : ''); ?>
              </div>
              <div class="count-fare-flight-mode-money-co">
                  $<?php echo number_format($basePrice, 2); ?>
              </div>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <div class="count-fare-flight-mode-money-co">Tax and service fees</div>
                <div class="count-fare-flight-mode-money-co">
                    $<?php echo number_format($tax, 2); ?>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <div class="total-amount-be-paid">Total Amount to be paid</div>
                <div class="amount-money-count-only-items">
                    $<?php echo number_format($totalAmount, 2); ?>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
 
</script>
<script>
jQuery(document).ready(function ($) {
    $(".book-now-button-confirm-page").click(function (e) {
        e.preventDefault();

        // Payment method from the clicked button
        let paymentMethod = $(this).val();
        let baseUrl = window.location.origin;
        let checkoutUrl = baseUrl + "/checkout.php";

        // Collect form inputs
        let title = $("#title").val().trim();
        let firstName = $("#firstName").val().trim();
        let lastName = $("#lastName").val().trim();
        let phone = $("#phone").val().trim();
        let email = $("#email").val().trim();
        let specialRequests = $(".requests-input").val().trim();
        let termsAccepted = $("#terms").is(":checked");
        let rateBasisId = $("input[name='rateBasisId']").val();
        let productId = $("input[name='pid']").val();
        let netPrice = $("input[name='netPrice']").val(); 
        let faretype = $("input[name='faretype']").val(); 
        

        // Get URL params
        let urlParams = new URLSearchParams(window.location.search);
        let location = urlParams.get("location") || "";
        let checkin = urlParams.get("checkin") || "";
        let checkout = urlParams.get("checkout") || "";
        let rooms = urlParams.get("rooms") || "";
        let hotelId = urlParams.get("hotel_id") || "";
        let tokenId = urlParams.get("token_id") || "";
        let sessionId = urlParams.get("session_id") || "";

        // Validate basic fields
        if (!firstName || !lastName || !phone || !email) {
            alert("Please fill in all required fields.");
            return;
        }

        if (!termsAccepted) {
            alert("Please accept the terms to proceed.");
            return;
        }

        if (!rateBasisId || !netPrice) {
            alert("Missing price or room details.");
            return;
        }

        // Guest selection
        let selectedGuests = [];
        $("input[name='selected_guest[]']:checked").each(function () {
            selectedGuests.push($(this).val());
        });

        const [room, adult, child] = rooms.split('-');
        const guests_type = {};
        const guests_number = {};
        let guests_ids = [];
        let guestCnt = 1;

        selectedGuests.forEach(guest => {
            const [type, number] = guest.split('_');
            if (!guests_type[type]) {
                guests_type[type] = 0;
                guests_number[type] = [];
            }
            guests_type[type]++;
            guests_number[type].push(number);
            guests_ids.push(number);
        });

        if (guests_type.adult !== undefined) {
            guestCnt += guests_type.adult;
        }
        if (guests_type.child !== undefined) {
            guestCnt += guests_type.child;
        }

        let roomGuest = parseInt(adult) + parseInt(child);

        if (guestCnt != roomGuest) {
            alert("Guest count does not match selected room capacity.");
            return;
        }

        // Submit AJAX request
        $.ajax({
            url: checkoutUrl,
            type: "POST",
            dataType: "json",
            data: {
                action: "hotel_book_now",
                title,
                firstName,
                lastName,
                phone,
                email,
                specialRequests,
                location,
                checkin,
                checkout,
                hotelId,
                tokenId,
                sessionId,
                productId,
                rooms,
                rateBasisId,
                faretype,
                netPrice,
                guests: guests_ids,
                paymentMethod
            },
            success: function (response) {
                if (response.status === "success" && response.payment_url) {
                    window.location.href = response.payment_url;
                } else {
                    alert("Booking failed: " + (response.message || "Unknown error."));
                }
            },
            error: function (xhr, status, error) {
                console.error("XHR Error:", xhr.responseText);
                alert("Something went wrong. Please try again.");
            }
        });
    });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const showMoreBtn = document.querySelector('.show-more-facilities');
    let visibleCount = 5;
    const increment = 5;
    const allItems = document.querySelectorAll('.hidden-facility');

    if (showMoreBtn) {
      showMoreBtn.addEventListener('click', function () {
        const hiddenItems = Array.from(allItems).filter(item => item.style.display === 'none');

        if (hiddenItems.length > 0) {
          // Show 5 more
          hiddenItems.slice(0, increment).forEach(item => item.style.display = 'list-item');

          if (Array.from(allItems).filter(item => item.style.display === 'none').length === 0) {
            showMoreBtn.textContent = 'Show less';
          }
        } else {
          // Collapse all except first 5
          allItems.forEach((item, index) => {
            item.style.display = index < (allItems.length - visibleCount) ? 'none' : 'list-item';
          });
          showMoreBtn.textContent = 'Show more';
        }
      });
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const guestTypeSelect = document.getElementById('guest_type');
    const guestTitleSelect = document.getElementById('guest_title');

    const allTitles = [
      { value: '', text: 'Select' },
      { value: 'Mr', text: 'Mr', types: ['adult'] },
      { value: 'Mrs', text: 'Mrs', types: ['adult'] },
      { value: 'Master', text: 'Boy (Master)', types: ['child'] },
      { value: 'Miss', text: 'Girl (Miss)', types: ['child'] }
    ];

    function updateTitleOptions(guestType) {
      // Clear current options
      guestTitleSelect.innerHTML = '';

      // Filter and append options
      allTitles.forEach(option => {
        if (!option.types || option.types.includes(guestType)) {
          const opt = document.createElement('option');
          opt.value = option.value;
          opt.textContent = option.text;
          guestTitleSelect.appendChild(opt);
        }
      });
    }

    guestTypeSelect.addEventListener('change', function () {
      updateTitleOptions(this.value);
    });
  });
</script>

<?php get_footer(); ?>
