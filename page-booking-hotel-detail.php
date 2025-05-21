
<?php 
/**
 * Template Name: Hotel Details Page
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
<?php
$hotelId = isset($_GET['hotelId']) ? sanitize_text_field($_GET['hotelId']) : '';
$sessionId = isset($_GET['sessionId']) ? sanitize_text_field($_GET['sessionId']) : '';
$productId = isset($_GET['productId']) ? sanitize_text_field($_GET['productId']) : '';
$tokenId = isset($_GET['tokenId']) ? sanitize_text_field($_GET['tokenId']) : '';
$location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
$rooms = isset($_GET['rooms']) ? sanitize_text_field($_GET['rooms']) : '';
$roomforPayment = isset($_GET['rooms']) ? sanitize_text_field($_GET['rooms']) : '';
$price = isset($_GET['price']) ? sanitize_text_field($_GET['price']) : '';
$decoded_price = $price ? base64_decode($price) : '';
$hotelDetails = fetch_hotel_details_by_id($hotelId,$productId,$tokenId,$sessionId);
$hotelReviews = [];
$hotelDetailsforPaymentpage =['hotelImages'=>isset($hotelDetails['hotelImages']) ? $hotelDetails['hotelImages'] : [],
'name'=>isset($hotelDetails['name']) ? $hotelDetails['name'] : '',
'locality' => isset($hotelDetails['locality']) ? $hotelDetails['locality'] : '',
'city' => isset($hotelDetails['city']) ? $hotelDetails['city'] : '',
'country' => isset($hotelDetails['country']) ? $hotelDetails['country'] : '',
'hotelRating' => isset($hotelDetails['hotelRating']) ? $hotelDetails['hotelRating'] : '',
];
if (
    isset($hotelDetails['hotel_review']) &&
    isset($hotelDetails['hotel_review']['reviews']) &&
    is_array($hotelDetails['hotel_review']['reviews'])
) {
    $hotelReviews = $hotelDetails['hotel_review']['reviews'];
}

 $location_parts = array_filter([$hotelDetails['locality'] ?? '',
    $hotelDetails['city'] ?? '', $hotelDetails['country'] ?? '' ]);
 
 $hotelRating = isset($hotelDetails['hotelRating']) && !empty($hotelDetails['hotelRating']) ? floatval($hotelDetails['hotelRating']) : 0;
                        
    if ($hotelRating >= 1.0 && $hotelRating <= 2.4) {
        $ratingLabel = "Good";
    } elseif ($hotelRating >= 2.5 && $hotelRating <= 3.4) {
        $ratingLabel = "Average"; 
    } elseif ($hotelRating >= 3.5 && $hotelRating <= 4.4) {
        $ratingLabel = "Very Good";
    } elseif ($hotelRating >= 4.5 && $hotelRating <= 5.0) {
        $ratingLabel = "Excellent";
    } else {
        $ratingLabel ="";
    }
?>

<div class="container-fluid">
    <div class="container hotel-details-page">
        <div class=" mt-4 hotels-detial-page">
            <!-- Header Section -->
            <div class="hotel-header">
                <div class="d-flex gap-4 align-items">
                    <div>
                    <h3 class="marine-tour-text"><?php echo $hotelDetails['name'] ?? ''; ?></h3>
                    <p class="location-text"><span class="efieel-tower-text">
                        <?php echo !empty($location_parts) ? implode(', ', $location_parts) : 'Location not available'; ?>
                    </p>
                    </div>
                    
                    <div class="star-icons">
                        <?php 
                            $maxStars = 5;
                            $rating = isset($hotelDetails['hotelRating']) ? floatval($hotelDetails['hotelRating']) : 0;

                            for ($i = 1; $i <= $maxStars; $i++) {
                                if ($i <= floor($rating)) {
                                    echo '<i class="fa-solid fa-star"></i>'; 
                                } elseif ($i == ceil($rating) && ($rating - floor($rating)) > 0) {
                                    echo '<i class="fa-solid fa-star-half-alt"></i>'; 
                                } else {
                                    echo '<i class="fa-regular fa-star"></i>';
                                }
                            } ?>
                    </div>
                    <div class="rating-section">
                        <span class="text-muted excellent-text">
                        <?php echo $ratingLabel; ?>
                            <br><?php if (isset($hotelDetails['hotel_review']['num_reviews'])): ?>
                                <span class="review-"><?php echo htmlspecialchars($hotelDetails['hotel_review']['num_reviews']) ?> reviews</span>
                            <?php else: ?>
                                <!-- <span class="review-">N/A</span> -->
                            <?php endif; ?></span>
                        <div class="rating-section-container">
                            <?php $skipRatings = ['GUEST', 'WITHOUT']; ?>
                            <span class="rating-badge"><?php echo in_array($hotelDetails['hotelRating'], $skipRatings) ? 0 : $hotelDetails['hotelRating'];?></span>
                        </div>
                    </div>
                </div> <!-- not -->
            </div> <!-- not -->
    <!-- Hotel Grid Layout -->
    <div class="hotel-grid">
        <div class="swiper-container main-slider">  
            <div class="swiper-wrapper">
            <?php 
            $hotelImages = isset($hotelDetails['hotelImages']) ? $hotelDetails['hotelImages'] : [];
            foreach ($hotelImages as $hotelImage) {
                // Ensure each image has a valid URL and caption
                $imageUrl = isset($hotelImage['url']) ? esc_url($hotelImage['url']) : '';
                $imageCaption = isset($hotelImage['caption']) ? esc_attr($hotelImage['caption']) : 'Hotel Image';
                if (!empty($imageUrl)) { 
            ?>
                <div class="swiper-slide">
                    <img src="<?php echo $imageUrl; ?>" class="hotel-main-img" alt="<?php echo $imageCaption; ?>">
                </div>
            <?php  } } ?>
            </div>

        </div>
        <!-- Thumbnails -->
        <div class="hotel-thumbnails">
            <?php 
            foreach (array_slice($hotelImages, 0, 3) as $index => $hotelImage) {
                $imageleftUrl = isset($hotelImage['url']) ? esc_url($hotelImage['url']) : '';
                $imageCaption = isset($hotelImage['caption']) ? esc_attr($hotelImage['caption']) : 'Hotel Image';

                if (!empty($imageleftUrl)) { ?>
                <img src="<?php echo $imageleftUrl; ?>" class="hotel-thumbnail" data-slide="<?php echo $index; ?>" alt="<?php echo $imageCaption; ?>">
            <?php  } } ?>
        </div>

        <div class="hotel-info">
            <?php 
            $hotelRoomOptions = fetch_room_option_for_hotel_details_page($hotelId, $productId, $tokenId, $sessionId);
            //echo "<pre>"; print_r($hotelRoomOptions); 
            $rooms = $hotelRoomOptions['roomRates']['perBookingRates'] ?? [];

            foreach (array_slice($rooms, 0, 1) as $index => $room) {   
                $roomTypen = isset($room['roomType']) ? esc_html($room['roomType']) : 'Unknown Room Type'; ?>
                <h5 class="hotel-title mb-0"><?php echo $roomTypen; ?></h5>
                    <p class="guest-text-detail-page">2 x Guest | 1 x Room</p>
                <div class="booking-features-section">
                    <?php 
                    foreach ($room['facilities'] as $index => $facility) { ?>
                    <div class="feature-items-top show-top<?php echo $index > 3 ? ' hidden-feature' : ''; ?>">
                        <div>
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <span class="free-cancellation-text"><?php echo htmlspecialchars($facility); ?></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <div class="mt-2 per-night-section">
                    <div>
                        <p class="mb-1 per-night-text">Per Night</p>
                    </div>
                    <span class="price-section mb-2"> 
                    <?php   
                    $currency = $room['currency'];
                    $price = number_format($room['netPrice'], 2);
                    echo ($currency === 'USD') ? '$' . esc_html($price) : esc_html($currency . ' ' . $price); ?>
                    </span>
                </div>
                            
                <div class="d-flex gap-2 button-section-home mt-2">
                    <?php 
                    $roomproductId = $room['productId'] ?? '';
                    $roomprice = $room['netPrice'];

                    $rateBasisId = $room['rateBasisId'] ?? '';
                    $roomsessionId = $hotelRoomOptions['sessionId'] ?? '';
                    $roomtokenId = $hotelRoomOptions['tokenId'] ?? '';
                    $roomhotelId = $hotelRoomOptions['hotelId'] ?? '';

                    if ( is_user_logged_in() ) { ?>
                        <button class="btn btn-primary select-room-buttons" 
                            onclick="redirectToPaymentPage(
                                '<?php echo esc_js($roomsessionId); ?>', 
                                '<?php echo esc_js($roomtokenId); ?>', 
                                '<?php echo esc_js($roomhotelId); ?>', 
                                '<?php echo esc_js($roomproductId); ?>', 
                                '<?php echo esc_js($checkin); ?>', 
                                '<?php echo esc_js($checkout); ?>',
                                '<?php echo esc_js($roomforPayment); ?>', 
                                '<?php echo esc_js($rateBasisId); ?>',
                                '<?php echo esc_js($roomprice); ?>',
                                '<?php echo esc_js($location); ?>')">                
                                <span class="select-room-text">Select Room</span>
                        </button><?php
                            } else {?>
                            
                            <a href="<?php echo get_site_url() . '/my-account/';?>">
                                <button class="btn btn-primary" >
                                    <span class="select-room-text">Select Room</span>
                                </button>
                            </a>
                        <?php  }?>
                </div>
            <?php } ?>
        </div>
    </div>
</div><!-- not -->

<div class="mt-4 sort-by-section detail-pages">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <button class="btn btn-sort room-options-select">
                <span class="room-option-select-text">Room Options</span>
            </button>
            <a href="#overview">
                <button class="btn btn-sort overview-select">
                    <span class="overview-sections">Overview</span>
                </button>
            </a>
            <a href="#aminites">
                <button class="btn btn-sort aminities-select">
                    <span class="aminites-section">Amenities</span>
                </button>
            </a>
            <a href="#location">
                <button class="btn btn-sort location-select">
                    <span class="location-sections-detail"> Location</span>
                </button>
            </a>
            <a href="#booking-rules">
                <button class="btn btn-sort price-high-to-low">
                    <span class="booking-rules-sections">Booking Rules</span>
                </button>
            </a>
        </div>
    </div>
</div>
 
<?php
if (!empty($rooms)) {
    $totalRooms = count($rooms);
    $roomsPerPage = 5; 
    $counter = 0;
    ?>
    
    <div id="room-list">
        <?php 
        foreach ($rooms as $index => $room) {
            $roomType = isset($room['roomType']) ? esc_html($room['roomType']) : 'Unknown Room Type';
                ?>
        <div class="room-item <?php echo ($index < $roomsPerPage) ? 'show' : 'hidden'; ?>">
            <div class="mt-4">
                <section class="hotel-wrapper">
                    <div class="hotel-container">
                        <div class="hotel-media">
                            <div class="d-flex flex-column">
                            <?php foreach (array_slice($hotelImages, 0, 1) as $index => $hotelImage) { 
                                $imageUrl = isset($hotelImage['url']) ? esc_url($hotelImage['url']) : '';
                                $imageCaption = isset($hotelImage['caption']) ? esc_attr($hotelImage['caption']) : 'Hotel Image';?>
                                <img src="<?php echo $imageUrl;?>" alt="<?php $imageCaption;?>" class="hotel-thumbnail">
                                <?php }?>
                                <span class="standard-room-text mb-1">
                                    <span class="standard-queen-text mt-1">
                                        <?php echo $roomType; ?>
                                    </span>
                                </span>
                            </div>
                            <div class="hotel-description">
                                <ul class="hotel-features">
                                    <?php foreach ($room['facilities'] as $indexd => $facility): ?>
                                    <li class="hotels-details-feature<?= $indexd >= 4 ? ' extra-feature' : '' ?>" <?= $indexd >= 4 ? 'style="display:none;"' : '' ?>>
                                        <div class="icons-right">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div class="feature-text">
                                            <?= htmlspecialchars($facility); ?>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="hotel-pricing">
                            <div class="d-flex align-items-center gap-2 hotel-pricing-combo">
                                <p class="current-price"> 
                                <?php
                                $currency = $room['currency'];
                                $price = number_format($room['netPrice'], 2);
                                echo ($currency === 'USD') ? '$' . esc_html($price) : esc_html($currency . ' ' . $price);?>
                                </p>
                            </div>
                            <p class="text-muted">Fees Per Night</p> 
                                 <?php 
                                $roomproductId = $room['productId'] ?? '';
                                $roomprice = $room['netPrice'];

                                $rateBasisId = $room['rateBasisId'] ?? '';
                                $roomsessionId = $hotelRoomOptions['sessionId'] ?? '';
                                $roomtokenId = $hotelRoomOptions['tokenId'] ?? '';
                                $roomhotelId = $hotelRoomOptions['hotelId'] ?? '';

                                if ( is_user_logged_in() ) {  
                                ?>
                                <button class="btn btn-primary select-room-buttons" 
                                    onclick="redirectToPaymentPage(
                                        '<?php echo esc_js($roomsessionId); ?>', 
                                        '<?php echo esc_js($roomtokenId); ?>', 
                                        '<?php echo esc_js($roomhotelId); ?>', 
                                        '<?php echo esc_js($roomproductId); ?>', 
                                        '<?php echo esc_js($checkin); ?>', 
                                        '<?php echo esc_js($checkout); ?>',
                                        '<?php echo esc_js($roomforPayment); ?>', 
                                        '<?php echo esc_js($rateBasisId); ?>',
                                        '<?php echo esc_js($roomprice); ?>',
                                        '<?php echo esc_js($location); ?>'
                                    )">
                                        <span class="select-room-text">Select Room</span>
                                    </button><?php
                                    } else {?>
                                    <a href="<?php echo get_site_url() . '/my-account/';?>">
                                        <button class="btn btn-primary" >
                                            <span class="select-room-text">Select Room</span>
                                        </button>
                                    </a>
                                  <?php  }?>

                                    <input type="hidden" name="pid" value="<?php echo $room['productId']; ?>">
                                     <input type="hidden" name="rateBasisId" value="<?php echo $room['rateBasisId']; ?>">
                                    <input type="hidden" name="netPrice" value="<?php echo esc_html(number_format($room['netPrice'], 2)); ?>">
                        </div>
                    </div><!-- not added -->
                </section>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if ($totalRooms > $roomsPerPage) { ?>
    <div class="text-end mt-3">
        <button id="showMoreBtn" class="btn btn-secondary">Show More</button>
    </div>
    <?php } ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let visibleCount = <?php echo $roomsPerPage; ?>;
            const rooms = document.querySelectorAll(".room-item");
            const showMoreBtn = document.getElementById("showMoreBtn");

            showMoreBtn.addEventListener("click", function () {
                let newVisibleCount = visibleCount + <?php echo $roomsPerPage; ?>;
                rooms.forEach((room, index) => {
                    if (index < newVisibleCount) {
                        room.classList.add("show");
                        room.classList.remove("hidden");
                    }
                });

                visibleCount = newVisibleCount;
                if (visibleCount >= rooms.length) {
                    showMoreBtn.style.display = "none"; // Hide button when all rooms are shown
                }
            });
        });
    </script>

    <?php } else { ?>
        <div class="alert alert-warning text-center"  style="margin-top: 20px;" role="alert">
            No Rooms Available.
        </div>
    <?php } ?>

        <section class="hotel-detail-paragraph-section" id="overview">
            <h4 class="fw-bold hotel-detail-paragraph-page">
            <?= $hotelDetails['name']?>
                <div class="star-icons">
                <?php
                $maxStars = 5;
                $rating = isset($hotelDetails['hotelRating']) ? floatval($hotelDetails['hotelRating']) : 0;
                    for ($i = 1; $i <= $maxStars; $i++) 
                    {
                        if ($i <= floor($rating)) {
                            echo '<i class="fa-solid fa-star"></i>';
                        } elseif ($i == ceil($rating) && ($rating - floor($rating)) > 0) {
                            echo '<i class="fa-solid fa-star-half-alt"></i>'; 
                        } else {
                            echo '<i class="fa-regular fa-star"></i>'; 
                        }
                    }
                ?>
                </div>
            </h4>
            <p class="text-muted location-text">
            <?php
                echo !empty($location_parts) ? implode(', ', $location_parts) : 'Location not available';
                ?>
            </p>

            <!-- Hotel Description Section -->
            <div class="mt-3">
                <h5 class="fw-bold hotel-description-text">Hotel Description</h5>
                <p class="description-text"><?= $hotelDetails['description']['content']?></p>
            </div>
        </section>

        <section class="hotel-iocns" id="aminites">
            <div class=" mt-4">
                <div class="amenities-container">
                <h4 class="amenities-title">Amenities</h4>
                <?php $facilities = $hotelDetails['facilities']; ?>
                    <div class="amenities-grid">
                    <?php foreach($facilities as $facilitie){?>
                        <div class="amenity-item">
                            <?php echo $facilitie ?>   
                        </div>
                    <?php }?>
                    </div>
                </div>
            </div>
        </section>

        <section class="map-section" id="location">
            <div class=" mt-4">
                <div class="map-container">
                    <h4 class="map-title">Location</h4>
                <?php
                $hotel_name = isset($hotelDetails['name']) ? urlencode($hotelDetails['name']) : 'Maldives'; ?>
                    <iframe width="100%" height="380" style="border:0;" loading="lazy" allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q=<?php echo $hotel_name; ?>&output=embed">
                    </iframe>
                </div>
            </div>
        </section>

        <section>
            <div class="review-container review-main-section">
                <h2 class="review-header  mt-3 mb-2">Customers Top Reviews</h2>
                <?php
                    $rating = $hotelDetails['hotel_review']['rating'] ?? 0;
                    // Calculate full stars, half star, and empty stars
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) < 0.75 ? 1 : 0;
                    if (($rating - $fullStars) >= 0.75) {
                        $fullStars++;
                        $halfStar = 0;
                    }
                    $emptyStars = 5 - ($fullStars + $halfStar);
                    ?>
                <div class="d-flex gap-3 align-items-center">
                    <div class="star-icons mb-3">
                        <?php for ($i = 0; $i < $fullStars; $i++): ?>
                            <i class="fa-solid fa-star"></i>
                        <?php endfor; ?>
                        <?php if ($halfStar): ?>
                            <i class="fa-solid fa-star-half-alt"></i>
                        <?php endif; ?>
                        <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                            <i class="fa-regular fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    (<?= number_format($rating, 1) ?> out of 5)
                </div>
            <?php 
                foreach($hotelReviews as $hotelReview){ 
                $location = isset($hotelReview['user']['user_location']['name']) && !empty($hotelReview['user']['user_location']['name'])? $hotelReview['user']['user_location']['name']: 'Unknown Location';
                $rawDate = $hotelReview['published_date'] ?? '';
                $formattedDate = $rawDate ? date('j F Y', strtotime($rawDate)) : 'Unknown Date';
                $reviewText = "Reviewed in {$location} on {$formattedDate}";

                $username = isset($hotelReview['user']['username']) ? htmlspecialchars($hotelReview['user']['username']) : 'Anonymous';
                $rating = $hotelReview['rating'] ?? 0;
                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) < 0.75 ? 1 : 0;
                if (($rating - $fullStars) >= 0.75) {
                    $fullStars++;
                    $halfStar = 0;
                }
                $emptyStars = 5 - ($fullStars + $halfStar); ?>
                    <div class="review-card mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-3">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="reviewer-name m-0">
                                    <?php echo isset($hotelReview['user']['username']) ? htmlspecialchars($hotelReview['user']['username']) : 'Anonymous'; ?>
                                </p>
                            </div>
                        </div>
                   
                        <div class="d-flex align-items-center mb-1">
                            <div class="star-icons">
                                <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                                <?php if ($halfStar): ?>
                                    <i class="fa-solid fa-star-half-alt"></i>
                                <?php endif; ?>
                                <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                    <i class="fa-regular fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-date mb-1"><?php echo $reviewText; ?></p>
                        <p class="review-text"><?php echo $hotelReview['text']; ?></p> 
                    </div>
                <?php }?>
            </div>
        </section>
        <!-- booking rule-section -->
        <section id="booking-rules">
            <div class="mt-4">
                <div class="booking-rules-container">
                    <h4 class="booking-rules-title">Booking Rules</h4>
                    <ul class="booking-rules-list">
                        <li class="booking-rule-item">As per the government regulations, every guest above the 18
                            years has to carry a valid Photo ID. The identification proofs can be Driving License,
                            Voters Card, Passport and Ration Card. Without valid ID, guests will not be allowed to
                            check in.</li>
                        <li class="booking-rule-item">BookA Travel will not be responsible for the check-in denied
                            by the hotel due to the above-mentioned reason.</li>
                        <li class="booking-rule-item">The primary guest checking-in to the hotel must be minimum of
                            18 years old. Children accompanying adults may be between 1 and 12 years.</li>
                        <li class="booking-rule-item">Guests will be charged for extra bed, food and other
                            facilities which are not mentioned in the booking and may vary as per the hotel.</li>
                        <li class="booking-rule-item">If an extra bed is included in your booking, you may be
                            provided with a folding cot or a mattress as an extra bed (depends on hotel).</li>
                        <li class="booking-rule-item">Generally, check-in / check-out time varies from hotel to
                            hotel and can be checked on the confirmation voucher. However, for early check-in or
                            late check-out, you are advised to confirm the same directly from the concerned hotel.
                        </li>
                        <li class="booking-rule-item">The room tariff is inclusive of all taxes but the amount paid
                            does not include charges for any additional services and facilities (such as room
                            service, mini bar, snacks or telephone calls). These services will be charged by the
                            hotel at the time of check-out.</li>
                        <li class="booking-rule-item">If the hotel denies accommodation to the guests posing as a
                            'couple' on not providing suitable ID proof, BookA Travel will not be responsible for
                            this condition and won’t provide any refund for such bookings.</li>
                    </ul>
                </div>
            </div>
        </section>
    </div><!-- not -->
</div><!-- not -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var swiper = new Swiper(".main-slider", {
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

        });

        // Thumbnail Click Event to Change Main Slide
        document.querySelectorAll(".hotel-thumbnail").forEach((thumbnail, index) => {
            thumbnail.addEventListener("click", function () {
                swiper.slideToLoop(index);
            });
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $(".book-now-button-detail-page").click(function () {
        let baseUrl = window.location.origin; // Automatically gets the domain
        let checkoutUrl = baseUrl + "/checkout.php";

        // Get values from the clicked room's container
        let roomContainer = $(this).closest(".hotel-container");
        let rateBasisId = roomContainer.find("input[name='rateBasisId']").val();
        let netPrice = roomContainer.find("input[name='netPrice']").val();
         let pid = roomContainer.find("input[name='pid']").val();
        

        // Get values from URL
        let urlParams = new URLSearchParams(window.location.search);
        let location = urlParams.get("location") || "";
        let checkin = urlParams.get("checkin") || "";
        let checkout = urlParams.get("checkout") || "";
        let rooms = urlParams.get("rooms") || "";
        let hotelId = urlParams.get("hotelId") || "";
        let tokenId = urlParams.get("tokenId") || "";
        let sessionId = urlParams.get("sessionId") || "";
       // let productId = urlParams.get("productId") || "";

        // Ensure required values are not empty
        if (!rateBasisId || !netPrice) {
            alert("Error: Missing room details. Please try again.");
            return;
        }
       // alert(rateBasisId); alert(netPrice);

        $.ajax({
            url: checkoutUrl,
            type: "POST",
            data: { 
                action: "book_now",
                location: location,
                checkin: checkin,
                checkout: checkout,
                hotelId: hotelId,
                tokenId: tokenId,
                sessionId: sessionId,
                productId: pid,
                rooms: rooms,
                rateBasisId: rateBasisId, 
                netPrice: netPrice 
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    window.location.href = response.payment_url; // Redirect to Stripe
                } else {
                    alert("Booking failed: " + response.message);
                }
            },
            error: function () {
                alert("Something went wrong!");
            }
        });
    });
});

</script>
<script type="text/javascript">
  function redirectToPaymentPage(roomSessionId, roomTokenId, roomHotelId, roomProductId, checkin, checkout, roomforPayment,rateBasisId,roomprice,location) {
    let baseUrl = "<?php echo site_url(); ?>"; // Get the domain
    let paymentPageUrl = baseUrl + "/hotel-payment/";

    // Build query string with parameters
    let params = new URLSearchParams({
        checkin: checkin,
        checkout: checkout,
        rooms: roomforPayment,
      hotel_id: roomHotelId,
      session_id: roomSessionId,
      product_id: roomProductId,
      token_id: roomTokenId,
      rate_basis_id:rateBasisId,
      price:roomprice,
      location:location
    });

    // Redirect to payment page with parameters
    window.location.href = paymentPageUrl + "?" + params.toString();
  }
</script>

<?php get_footer(); ?>
   