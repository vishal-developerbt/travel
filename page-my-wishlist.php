<?php 
/**
 * Template Name: My Wishlist page
 * Description: A custom page template for special layouts.
 */

get_header();

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
global $wpdb;

$results = $wpdb->get_results(
    $wpdb->prepare("
        SELECT hotel_id 
        FROM travel_wishlist
        WHERE customer_id = %d AND type = %s
        ORDER BY created_at DESC
    ", $user_id, 'hotel'), // or use 'flight' if needed
    ARRAY_A
);

// Extract hotel_id values into a simple array
$hotel_ids = array_column($results, 'hotel_id');

// Convert to comma-separated string
$hotelid = implode(",", $hotel_ids);

// Output the result

$booking_data = fetch_Whislist_hotels_listings($hotelid);
$status = $booking_data['status'];
$hotels = $booking_data['itineraries'];

?>

<?php
    global $current_user;
    wp_get_current_user();
?>
<div class="container order-history-page d-flex"><?php echo do_shortcode('[user_registration_my_account]'); ?>
    <div class="profile-card order-maintain-section">
        <div class="profile-card-inner-section order-inner-section"></div>
        <div class="order-detail-section">
        <h4 class="user-name-text mx-3 pt-2 mt-3">My Hotel Wish list </h4>

        <?php if (!empty($hotels)) : ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($hotels as $hotel) :
                       $price = $hotel['total']; ?>
                <?php
                    global $current_user;
                    wp_get_current_user();

                    $item_id = $hotel['twxHotelId']; // Change dynamically per hotel/flight
                    $type = 'hotel'; // 'hotel' or 'flight'
                    $name = $hotel['hotelName'];
                    $is_wishlisted = is_item_in_wishlist($current_user->ID, $item_id, $type,$name);
                ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-container">
                            <span class="wishlist-icon <?php echo $is_wishlisted ? 'active' : ''; ?>" 
                              data-item-id="<?php echo esc_attr($item_id); ?>" 
                              data-type="<?php echo esc_attr($type); ?>" 
                              data-customer-id="<?php echo esc_attr($current_user->ID); ?>" 
                              data-customer-email="<?php echo esc_attr($current_user->user_email); ?>"
                              title="<?php echo $is_wishlisted ? 'Remove from wishlist' : 'Add to wishlist'; ?>">
                             <i class="fas fa-heart heart-icon"></i>
                            </span>
                            <img src="<?php echo esc_url($hotel['thumbNailUrl']); ?>" class="card-img-top" alt="<?php echo esc_attr($hotel['hotelName']); ?>">
                        </div>
                        <div class="card-body">
                          <h5 class="card-title"><?php echo $hotel['city'] ?>, <?php echo $hotel['country'] ?></h5>
                          <p class="card-text">
                            <?php echo esc_attr($hotel['hotelName']); ?>
                          </p>
                        </div>
                        <div class="card-footer text-end">
                            <a class="btn btn-primary btn-sm" href="<?php echo esc_url(
                                            get_site_url() . '/hotel-detail-page/?hotelId=' . urlencode($hotel['hotelId']) 
                                            . '&sessionId=' . urlencode($status['sessionId']) 
                                            . '&productId=' . urlencode($hotel['productId']) 
                                            . '&tokenId=' . urlencode($hotel['tokenId']) 
                                            . '&price=' . urlencode(base64_encode($price)) 
                                            . '&location=' . urlencode($hotel['city']) 
                                            . '&checkin=' . urlencode(date('Y-m-d')) 
                                            . '&checkout=' . urlencode(date('Y-m-d', strtotime('+2 day'))) // <-- closing paren fixed here
                                            . '&rooms=' . urlencode('1-2-0')
                                        ); ?>">
                            View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No Wishlist found for your account.</p>
            <?php endif; ?>
          </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
    $(".user-registration-MyAccount-navigation-link--dashboard").removeClass("is-active");
    $(".user-registration-MyAccount-navigation-link--my-wishlist").addClass("is-active");
});

</script>
    <?php get_footer(); ?>

   