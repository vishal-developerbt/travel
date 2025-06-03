
<?php 
/**
 * Template Name: My Orders
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
<?php 
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare("
        SELECT * FROM hotel_booking_details h
        INNER JOIN (
            SELECT MAX(id) as latest_id
            FROM hotel_booking_details
            WHERE customer_id = %d
            AND checkin >= CURDATE()
            GROUP BY transaction_id
        ) latest ON h.id = latest.latest_id
        ORDER BY h.id DESC
    ", $user_id),
    ARRAY_A
);

?>
<div class="container order-history-page d-flex"><?php echo do_shortcode('[user_registration_my_account]'); ?>
    <div class="card-dsection-orer-min">
        <div class="profile-card order-maintain-section">
            <div class="profile-card-inner-section order-inner-section">
                <h4 class="user-name-text mx-3 pt-2">UpComming Hotel Booking</h4>
            </div>
            <div class="order-detail-section">
            <?php if (!empty($results)) : ?>
                <?php foreach ($results as $booking) : ?>
                <div class="d-flex justify-content-between order-detail-body-section mb-3">
                    <!-- Left Section -->
                    <div class="d-flex flex-column align-items-start">
                        <div class="d-flex gap-1 align-items-center">
                            <span class="order-booking-id w-auto">Booking ID:</span>
                            <div> <a href="<?php echo esc_url( home_url( '/travel/order-view/?hotel_id=' . $booking['transaction_id'] ) ); ?>">
                            <span class="booking serial-number text-primary fw-bold"><?php echo esc_html($booking['referenceNum']); ?></span></a></div>
                        </div>
                        <div class="d-flex gap-1 align-items-center">
                            <span class="order-booking-id w-auto">Total amount:</span>
                            <div><span class="booking serial-numbers fw-bold"><?= esc_html(get_option('travelx_required_currency')) . ' ' . esc_html($booking['price'] ?? ''); ?></span></div>
                        </div>
                        <div class="d-flex gap-1 align-items-center">
                            <span class="order-booking-id w-auto">Booking Status:</span>
                            <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['booking_status'] ?? '0') == '1' ? 'Confirmed' : 'CANCELLED'; ?></span></div>
                        </div>

                        <div class="d-flex gap-1 align-items-center">
                            <span class="order-booking-id w-auto">Payment Status:</span>
                            <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['payment_status']); ?></span></div>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="d-flex flex-column align-items-end">
                        <div class="d-flex gap-1 align-items-center">
                            <span class="order-booking-id w-auto">Bed Type:</span>
                            <div><span class="booking-type-text fw-bold">Single</span></div>
                        </div>
                        <div class="d-flex gap-1 align-items-center">
                            <span class="order-booking-id w-auto">Booking date:</span>
                            <div><span class="order-booking-date  fw-bold"><?php echo date('d-M-Y', strtotime($booking['checkin'])); ?></span></div>
                        </div>
                        <?php if($booking['booking_status'] && ($booking['fare_type'] =='Refundable')){ ?>
                        <form class="cancel-hotel-booking-form" method="post">
                            <input type="hidden" name="action" value="cancel_hotel_booking">
                            <input type="hidden" name="referenceNum" value="<?= esc_attr($booking['referenceNum']); ?>">
                            <input type="hidden" name="supplierConfirmationNum" value="<?= esc_attr($booking['supplierConfirmationNum']); ?>">
                            <input type="hidden" name="hotel_token_id" value="<?= esc_attr($booking['hotel_token_id']); ?>">
                            <?php wp_nonce_field('cancel_booking_nonce', 'cancel_nonce'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    <?php } ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>No bookings found for your account.</p>
                <?php endif; ?>
            </div>

            <h4 class="user-name-text mx-3 pt-2">Past Hotel Booking</h4>

            <?php

            $pastHotelResult = $wpdb->get_results(
                    $wpdb->prepare(
                        "
                        SELECT * FROM hotel_booking_details h
                        INNER JOIN (
                            SELECT MAX(id) as latest_id
                            FROM hotel_booking_details
                            WHERE customer_id = %d
                            AND checkin < CURDATE()
                            GROUP BY transaction_id
                        ) latest ON h.id = latest.latest_id
                        ORDER BY h.id DESC
                        ",
                        $user_id
                    ),
                    ARRAY_A
                );
            ?>
            <?php if (!empty($pastHotelResult)) : ?>
                <?php foreach ($pastHotelResult as $booking) : ?>
                    <div class="d-flex justify-content-between order-detail-body-section mb-3">
                        <!-- Left Section -->
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking ID:</span>
                                <div> <a href="<?php echo esc_url( home_url( '/travel/order-view/?hotel_id=' . $booking['transaction_id'] ) ); ?>">
                                <span class="booking serial-number text-primary fw-bold"><?php echo esc_html($booking['referenceNum']); ?></span></a></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Total amount:</span>
                                <div><span class="booking serial-numbers fw-bold"><?= esc_html(get_option('travelx_required_currency')) . ' ' . esc_html($booking['price'] ?? ''); ?></span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking Status:</span>
                                <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['booking_status'] ?? '0') == '1' ? 'Confirmed' : 'CANCELLED'; ?></span></div>
                            </div>

                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Payment Status:</span>
                                <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['payment_status']); ?></span></div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Bed Type:</span>
                                <div><span class="booking-type-text fw-bold">Single</span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking date:</span>
                                <div><span class="order-booking-date  fw-bold"><?php echo date('d-M-Y', strtotime($booking['checkin'])); ?></span></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No bookings found for your account.</p>
            <?php endif; ?>
            <!-- past hotelsssss -->               
        </div>

        <div class="profile-card order-maintain-section">
            <h4 class="user-name-text mx-3 pt-2">UpComming Flight Booking</h4>
            <?php

            $upcommingFlightResult = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT * FROM flight_booking_details f
                    INNER JOIN (
                        SELECT MAX(id) as latest_id
                        FROM flight_booking_details
                        WHERE customer_id = %d
                          AND departure_date >= CURDATE()
                        GROUP BY transaction_id
                    ) latest ON f.id = latest.latest_id
                    ORDER BY f.id DESC
                ", $user_id),
                ARRAY_A
            );

            ?>
            <div class="order-detail-section">
            <?php if (!empty($upcommingFlightResult)) : ?>
                <?php foreach ($upcommingFlightResult as $booking) : ?>
                    <div class="d-flex justify-content-between order-detail-body-section mb-3">
                        <!-- Left Section -->
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking ID:</span>
                                <div> 
                              <a href="<?php echo esc_url( home_url( '/travel/order-view/?flight_id=' . $booking['session_id'] ) ); ?>">
                                   <span class="booking serial-number text-primary fw-bold">
                                    <?php echo esc_html(!empty($booking['booking_id']) ? $booking['booking_id'] : 'N/A'); ?>
                                    </span></a></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Total amount:</span>
                                <div><span class="booking serial-numbers fw-bold"><?= esc_html(get_option('travelx_required_currency')) . ' ' . esc_html($booking['amount'] ?? ''); ?></span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking Status:</span>
                                <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['booking_status']); ?></span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Payment Status:</span>
                                <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['payment_status']); ?></span></div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Is Refundable :</span>
                                <div><span class="booking-type-text fw-bold"><?php echo esc_html($booking['is_refundable']); ?></span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Travel class :</span>
                                <div><span class="booking-type-text fw-bold"><?php echo esc_html($booking['travel_class']); ?></span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Departure date:</span>
                                <div><span class="order-booking-date  fw-bold"><?php echo date('d-M-Y', strtotime($booking['departure_date'])); ?></span></div>
                            </div>
                            <?php if(($booking['is_refundable'] =='Yes') && ($booking['booking_status'] =='confirmed')){ ?>
                            <form class="cancel-flight-booking-form" method="post">
                                <input type="hidden" name="action" value="cancel_flight_booking">
                                <input type="hidden" name="booking_id" value="<?= esc_attr($booking['booking_id']); ?>">
                                <input type="hidden" name="session_id" value="<?= esc_attr($booking['session_id']); ?>">
                                <?php wp_nonce_field('cancel_hotel_booking_nonce', 'cancel_nonce'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                         <?php }?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>No bookings found for your account.</p>
                <?php endif; ?>
            </div>
            <!-- for past orders -->
            <?php 
            $pastFlights = $wpdb->get_results(
                $wpdb->prepare(
                    "
                    SELECT * FROM flight_booking_details f
                    INNER JOIN (
                        SELECT MAX(id) as latest_id
                        FROM flight_booking_details
                        WHERE customer_id = %d
                          AND departure_date < CURDATE()
                        GROUP BY transaction_id
                    ) latest ON f.id = latest.latest_id
                    ORDER BY f.id DESC
                    ",
                    $user_id
                ),
                ARRAY_A
            ); ?>

            <div class="profile-card-inner-section order-inner-section">
                <h4 class="user-name-text mx-3 pt-2">Past Flight Booking</h4>
            </div>
            <div class="order-detail-section">
            <?php if (!empty($pastFlights)) : ?>
                <?php foreach ($pastFlights as $booking) : ?>
                    <div class="d-flex justify-content-between order-detail-body-section mb-3">
                        <!-- Left Section -->
                        <div class="d-flex flex-column align-items-start">
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking ID:</span>
                                <div> 
                                    <a href="<?php echo esc_url( home_url( '/travel/order-view/?flight_id=' . $booking['session_id'] ) ); ?>">
                                   <span class="booking serial-number text-primary fw-bold">
                                    <?php echo esc_html(!empty($booking['booking_id']) ? $booking['booking_id'] : 'N/A'); ?>
                                    </span></a>
                                </div>
                            </div>
                             <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Total amount:</span>
                                <div><span class="booking serial-numbers fw-bold"><?= esc_html(get_option('travelx_required_currency')) . ' ' . esc_html($booking['amount'] ?? ''); ?></span></div>
                            </div>
                             <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Booking Status:</span>
                                <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['booking_status']); ?></span></div>
                            </div>
                             <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Payment Status:</span>
                                <div><span class="booking serial-numbers fw-bold"><?php echo esc_html($booking['payment_status']); ?></span></div>
                            </div>
                            
                        </div>

                        <!-- Right Section -->
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Travel class :</span>
                                <div><span class="booking-type-text fw-bold"><?php echo esc_html($booking['travel_class']); ?></span></div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <span class="order-booking-id w-auto">Departure date:</span>
                                <div><span class="order-booking-date  fw-bold"><?php echo date('d-M-Y', strtotime($booking['departure_date'])); ?></span></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <p>No bookings found for your account.</p>
                <?php endif; ?>
            </div>      
        </div>
    </div>
    <!-- flight -->
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
    $(".user-registration-MyAccount-navigation-link--dashboard").removeClass("is-active");
    $(".user-registration-MyAccount-navigation-link--my-orders").addClass("is-active");
});

</script>
<style >
    .page-template-page-my-orders-php .user-registration-MyAccount-content {
    display: none;
}
</style>
<?php get_footer(); ?>

   