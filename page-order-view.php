<?php 
/**
 * Template Name: Order View Page
 * Description: A custom page template for special layouts.
 */

// If download requested, generate PDF and exit
require_once get_template_directory() . '/fpdf.php';


get_header(); 
?>
<?php
$current_url = $_SERVER['REQUEST_URI'];
$separator = (strpos($current_url, '?') !== false) ? '&' : '?';
?>

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
        <div><a href="<?php echo htmlspecialchars($current_url . $separator . 'download=1'); ?>" class="button">Download PDF</a></div>
        <?php

            $data = $results[0];
            $UniqueID = $data['booking_id'];
            $transactionId = $data['transaction_id'];
            $paymentStatus = $data['payment_status'];
             
            $userFlightBookingDetail = get_flight_booking_details_by_api($UniqueID);
            //echo "<pre>"; print_r($userFlightBookingDetail); die;
            $flightDetail = $userFlightBookingDetail['TripDetailsResponse']['TripDetailsResult']['TravelItinerary'];

            //echo "<pre/>"; print_r($flightCustomerDetail); die;
            
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
                <div class="col-md-6">
                    <strong>Ticket Status:</strong> <?= esc_html($flightDetail['TicketStatus'] ?? ''); ?>
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
                    <strong>Origin:</strong> <?= esc_html(getCityNameByAirPortCode(($flightDetail['Origin'] ?? ''))); ?>
                </div>
                <div class="col-md-6">
                    <strong>Destination:</strong> <?= esc_html(getCityNameByAirPortCode($flightDetail['Destination'] ?? '')); ?>
                </div>
            </div>

           
            <!-- Flight Segment -->
            <?php
            $flight = $flightDetail['ItineraryInfo']['ReservationItems'][0]['ReservationItem'] ?? null;
            if ($flight) :?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Flight Number:</strong> <?= esc_html($flight['MarketingAirlineCode'] . ' ' . $flight['FlightNumber']); ?>
                    </div>
                    <div class="col-md-6">
                        <strong>DepartureCity:</strong> 
                        <?= esc_html(getCityNameByAirPortCode($flight['DepartureAirportLocationCode']));?>
                    </div>
                     <div class="col-md-6">
                        <strong>DepartureTime:</strong> 
                        <?= $flight['DepartureDateTime'];?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ArrivalCity:</strong> 
                        <?= esc_html(getCityNameByAirPortCode($flight['ArrivalAirportLocationCode']));?>
                    </div>
                    <div class="col-md-6">
                        <strong>ArrivalTime:</strong> 
                        <?= esc_html($flight['ArrivalDateTime']);?>
                    </div>
                </div>
                <div class="row mb-3">
                     <div class="col-md-6">
                         <div class="col-md-6">
                        <strong>Baggage:</strong> <?= esc_html($flight['Baggage'] ?? 'N/A'); ?>
                    </div>
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

            <?php

            //Start of download file as pdf//
            if (isset($_GET['download']) && $_GET['download'] == '1') {
    
            if (ob_get_length()) {
                ob_end_clean();
            }

            // --- Determine Filename based on current date and time ---

            $currentDateTime = date('Y-m-d_H-i');
            $pdfFileName = 'flight_booking_details_' . $currentDateTime . '.pdf';
            $departTime = esc_html($flight['DepartureAirportLocationCode'] . ' — ' . $flight['DepartureDateTime']);
        
            $bookingDetails = [
                'Booking Status' => esc_html($flightDetail['BookingStatus'] ?? ''),
                'Booking ID' => esc_html($flightDetail['UniqueID'] ?? ''),
                'Payment Status' => esc_html($paymentStatus ?? ''),
                'Transaction ID' => esc_html($transactionId ?? ''),
                'Origin'=>esc_html(getCityNameByAirPortCode($flightDetail['Origin'] ?? '')),
                'Destination' => esc_html($flightDetail['Destination'] ?? ''),
                'Flight Number' => esc_html($flight['MarketingAirlineCode'] . ' ' . $flight['FlightNumber']),
                'DepartureCity' => esc_html(getCityNameByAirPortCode($flight['DepartureAirportLocationCode'])),
                'DepartureTime' => esc_html(str_replace('T', ' ', $flight['DepartureDateTime'])),
                'ArrivalCity' => esc_html(getCityNameByAirPortCode($flight['ArrivalAirportLocationCode'])),
                'ArrivalTime' => esc_html(str_replace('T', ' ', $flight['ArrivalDateTime'])),
                'Baggage' => esc_html($flight['Baggage'] ?? 'N/A'), // Added sample baggage detail
                'Total Fare' => esc_html($fare['Amount']) . ' ' . esc_html($fare['CurrencyCode']),
                'Fare Type' => esc_html($flightDetail['FareType'] ?? '')
            ];
    // --- End of Flight Booking Details ---

    // Create a new FPDF instance
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 10); // Set margins for better layout

    // --- Title ---
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 15, 'Flight Booking Details', 0, 1, 'C');
    $pdf->Ln(6); // Add some space

    // --- Display Booking Details ---
    $pdf->SetFont('Arial', '', 12); // Regular font for details

    foreach ($bookingDetails as $label => $value) {
        $pdf->SetFont('Arial', 'B', 12); // Bold for label
        $pdf->Cell(50, 8, $label . ':', 0, 0, 'R'); // Label column (fixed width)
        $pdf->SetFont('Arial', '', 12); // Regular for value
        $pdf->MultiCell(0, 8, $value, 0, 'L'); // Value column (multicell for wrapping, 0 width for remaining space)
    }

    // --- Title ---
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 15, 'Guest Booking Details', 0, 1, 'C'); // Centered title, new line
    $pdf->Ln(6); // Add some space

    // --- Display Booking Details ---
    $pdf->SetFont('Arial', '', 12); // Regular font for details

     foreach ($results as $booking){
        //echo "<pre/>"; print_r($booking);

            $guestDetailsData = [
                    'firstname' => esc_html($booking['first_name']),
                    'lastname' => esc_html($booking['last_name']),
                    'guestemail' => esc_html($booking['email']),
                    'guestcontact' => esc_html($booking['phone']),
                    'passengertype'=>esc_html($booking['passenger_type']),
                    'dateofbirth' => esc_html($booking['dob'])
                ];
     }

    //THis is for guest detail//
    $guestDetails = [
        'First Name' => esc_html($guestDetailsData['firstname'] ?? ''),
        'Last Name' => esc_html($guestDetailsData['lastname'] ?? ''),
        'Email' => esc_html($guestDetailsData['guestemail'] ?? ''),
        'Phone' => esc_html($guestDetailsData['guestcontact'] ?? ''),
        'Passenger Type'=>esc_html($guestDetailsData['passengertype'] ?? ''),
        'DOB' => esc_html($guestDetailsData['dateofbirth'] ?? '')
    ];

    foreach ($guestDetails as $label => $value) {
        $pdf->SetFont('Arial', 'B', 12); // Bold for label
        $pdf->Cell(50, 8, $label . ':', 0, 0, 'R'); // Label column (fixed width)
        $pdf->SetFont('Arial', '', 12); // Regular for value
        $pdf->MultiCell(0, 8, $value, 0, 'L'); // Value column (multicell for wrapping, 0 width for remaining space)
    }

    // Add a footer or other information if needed
    $pdf->Ln(10);   
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Generated on ' . date('Y-m-d H:i:s'), 0, 0, 'R');

     // Set the filename directly in the Content-Disposition header.
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $pdfFileName . '"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    // Output the PDF, forcing a download ('D').
    $pdf->Output($pdfFileName, 'D');

    // Terminate script execution immediately after outputting the PDF.
    exit;


}
            ?>
        </div>
        <?php 


        else : ?>
            <p>No flight details available.</p>
        <?php endif; ?>
        <h2 class="mb-4">Guest Details</h2>
        <?php foreach ($results as $booking): 
            //echo "<pre/>";print_r($booking); die;?>
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
                    <strong>Trip Type:</strong> <?= esc_html($booking['trip_type']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Nationality:</strong> <?= esc_html($booking['nationality']); ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Email:</strong> <?= esc_html($booking['email']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Phone:</strong> <?= esc_html($booking['phone']); ?>
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
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Destination From:</strong> <?= esc_html(getCityNameByAirPortCode($booking['destination_from'])); ?>
                </div>
                <div class="col-md-6">
                    <strong>Destination To:</strong> <?= esc_html(getCityNameByAirPortCode($booking['destination_to'])); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Departure Date:</strong> <?= esc_html($booking['departure_date']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Travel Class:</strong> <?= esc_html($booking['travel_class']); ?>
                </div>
            </div>   

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Amount:</strong> <?= esc_html($booking['amount']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Payment Status:</strong> <?= esc_html($booking['payment_status']); ?>
                </div>
            </div>  
        </div>

         <?php
                    //Customer detail//
                    $flightCustomerDetail = $userFlightBookingDetail['TripDetailsResponse']['TripDetailsResult']['TravelItinerary']['ItineraryInfo']['CustomerInfos'][0];
                    
            ?>
            <h2 class="mb-4">Customer Details</h2>
            <?php foreach ($flightCustomerDetail as $bookingcustomer):?>
        <div class="booking-box border p-4 rounded mb-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>PassengerType:</strong> <?= esc_html($bookingcustomer['PassengerType']); ?>
                </div>  
                <div class="col-md-6">
                    <strong>PassportNumber:</strong> <?= esc_html($bookingcustomer['PassportNumber']); ?>
                </div>
            </div>

            <div class="row mb-3">
                 <div class="col-md-6">
                    <strong>PassengerTitle:</strong> <?= esc_html($bookingcustomer['PassengerTitle']); ?>
                </div>
                <div class="col-md-6">
                    <strong>PassengerName:</strong> <?= esc_html($bookingcustomer['PassengerFirstName']); ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>PassengerNationality:</strong> <?= esc_html(getCityNameByAirPortCode($bookingcustomer['PassengerNationality'])); ?>
                </div>
                <div class="col-md-6">
                    <strong>PhoneNumber:</strong> <?= esc_html($bookingcustomer['PhoneNumber']); ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>EticketNumber:</strong> <?= esc_html($bookingcustomer['eTicketNumber']); ?>
                </div>
                <div class="col-md-6">
                    <strong>EmailAddress:</strong> <?= esc_html($bookingcustomer['EmailAddress']); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Gender:</strong> <?= esc_html($bookingcustomer['Gender']); ?>
                </div>
                <div class="col-md-6">
                    <strong>DOB:</strong> <?= esc_html($bookingcustomer['DateOfBirth']); ?>
                </div>
            </div>
        </div>

        <?php endforeach; ?>


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
