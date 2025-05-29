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
        'session_id' => $_GET['session_id'] ?? '',
        'fare_source_code' => $_GET['fareSourceCode'] ?? '',
        
    ];

    $fareSourceCode = $_GET['fareSourceCode'] ?? '';
    $sessionId = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';
    $validateFlightData = validateFlightFareMethod($sessionId, $fareSourceCode);

    $IsValid =$validateFlightData['response']['AirRevalidateResponse']['AirRevalidateResult']['IsValid'];
    if($IsValid){
        $flightData = $validateFlightData['response']['AirRevalidateResponse']['AirRevalidateResult']['FareItineraries'];
        $flightDataWithReturn = $validateFlightData['response']['AirRevalidateResponse']['AirRevalidateResult']['FareItineraries'];
        $fare = $flightData['FareItinerary'] ?? [];
    }else{
         echo "<script>
        alert('Response not getting from validateFlightFareMethod Api');
        window.history.back();
    </script>";
    exit;
        // echo "Response not getting from validateFlightFareMethod Api ";
    }   
   
    $isRefundable = $fare['AirItineraryFareInfo']['IsRefundable'];
    $fareType   = $fare['AirItineraryFareInfo']['FareType'];
  
    $isPassportMandatory = $fare['IsPassportMandatory'];
   
    // STEP 1: Segment details
    $segment = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][0]['FlightSegment'] ?? [];
    $segment1 = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'] ?? [];
    $segmentU = $fare['OriginDestinationOptions'] ?? [];

    // STEP 2: Baggage & Penalty (use first FareBreakdown for reference)
    $fareBreakdown = $fare['AirItineraryFareInfo']['FareBreakdown'][0] ?? [];
    $baggage = $fareBreakdown['Baggage'][0] ?? '0PC';
    $cabinBaggage = $fareBreakdown['CabinBaggage'][0] ?? 'SB';
    $penalty = $fareBreakdown['PenaltyDetails'] ?? [];
    $refundAllowed = $fare['AirItineraryFareInfo']['IsRefundable'];

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
?>

<div class="container-fluid heading-blue-booking-things"></div>
<main class="container py-4">
    <h1 class="mb-4 conform-booking-flight-detail-travel">Confirm Your Booking</h1>
    <div class="row  mt-1">
        <!-- Left Column (8 cols) -->
        <div class="col-md-8">
            <!-- Flight Details Start-->
            <div class="bg-white rounded p-4 mb-4 dustination-and-weight-condition-in-travelling">
         
        <?php
                
            foreach($segmentU as $index =>  $flightdata){ 

                $previousArrival = null;
                $totalStops = $flightdata['TotalStops'];
                $flights    = $flightdata['OriginDestinationOption'];

                if($totalStops ==1){ 
                $origin = $flights[0]['FlightSegment']['DepartureAirportLocationCode'] ?? '';
                $destination = $flights[1]['FlightSegment']['ArrivalAirportLocationCode'] ?? '';
                $departureDateTime = date("D, d M Y", strtotime($flights[0]['FlightSegment']['DepartureDateTime']));
                }elseif($totalStops ==2){
                $origin = $flights[0]['FlightSegment']['DepartureAirportLocationCode'] ?? '';
                $destination = $flights[2]['FlightSegment']['ArrivalAirportLocationCode'] ?? '';
                }else{
                $origin = $flights['DepartureAirportLocationCode'] ?? '';
                $destination = $flights['ArrivalAirportLocationCode'] ?? '';
                }
        ?>
        <div class="d-flex align-items-center gap-2 mb-5">
            <img src="<?php echo get_template_directory_uri(); ?>/photos/flight.png" alt="flight">
            <div class="flight-dustination-into-travelling-fl">
            <?php 
                $stopsText = !empty($totalStops) ? "{$totalStops} Stop(s)" : "Non Stop";
                $originCity = getCityNameByAirPortCode($origin);
                $destinationCity = getCityNameByAirPortCode($destination);
                echo esc_html("{$originCity} → {$destinationCity} | {$departureDateTime} | {$stopsText}"); ?>
            </div>
        </div>
        
        <?php
        foreach($flights as $flightrow){
            $airlineName    =$flightrow['FlightSegment']['MarketingAirlineName'];
            $flightNumber   =$flightrow['FlightSegment']['FlightNumber'];
            $departureTime  = date("H:i", strtotime($flightrow['FlightSegment']['DepartureDateTime']));
            $arrivalTime    = date("H:i", strtotime($flightrow['FlightSegment']['ArrivalDateTime']));
            $departureDateTime = date("D, d M Y h:i A", strtotime($flightrow['FlightSegment']['DepartureDateTime']));
            $ArrivalDateTime = date("D, d M Y h:i A", strtotime($flightrow['FlightSegment']['ArrivalDateTime']));
            $duration = $flightrow['FlightSegment']['JourneyDuration'] ?? 0;
            $durationFormatted = sprintf("%02dh %02dm", floor($duration / 60), $duration % 60);
            $origin1 =$flightrow['FlightSegment']['DepartureAirportLocationCode'];
            $destination1 =$flightrow['FlightSegment']['ArrivalAirportLocationCode'];
            if (isset($previousArrival)) {
                $layoverSeconds = strtotime($flightrow['FlightSegment']['DepartureDateTime']) - strtotime($previousArrival);
                $layoverFormatted = sprintf("%02dh %02dm", floor($layoverSeconds / 3600), ($layoverSeconds % 3600) / 60);
            }

        ?>
        <?php if ($layoverFormatted !== null && isset($previousArrival)) { ?>
            <div class="layover-time-section mt-3">
                <span class="change of planes">Change of planes:</span>
                <?php echo $layoverFormatted;?>
            </div>
        <?php }
        $previousArrival = $flightrow['FlightSegment']['ArrivalDateTime']; 
        ?>
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="airline-logo">
                    <div class="airline-inner">
                        <img src="<?php echo esc_url(get_airline_logo_url($flightrow['FlightSegment']['MarketingAirlineCode'])); ?>" alt="">
                    </div>
                </div>
                <div>
                    <div class="flight-no-flight-detail">
                        <?php echo esc_html("{$airlineName} | {$flightNumber}"); ?>
                    </div>
                    <div class="text-secondary-boding">
                        Aircraft: <?php echo esc_html($flightrow['FlightSegment']['OperatingAirline']['Equipment'] ?? ''); ?>
                    </div>
                </div>
                <div class="ms-auto text-end">
                    <div class="eco-sev-both"><?php echo esc_html($tripArgs['class']); ?></div>
                </div>
            </div>

            <div class="flight-segment-wrapper d-flex align-items-start">
                <!-- Departure -->
                <div class="flight-time-info text-end pe-3">
                    <div class="flight-time fw-bold"><?php echo esc_html($departureTime); ?></div>
                    <div class="flight-location fw-semibold">
                        <?php echo esc_html(getCityNameByAirPortCode($origin1));?>
                    </div>,
                    <span>
                        <?php echo esc_html(getAirPortNameByAirPortCode($origin1));?>    
                    </span>
                    <div class="text-secondary-day-date-mon">
                        (Depart on - <?php echo esc_html($departureDateTime); ?>)
                    </div>
                </div>

                <!-- Duration -->
                <div class="flight-connector flex-grow-1 d-flex flex-column align-items-center mx-3 mt-3">
                    <div class="dotted-line flex-grow-1"></div>
                    <div class="flight-duration text-muted my-1"><?php echo esc_html($durationFormatted); ?></div>
                    <div class="dotted-line flex-grow-1"></div>
                </div>

                <!-- Arrival -->
                <div class="flight-time-info text-start ps-3">
                    <div class="flight-time fw-bold">
                        <?php echo esc_html($arrivalTime); ?></div>
                    <div class="flight-location fw-semibold">
                        <?php echo esc_html(getCityNameByAirPortCode($destination1)); ?>
                    </div>,
                    <span>
                        <?php echo esc_html(getAirPortNameByAirPortCode($destination1));?>
                    </span>
                    <div class="text-secondary-day-date-mon">
                        (Arrives - <?php echo esc_html($ArrivalDateTime); ?>)
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 small baggages-weight--check-bagger mb-2 mt-2">
                <div class="d-flex align-items-center gap-2 mt-3">
                    <img src="<?php echo get_template_directory_uri(); ?>/photos/items.png" alt="items" id="bag-of-items">
                    <span class="cabin-bags-kg">Cabin Baggage: <span class="weight-only-sp"><?php echo esc_html("Cabin: {$cabinBaggage}, Checked: {$baggage}"); ?></span></span>
                </div>
            </div>
        <?php  }

        echo "<br><hr><br>";
            }

        ?>
               
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
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">Email Address</label>
                            <input type="email" class="form-control" id="email" value="<?php echo $email; ?>" required>
                        </div>
                    </div>
                    <?php if($isPassportMandatory){ ?>

                 
                     <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                          <label for="passport_number" class="form-label small form-name-email-detail-all-th">Passport Number</label>
                          <input type="text" class="form-control" id="passport_number" placeholder="Enter passport number" required>
                          <div class="invalid-feedback">Please enter a valid passport number.</div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label small form-name-email-detail-all-th">Passport Issue Country</label>
                            <input type="text" class="form-control" id="passport_issue_country" placeholder="Enter issue country (e.g., IN" required>
                        </div>
                       
                        <div class="col-md-4 mb-3 mb-md-0">
                          <label for="passport_expiry_date" class="form-label small form-name-email-detail-all-th">Passport Expiry Date</label>
                          <input type="date" class="form-control" id="passport_expiry_date" required>
                          <div class="invalid-feedback">Please enter a valid future expiry date.</div>
                        </div>
                    </div>
                     <?php   } ?>
                     <input type="hidden" name="isPassportRequired" id="isPassportRequired" value="<?php echo esc_attr($isPassportMandatory); ?>">
                     <input type="hidden" name="isRefundable" id="isRefundable" value="<?php echo esc_attr($isRefundable); ?>">
                     
                <!-- Hidden inputs to pass selected flight & trip args -->
                    <input type="hidden" name="tripArgsEncoded" id="tripArgsEncoded" value="<?php echo esc_attr(base64_encode(json_encode($tripArgs))); ?>">
                    <input type="hidden" name="netPrice" id="netPrice" value="<?php echo esc_attr($totalFare); ?>">
                    <input type="hidden" name="fareType" id="fareType" value="<?php echo esc_attr($fareType); ?>">
                    
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
                                 <?php if($isPassportMandatory){ ?>
                                <div class="hotel-payment-form-group">
                                    <label>Passport Number:</label>
                                    <input type="text" id="guest_passport_number" class="hotel-payment-form-control" required />
                                </div>
                                <div class="hotel-payment-form-group">
                                    <label>Passport Issue Country:</label>
                                    <input type="text" id="guest_issue_country" class="hotel-payment-form-control" required />
                                </div>
                                <div class="hotel-payment-form-group">
                                    <label> Passport Expiry Date:</label>
                                    <input type="date" id="guest_passport_expiry" class="hotel-payment-form-control" required />
                                </div>
                            <?php }?>
                               
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

        let valid = true; // Declare and initialize the validation flag

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
        let isPassportRequired = $("#isPassportRequired").val().trim();
        let isRefundable = $("#isRefundable").val().trim();

        // Passport required validations
        if (isPassportRequired === "1") {
            let passportNumber = $("#passport_number").val().trim();
            let passportIssueCountry = $("#passport_issue_country").val().trim();
            let passportExpiryDate = $("#passport_expiry_date").val().trim();

            if (!title || !firstName || !lastName || !dob || !nationality || 
                !passportNumber || !passportIssueCountry || !passportExpiryDate) {
                alert("Please fill in all required fields.");
                return;
            }

            // Validate passport number
            const passportPattern = /^[a-zA-Z0-9]{8,9}$/;
            if (!passportPattern.test(passportNumber)) {
                $("#passport_number").addClass('is-invalid');
                valid = false;
            } else {
                $("#passport_number").removeClass('is-invalid');
            }

            // Validate passport expiry date
            if (passportExpiryDate) {
                const expiry = new Date(passportExpiryDate);
                const today = new Date();
                const sixMonthsLater = new Date();
                sixMonthsLater.setMonth(today.getMonth() + 6);

                if (expiry <= sixMonthsLater) {
                    $("#passport_expiry_date").addClass('is-invalid');
                    valid = false;
                } else {
                    $("#passport_expiry_date").removeClass('is-invalid');
                }
            }

        } else {
            if (!title || !firstName || !lastName || !dob || !nationality) {
                alert("Please fill in all required fields.");
                return;
            }
        }

        if (!valid) {
            alert("Please correct the errors before continuing.");
            return;
        }

        // Validate DOB: must be more than 12 years ago
        let enteredDOB = new Date(dob);
        let today = new Date();

        if (isNaN(enteredDOB.getTime())) {
            alert("Please enter a valid date of birth.");
            return;
        }

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

        // Extract expected guest numbers from URL
        const urlParams = new URLSearchParams(window.location.search);
        const expectedAdults = parseInt(urlParams.get("adults") || "0");
        const expectedChildren = parseInt(urlParams.get("children") || "0");
        const expectedInfants = parseInt(urlParams.get("infants") || "0");

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

        // Validate guest type counts
        if (
            guests_type.adult !== expectedAdults || 
            guests_type.child !== expectedChildren || 
            guests_type.infant !== expectedInfants
        ) {
            alert(
                "Selected guests do not match the flight booking requirements.\n" +
                `Expected: ${expectedAdults} adults, ${expectedChildren} children, ${expectedInfants} infants\n` +
                `Selected: ${guests_type.adult} adults, ${guests_type.child} children, ${guests_type.infant} infants`
            );
            return;
        }

        // Hidden fields
        let tripArgs = $("#tripArgsEncoded").val();
        let netPrice = $("#netPrice").val();
        let fareType = $("#fareType").val();

        let requestData = {
            action: "confirm_flight_booking",
            first_name: firstName,
            last_name: lastName,
            email: email,
            title: title,
            dob: dob,
            nationality: nationality,
            tripArgsEncoded: tripArgs,
            netPrice: netPrice,
            guests: selectedGuests,
            paymentMethod: paymentMethod,
            isRefundable: isRefundable,
            fareType: fareType
        };

        if (isPassportRequired === "1") {
            requestData.passport_number = $("#passport_number").val().trim();
            requestData.passport_issue_country = $("#passport_issue_country").val().trim();
            requestData.passport_expiry_date = $("#passport_expiry_date").val().trim();
        }

        // Send data to server
        $.ajax({
            url: checkoutUrl,
            type: "POST",
            dataType: "json",
            data: requestData,
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
<style>
    .flight-segment-wrapper {
    background: #f8f8f8;
    flex-direction: column;
    border-radius: 6px;
    padding: 15px 20px;
    font-family: Arial, sans-serif;
}
.flight-time-info.text-start.ps-3 {
    display: flex
;
    gap: 20px;
    margin-top: 20px;
    padding-left: 0px ! IMPORTANT;
}
.flight-segment-wrapper.d-flex.align-items-start .text-end {
    text-align: start !important;
    display: flex
;
    align-items: center;
    gap: 20px;
}
.flight-time {
    font-size: 1.2rem;
}

.flight-location {
    font-size: 1rem;
}
.bg-white.rounded.p-4.mb-4.dustination-and-weight-condition-in-travelling span.change.of.planes {
    color: #0b5ed7;
    font-weight: bold;
}
.flight-airport {
    font-size: 0.85rem;
    color: #666;
}

.flight-connector .dotted-line {
    border-left: 2px dashed #ccc;
    height: 15px;
    width: 1px;
}

.flight-duration {
    font-size: 0.9rem;
    color: #777;
}
.flight-connector.flex-grow-1.d-flex.flex-column.align-items-center.mx-3.mt-3 {
    margin-left: 0px ! IMPORTANT;
}
</style>
<?php get_footer(); ?>
