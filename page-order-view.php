<?php 
/**
 * Template Name: Order View Page
 * Description: A custom page template for special layouts.
 */

get_header(); ?>

<?php

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/login')); // Redirect to login if not logged in
        exit;
    }

    // Get current user ID
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;

    // Validate and sanitize order ID from URL
    $flight_id = isset($_GET['flight_id']) ? sanitize_text_field($_GET['flight_id']) : null;
    $hotel_id = isset($_GET['hotel_id']) ? sanitize_text_field($_GET['hotel_id']) : null;

    // If neither is present, show error
    if (empty($flight_id) && empty($hotel_id)) {
        echo '<div class="alert alert-danger">Missing flight or hotel ID.</div>';
        exit;
    }

    if (!empty($hotel_id)) {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $hotel_id)) {
            echo '<div class="alert alert-danger">Invalid hotel ID format.</div>';
            exit;
        }

        global $wpdb;

        // Fetch booking data for current user only (security check)
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM hotel_booking_details WHERE transaction_id = %s",
                $hotel_id
            ),
            ARRAY_A
        );


        // Handle not found
        if (!$results) {
            echo '<div class="alert alert-warning">Booking not found or you do not have permission to view it.</div>';
            exit;
        } else { ?>

        <div class="container mt-5">
            <h2 class="mb-4">Hotel Booking Details</h2>
            <?php $data = $results[0];
                $referenceNum = $data['referenceNum'];
                $supplierConfirmationNum = $data['supplierConfirmationNum'];
                $transaction_id = $data['transaction_id'];
                $payment_status = $data['payment_status'];
                $userBookingDetail = get_booking_details_by_api($referenceNum, $supplierConfirmationNum);

            ?>
                <!-- for common details -->
                <?php if (!empty($userBookingDetail) && is_array($userBookingDetail)) : ?>
                <div class="booking-box border p-4 rounded mb-4">
                    <!-- Top Row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Booking Status:</strong> <?= esc_html($userBookingDetail['status'] ?? ''); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Supplier Confirmation Num:</strong> <?= esc_html($userBookingDetail['supplierConfirmationNum'] ?? ''); ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Booking Reference Num:</strong> <?= esc_html($referenceNum ?? ''); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Hotel Address:</strong><br>
                            <?= esc_html($userBookingDetail['roomBookDetails']['address'] ?? ''); ?>
                        </div>
                    </div>
                    <!-- Hotel Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Hotel Name:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['hotelName'] ?? ''); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Hotel ID:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['hotelId'] ?? ''); ?>
                        </div>
                    </div>

                   <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Transaction ID:</strong> <?= esc_html($transaction_id ?? ''); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong> <?= esc_html($payment_status ?? ''); ?>
                        </div>
                    </div>
                    <!-- Hotel Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>City:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['city'] ?? ''); ?>
                        </div>
                         <div class="col-md-6">
                            <strong>Country:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['country'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Check-In:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['checkIn'] ?? ''); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Check-Out:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['checkOut'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Price and Fare -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Net Price:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['NetPrice'] ?? ''); ?> <?= esc_html($userBookingDetail['roomBookDetails']['currency'] ?? ''); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Fare Type:</strong> <?= esc_html($userBookingDetail['roomBookDetails']['fareType'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Rooms Info (if exists) -->
                    <?php if (!empty($userBookingDetail['roomBookDetails']['rooms']) && is_array($userBookingDetail['roomBookDetails']['rooms'])) : ?>
                        <?php foreach ($userBookingDetail['roomBookDetails']['rooms'] as $index => $room) : ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Room Name:</strong> <?= esc_html($room['name'] ?? ''); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Board Type:</strong> <?= esc_html($room['boardType'] ?? ''); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- Cancellation Policy -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Cancellation Policy:</strong><br>
                            <?= esc_html($userBookingDetail['roomBookDetails']['cancellationPolicy'] ?? ''); ?>
                        </div>  
                    </div>
                </div>
                <?php else : ?>
                    <p>No booking details found.</p>
                <?php endif; ?>
                <h2 class="mb-4">Guest Details</h2>
                <?php foreach ($results as $booking): ?>
                <div class="booking-box border p-4 rounded mb-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>First Name:</strong> <?= esc_html($booking['firstName']); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Last Name:</strong> <?= esc_html($booking['lastName']); ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Email:</strong> <?= esc_html($booking['customer_email']); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Phone:</strong> <?= esc_html($booking['phone']); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
<?php } }?>

<!-- =====================fOR fLIGHT details======================== -->
<?php

    if (!empty($flight_id)) {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $flight_id)) {
            echo '<div class="alert alert-danger">Invalid Flight ID format.</div>';
            exit;
        }

    global $wpdb;

    // Fetch booking data for current user only (security check)

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM flight_booking_details WHERE session_id = %s",
            $flight_id
        ),
        ARRAY_A
    );


    // Handle not found
    if (!$results) {
        echo '<div class="alert alert-warning">Booking not found or you do not have permission to view it.</div>';
        exit;
    } else { ?>

    <div class="container mt-5">
        <h2 class="mb-4">Flight Booking Details</h2>

        <?php
            $data = $results[0];
            $UniqueID = $data['booking_id'];
            $transactionId = $data['transaction_id'];
            $paymentStatus = $data['payment_status'];
             
            // $supplierConfirmationNum = $data['supplierConfirmationNum'];
            // $transaction_id = $data['transaction_id'];
            // $payment_status = $data['payment_status'];

            $userFlightBookingDetail = get_flight_booking_details_by_api($UniqueID);
            echo "<pre>"; print_r($userFlightBookingDetail); die;
            $flightDetail = $userFlightBookingDetail['TripDetailsResponse']['TripDetailsResult']['TravelItinerary'];
        ?>
        <!-- for common details -->
        <?php if (!empty($flightDetail)) : ?>
        <div class="booking-box border p-4 rounded mb-4">
            <!-- Top Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Booking Status:</strong> <?= esc_html($flightDetail['BookingStatus'] ?? ''); ?>
                </div>
                <div class="col-md-6">
                    <strong>Booking ID:</strong> <?= esc_html($flightDetail['UniqueID'] ?? ''); ?>
                </div>
            </div>

            <!-- Top Row -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Payment Status:</strong> <?= esc_html($paymentStatus ?? ''); ?>
                </div>
                <div class="col-md-6">
                    <strong>Transaction ID:</strong> <?= esc_html($transactionId ?? ''); ?>
                </div>
            </div>

            <!-- Airports -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Origin:</strong> <?= esc_html($flightDetail['Origin'] ?? ''); ?>
                </div>
                <div class="col-md-6">
                    <strong>Destination:</strong> <?= esc_html($flightDetail['Destination'] ?? ''); ?>
                </div>
            </div>

            <!-- Flight Segment -->
            <?php
            $flight = $flightDetail['ItineraryInfo']['ReservationItems'][0]['ReservationItem'] ?? null;
            if ($flight) : ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Flight Number:</strong> <?= esc_html($flight['MarketingAirlineCode'] . ' ' . $flight['FlightNumber']); ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Departure:</strong> <?= esc_html($flight['DepartureAirportLocationCode'] . ' — ' . $flight['DepartureDateTime']); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Arrival:</strong> <?= esc_html($flight['ArrivalAirportLocationCode'] . ' — ' . $flight['ArrivalDateTime']); ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Baggage:</strong> <?= esc_html($flight['Baggage'] ?? 'N/A'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Fare Info -->
            <?php
            $fare = $flightDetail['ItineraryInfo']['ItineraryPricing']['TotalFare'] ?? null;
            if ($fare) : ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Total Fare:</strong>
                        <?= esc_html($fare['Amount']) . ' ' . esc_html($fare['CurrencyCode']); ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Fare Type:</strong> <?= esc_html($flightDetail['FareType'] ?? ''); ?>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
        <?php else : ?>
            <p>No flight details available.</p>
        <?php endif; ?>
        <h2 class="mb-4">Guest Details</h2>
        <?php foreach ($results as $booking): ?>
        <div class="booking-box border p-4 rounded mb-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>First Name:</strong> <?= esc_html($booking['first_name']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Last Name:</strong> <?= esc_html($booking['last_name']); ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Email:</strong> <?= esc_html($booking['email']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Phone:</strong> <?= esc_html($booking['dob']); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Passenger Type:</strong> <?= esc_html($booking['passenger_type']); ?>
                </div>
                <div class="col-md-6">
                    <strong>DOB:</strong> <?= esc_html($booking['dob']); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php } } ?>

<style>
    strong.Booking-detail.mb-4 {
     font-family: Inter;
    font-weight: 600;
    font-size: 24px;
    line-height: 60px;
    letter-spacing: 0%;
}
    .booking-box {
      background-color: #f8f9fa;
      border: 2px solid #dee2e6;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .label-text {
      font-weight: bold;
    }
    .value-box {
      background-color: #ffffff;
      padding: 8px 12px;
      border: 1px solid #ced4da;
      border-radius: 6px;
    }
</style>

<?php get_footer(); ?>
