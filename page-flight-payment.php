<?php 
/**
 * Template Name: Flight payment Page
 * Description: A custom page template for special layouts.
 */
get_header(); 

    $tripArgs = [
        'tripType' => $_GET['tripType'] ?? 'OneWay',
        'origin' => $_GET['origin'] ?? '',
        'destination' => $_GET['destination'] ?? '',
        'departureDate' => $_GET['departureDate'] ?? '',
        'returnDate' => $_GET['returnDate'] ?? '',
        'class' => $_GET['class'] ?? 'Economy',
        'adults' => $_GET['adults'] ?? 1,
        'children' => $_GET['children'] ?? 0,
        'infants' => $_GET['infants'] ?? 0,
        'session_id' => $_GET['session_id'] ?? 0,
        
    ];

    $selectedFlightEncoded = $_GET['flightData'] ?? '';

    // Decode the flight data
    $flightData = [];
    if (!empty($selectedFlightEncoded)) {
        $decoded = base64_decode($selectedFlightEncoded);
        $flightData = json_decode($decoded, true);
    }

    $fare = $flightData['FareItinerary'] ?? [];

    // STEP 1: Segment details
    $segment = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][0]['FlightSegment'] ?? [];

    // STEP 2: Baggage & Penalty (use first FareBreakdown for reference)
    $fareBreakdown = $fare['AirItineraryFareInfo']['FareBreakdown'][0] ?? [];
    $baggage = $fareBreakdown['Baggage'][0] ?? '0PC';
    $cabinBaggage = $fareBreakdown['CabinBaggage'][0] ?? 'SB';
    $penalty = $fareBreakdown['PenaltyDetails'] ?? [];
    $refundAllowed = !empty($penalty['RefundAllowed']) ? 'Yes' : 'No';
    $refundFee = $penalty['RefundPenaltyAmount'] ?? '0.00';
    $changeAllowed = !empty($penalty['ChangeAllowed']) ? 'Yes' : 'No';
    $changeFee = $penalty['ChangePenaltyAmount'] ?? '0.00';

    // STEP 3: Passenger Count
    $adults = (int) $tripArgs['adults'];
    $children = (int) $tripArgs['children'];
    $infants = (int) $tripArgs['infants'];
    $payingPassengers = $adults + $children;

    $totalBaseFare = 0;
    $totalTax = 0;
    $totalFare = 0;
    $passengerFares = [];
    $fareBreakdowns = $fare['AirItineraryFareInfo']['FareBreakdown'] ?? [];

    if (!empty($fareBreakdowns[0])) {
        $fareInfo = $fareBreakdowns[0]['PassengerFare'] ?? [];

        $unitBase = isset($fareInfo['BaseFare']['Amount']) ? (float)$fareInfo['BaseFare']['Amount'] : 0;
        $unitTotal = isset($fareInfo['TotalFare']['Amount']) ? (float)$fareInfo['TotalFare']['Amount'] : 0;
        $unitTax = $unitTotal - $unitBase;

        $totalBaseFare = $unitBase * $payingPassengers;
        $totalTax = $unitTax * $payingPassengers;
        $totalFare = $unitTotal * $payingPassengers;

        $passengerFares[] = [
            'type' => 'Passenger',
            'qty' => $payingPassengers,
            'fare' => $unitTotal,
        ];
    }

    // STEP 5: Flight meta info
    $airlineLogo = get_airline_logo_url($segment['MarketingAirlineCode'] ?? '');
    $airlineName = $segment['MarketingAirlineName'] ?? 'Airline';
    $flightNumber = $segment['FlightNumber'] ?? 'N/A';
    $departureTime = date("H:i", strtotime($segment['DepartureDateTime']));
    $arrivalTime = date("H:i", strtotime($segment['ArrivalDateTime']));
    $departureDate = date("D, d M Y", strtotime($segment['DepartureDateTime']));
    $origin = $segment['DepartureAirportLocationCode'] ?? '';
    $destination = $segment['ArrivalAirportLocationCode'] ?? '';
    $duration = $segment['JourneyDuration'] ?? 0;
    $durationFormatted = sprintf("%02dh %02dm", floor($duration / 60), $duration % 60);
?>

<div class="container-fluid heading-blue-booking-things"></div>
<main class="container py-4">
    <h1 class="mb-4 conform-booking-flight-detail-travel">Confirm Your Booking</h1>
    <div class="row  mt-1">
        <!-- Left Column (8 cols) -->
        <div class="col-md-8">
            <!-- Flight Details Start-->
            <div class="bg-white rounded p-4 mb-4 dustination-and-weight-condition-in-travelling">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="<?php echo get_template_directory_uri(); ?>/photos/flight.png" alt="flight">
                    <div class="flight-dustination-into-travelling-fl">
                        <?php echo esc_html("{$origin} → {$destination} | {$departureDate}"); ?>
                    </div>
                </div>
                <div class="no-stop-text-secondary mb-3">
                    Non Stop | All departure/arrival times are in local time
                </div>
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="airline-logo">
                        <div class="airline-inner">
                            <img src="<?php echo esc_url($airlineLogo); ?>" alt="">
                        </div>
                    </div>
                    <div>
                        <div class="flight-no-flight-detail">
                            <?php echo esc_html("{$airlineName} | {$flightNumber}"); ?>
                        </div>
                        <div class="text-secondary-boding">
                            Aircraft: <?php echo esc_html($segment['OperatingAirline']['Equipment'] ?? ''); ?>
                        </div>
                    </div>
                    <div class="ms-auto text-end">
                        <div class="eco-sev-both"><?php echo esc_html($tripArgs['class']); ?></div>
                    </div>
                </div>

                <div class="items-selection-flight-dur-dus-weight">
                    <div class="row mb-2 airport-detail-weight-detail-time-det">
                        <div class="col-3 depart-day-time-flight-1">
                            <div class="text-secondary-day-date-mon">
                                Depart on - <?php echo esc_html($departureDate); ?>
                            </div>
                            <div class="hours-flight-1-1"><?php echo esc_html($departureTime); ?></div>
                            <div class="location-del-flight"><?php echo esc_html($origin); ?></div>
                        </div>

                        <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="text-secondary-only-time">
                                <?php echo esc_html($durationFormatted); ?>
                            </div>
                            <div class="text-secondary-durition-fli">Duration</div>
                        </div>

                        <div class="col-3 depart-day-time-flight-1">
                            <div class="text-secondary-day-date-mon">
                                Arrives - <?php echo esc_html($departureDate); ?>
                            </div>
                            <div class="hours-flight-1-1">
                                <?php echo esc_html($arrivalTime); ?>
                            </div>
                            <div class="location-del-flight"><?php echo esc_html($destination); ?></div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 small baggages-weight--check-bagger">
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?php echo get_template_directory_uri(); ?>/photos/items.png" alt="items" id="bag-of-items">
                                <span class="cabin-bags-kg">Cabin Baggage: <span class="weight-only-sp"><?php echo esc_html("Cabin: {$cabinBaggage}, Checked: {$baggage}"); ?></span>   </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?php echo get_template_directory_uri(); ?>/photos/bag.png" alt="bag" id="bag-of-weight">
                            <span class="cabin-bags-kg">Check-in Baggage: <span class="weight-only-sp">
                            15 Kg (01 Piece only)</span></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Flight Details End-->

            <!-- Cancellation Policy -->
            <div class="bg-white rounded p-4 mb-4 price-summery-cencillation-price-cen">
                <h2 class="fs-5 fw-bold mb-3 policies-of-censillation-charge">Cancellation & Date Change Policy</h2>
                <div class="mb-4 time-frame-to-censillation">
                    <div class="row time-date-flight-cencillation-items">
                        <div class="col-md-8 schedule-flight-depart">
                            <h3 class="time-fair-charge-items mb-1">Refund Policy</h3>
                            <div class="text-secondary-scheduled-depart">(Based on airline fare rules)</div>
                        </div>
                        <div class="col-md-4">
                            <h3 class="time-fair-charge-items mb-1">Refund Allowed</h3>
                            <div class="text-secondary-passanger-count-on-page">
                                <?php echo esc_html($refundAllowed); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row py-2 time-date-flight-cencillation-items">
                        <div class="col-md-8  price-fare-flight-sec-thi-item">Refund Fee</div>
                        <div class="col-md-4 px-3 price-fare-flight-sec-thi-item">
                            <?php
                            $currency = get_option('travelx_required_currency');
                            $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                            echo $symbol . number_format((float)$refundFee, 2);
                            ?>  
                        </div>
                    </div>
                </div>
                <div class="flight-dot-end-regular-charge">
                    <div class="row  time-date-flight-cencillation-items">
                        <div class="col-md-8 schedule-flight-depart">
                            <h3 class="time-fair-charge-items mb-1">Change Policy</h3>
                            <div class="text-secondary-no-delay">(Based on airline fare rules)</div>
                        </div>
                        <div class="col-md-4">
                            <h3 class="time-fair-charge-items mb-1">Change Allowed</h3>
                            <div class="text-secondary-fee-passsanger">
                                <?php echo esc_html($changeAllowed); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row py-2 time-date-flight-cencillation-items ">
                        <div class="col-md-8 px-3 price-fare-flight-sec-thi-item">Change Fee</div>
                        <div class="col-md-4 px-3 price-fare-flight-sec-thi-item">
                           <?php
                            if ($changeAllowed === 'Yes') {
                                $currency = get_option('travelx_required_currency');
                                $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                echo $symbol . esc_html($changeFee);
                            } else {
                                echo 'Not Changeable';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="info-impo-text-secondary mt-3">
                    <span class="Important-info-note">Important:</span> The airline fees are fetched from the API and are subject to change without prior notice. Final fees are confirmed upon ticket booking.
                </div>
            </div>
            <!-- Cancellation Policy end -->

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
                    $phone      = esc_attr( get_user_meta( $current_user->ID, 'billing_phone', true ) ); // WooCommerce
                }
            ?>
            <!-- Traveller Details Start-->
            <div class="rounded p-4 mb-4 Traveller-details-in-booking-sec">
                <h2 class="fs-5 fw-bold mb-3 details-fare-det-tra-offert">Traveller Details</h2>
               
                <form id="travelerForm">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">Gender</label>
                            <select name="title" id="title" class="form-select hotel-payment-form-control">
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">First Name</label>
                            <input type="text" class="form-control" id="firstName" value="<?php echo $first_name; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small form-name-email-detail-all-th">Last Name</label>
                            <input type="text" class="form-control" id="lastName" value="<?php echo $last_name; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" required>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">Nationality</label>
                            <input type="text" class="form-control" id="nationality" placeholder="e.g., Indian" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">Email Address</label>
                            <input type="email" class="form-control" id="email" value="<?php echo $email; ?>" required>
                        </div>
                    </div>
                <!-- Hidden inputs to pass selected flight & trip args -->
                    <input type="hidden" name="selectedFlightEncoded" id="selectedFlightEncoded" value="<?php echo esc_attr($selectedFlightEncoded); ?>">
                    <input type="hidden" name="tripArgsEncoded" id="tripArgsEncoded" value="<?php echo esc_attr(base64_encode(json_encode($tripArgs))); ?>">
                    <input type="hidden" name="netPrice" id="netPrice" value="<?php echo esc_attr($totalFare); ?>">
                </form>
                <div class="impo-ticket-sms-text-secondary">
                    Your ticket will be delivered through WhatsApp, SMS, phone calls, email, and other available
                    channels
                </div>
                <!-- Traveller Details End-->
   
                <!-- Traveller Guest Details Start-->
                <div class="add-guest-only-sec">
                    <button class="hotel-btn-submit add-guest-main-btn-1" onclick="hotelPaymentOpenForm()">
                      Add Guest <span style="margin-right: 8px; font-size: 14px;">➕</span>
                    </button>
                    <div id="guestNameDisplay" class="appear-guest-name"></div>
                    <div id="guestListContainer">
                        <ul id="guestList" style="list-style: none; padding: 0;"></ul>
                    </div>
                    <!-- Popup Form -->
                    <div id="hotelPaymentPopupForm" class="hotel-payment-popup-form-container">
                        <div class="hotel-payment-popup-form-content">
                        <span class="hotel-payment-close-btn" onclick="hotelPaymentCloseForm()">&times;</span>
                        <h4>Add Guest for Flight Booking</h4>
                            <form id="hotelPaymentForm">
                                <div class="hotel-payment-form-group">
                                    <label>Guest Type:</label>
                                    <select class="form-select" id="guest_type" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="adult">Adult</option>
                                        <option value="child">Child</option>
                                        <option value="infant">Infants</option>
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
                                    <input type="text" id="first_name" class="hotel-payment-form-control" required />
                                </div>
                                <div class="hotel-payment-form-group">
                                    <label>Last Name:</label>
                                    <input type="text" id="last_name" class="hotel-payment-form-control" required />
                                </div>
                                <div class="hotel-payment-form-group">
                                    <label>Date of Birth:</label>
                                    <input type="date" id="guestDob" class="hotel-payment-form-control styled-date" required />
                                </div>
                                <div class="hotel-payment-form-group">
                                    <label>Nationality:</label>
                                    <input type="text" id="guestNationality" class="hotel-payment-form-control" required />
                                </div>
                            <?php 
                              if ( is_user_logged_in() ) {  $current_user_id= get_current_user_id(); ?>
                                <input type="hidden" id="user_id" value="<?php echo esc_attr($current_user_id); ?>" 
                                        hidden class="hotel-payment-form-control"/>
                                <input type="hidden" id="g_id" value="" 
                                        hidden class="hotel-payment-form-control"/>
                            <?php  } ?>
                                <button type="submit" class="hotel-payment-btn-submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                 <!-- Traveller Guest Details End-->
            </div>
            <!-- Confirm Button -->
            <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-primary btn-lg py-3 px-4 w-100 w-md-auto book-now-button-confirm-page submit-payment-btn-more-mon" style="max-width: 500px;"  name="payment_method" value="stripe" id="confirmBtn">Pay with Stripe</button>

                <button class="btn btn-primary btn-lg py-3 px-4 w-100 w-md-auto book-now-button-confirm-page submit-payment-btn-more-mon" style="max-width: 500px;" name="payment_method" value="crypto" id="confirmBtn">Pay with Crypto</button>
            </div>
        </div>

        <!-- Right Column (4 cols) Price Summary start-->
        <div class="col-md-4">
            <div class="bg-white rounded p-4 mb-4 price-summery-cencillation-price">
                <h2 class="fs-5 fw-bold mb-3 details-fare-det-tra-offert">Price Summary</h2>
                <div>
                <?php foreach ($passengerFares as $pax): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="count-fare-flight-mode-money-co">
                            <?php echo esc_html($pax['type']); ?> x <?php echo $pax['qty']; ?>
                        </div>
                        <div class="count-fare-flight-mode-money-co">
                            <?php
                                $currency = get_option('travelx_required_currency');
                                $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                echo $symbol . number_format((float)$pax['fare'] * $pax['qty'], 2);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                    <div class="d-flex justify-content-between mb-2 mt-3 pt-2 border-top">
                        <div class="count-fare-flight-mode-money-co">Base Fare</div>
                        <div class="count-fare-flight-mode-money-co">
                            <?php
                                $currency = get_option('travelx_required_currency');
                                $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                echo $symbol . number_format((float)$totalBaseFare, 2);
                            ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <div class="count-fare-flight-mode-money-co">Taxes</div>
                        <div class="count-fare-flight-mode-money-co">
                            <?php
                                $currency = get_option('travelx_required_currency');
                                $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                echo $symbol . number_format((float)$totalTax, 2);
                            ?>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <div class="total-amount-be-paid">Total Amount</div>
                        <div class="amount-money-count-only-items">
                            <?php
                                $currency = get_option('travelx_required_currency');
                                $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                echo $symbol . number_format((float)$totalFare, 2);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <div class="total-amount-be-paid">Total Amount</div>
            <div class="amount-money-count-only-items"><?php
                $currency = get_option('travelx_required_currency');
                $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                echo $symbol . number_format((float)$totalFare, 2);
                ?>  
            </div>
        </div>
    </div>
</main> 

<script>
jQuery(document).ready(function ($) {
    $(".book-now-button-confirm-page").on("click", function (e) {
        e.preventDefault();

        let baseUrl = "<?php echo site_url(); ?>";
        let checkoutUrl = baseUrl + "/flightcheckout.php";
        let paymentMethod = $(this).val();

        // Get user inputs
        let title = $("#title").val();
        let firstName = $("#firstName").val().trim();
        let lastName = $("#lastName").val().trim();
        let email = $("#email").val().trim();
        let dob = $("#dob").val().trim();
        let nationality = $("#nationality").val().trim();

        // Validate required fields
        if (!title || !firstName || !lastName || !dob || !nationality) {
            alert("Please fill in all required fields.");
            return;
        }

        // Validate DOB: must be more than 12 years ago (including month and day)
        let enteredDOB = new Date(dob);
        let today = new Date();

        if (isNaN(enteredDOB.getTime())) {
            alert("Please enter a valid date of birth.");
            return;
        }

        // Calculate age
        let age = today.getFullYear() - enteredDOB.getFullYear();
        let m = today.getMonth() - enteredDOB.getMonth();
        let d = today.getDate() - enteredDOB.getDate();

        if (m < 0 || (m === 0 && d < 0)) {
            age--;
        }

        if (age < 12) {
            alert("You must be older than 12 years.");
            return;
        }

        //  Extract expected guest numbers from URL
        const urlParams = new URLSearchParams(window.location.search);
        const expectedAdults = parseInt(urlParams.get("adults") || 0);
        const expectedChildren = parseInt(urlParams.get("children") || 0);
        const expectedInfants = parseInt(urlParams.get("infants") || 0);

        //  Collect selected guest IDs and types
        let selectedGuests = [];
        let guests_type = { adult: 1, child: 0, infant: 0 };

        $("input[name='selected_guest[]']:checked").each(function () {
            const guestId = $(this).val();
            const guestInfo = $(this).closest('label').find('span').text().toLowerCase();

            if (guestInfo.includes("adult")) {
                guests_type.adult++;
            } else if (guestInfo.includes("child")) {
                guests_type.child++;
            } else if (guestInfo.includes("infant")) {
                guests_type.infant++;
            }

            selectedGuests.push(guestId);
        });


        //  Validate guest type counts
        if (guests_type.adult !== expectedAdults || 
            guests_type.child !== expectedChildren || 
            guests_type.infant !== expectedInfants) {
            alert("Selected guests do not match the flight booking requirements.\n" +
                  `Expected: ${expectedAdults} adults, ${expectedChildren} children, ${expectedInfants} infant\n` +
                  `Selected: ${guests_type.adult} adults, ${guests_type.child} children, ${guests_type.infant} infants`);
            return;
        }

        // Hidden fields
        let selectedFlight = $("#selectedFlightEncoded").val();
        let tripArgs = $("#tripArgsEncoded").val();
        let netPrice = $("#netPrice").val();

        // Send data to PHP
        $.ajax({
            url: checkoutUrl,
            type: "POST",
            dataType: "json",
            data: {
                action: "confirm_flight_booking",
                first_name: firstName,
                last_name: lastName,
                email: email,
                title: title,
                dob: dob,
                nationality: nationality,
                selectedFlightEncoded: selectedFlight,
                tripArgsEncoded: tripArgs,
                netPrice: netPrice,
                guests: selectedGuests,
                paymentMethod: paymentMethod
            },
            success: function (response) {
                console.log(response);
                if (response.status === "success") {
                    window.location.href = response.redirect_url;
                } else {
                    alert("Booking failed: " + response.message);
                }
            },
            error: function () {
                alert("Something went wrong. Please try again.");
            }
        });
    });
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
      { value: 'Master', text: 'Boy (Master)', types: ['child', 'infant'] },
      { value: 'Miss', text: 'Girl (Mis)', types: ['child', 'infant'] }
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
