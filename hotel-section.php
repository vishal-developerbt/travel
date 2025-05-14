<?php
$booking_data = fetch_most_popular_hotels_listings();
$status = isset($booking_data['status']) ? $booking_data['status'] : [];

// Safely assign $hotels
$hotels = isset($booking_data['itineraries']) && is_array($booking_data['itineraries']) ? $booking_data['itineraries'] : [];
?>
<?php if (!empty($hotels)): ?>
<div class=" hotel-section-bg-section">
    <section class="container hotel-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="most-popular-hotels">Our Most Popular Hotels</h2>
        </div>
                      
        <div class="row our-most-popular-hotels ">
            <?php   foreach ($hotels as $hotel) { 
                  $price = $hotel['total'];
                 $rating = isset($hotel['hotelRating']) ? ($hotel['hotelRating']) : 0;
                 if ($count >= 4) break;
                    $count++; ?>
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <div class="card hotel-card ">
                    <img src="<?php echo esc_url($hotel['thumbNailUrl']); ?>" alt="<?php echo esc_attr($hotel['hotelName']); ?>">
                    <div class="card-body">
                        <h6 class="parish-text"><?php echo $hotel['city'] ?>, <?php echo $hotel['country'] ?></h6>
                        <a href="<?php echo esc_url(
                            get_site_url() . '/hotel-detail-page/?hotelId=' . urlencode($hotel['hotelId']) 
                            . '&sessionId=' . urlencode($status['sessionId']) 
                            . '&productId=' . urlencode($hotel['productId']) 
                            . '&tokenId=' . urlencode($hotel['tokenId']) 
                            . '&price=' . urlencode(base64_encode($price)) 
                            . '&location=' . urlencode($hotel['city']) 
                            . '&checkin=' . urlencode(date('Y-m-d')) 
                            . '&checkout=' . urlencode(date('Y-m-d', strtotime('+4 day'))) // <-- closing paren fixed here
                            . '&rooms=' . urlencode('1-2-0')
                        ); ?>">
                            <p class="parish-paragraph"><?php echo $hotel['hotelName']; ?></p>
                        </a>
                        <p class="parish-rating"><?php echo $rating; ?> rating</p>
                        <?php if (isset($hotel['currency']) && isset($hotel['total'])): ?>
                        <p class="rating-bottom-section">4 days <span class="float-end  price-section-home-pages fw-bold"><?php
                            $currency = $hotel['currency'];
                            $price = number_format($hotel['total'], 2);

                            // Show $ for USD, otherwise show currency
                            echo ($currency === 'USD') ? '$' . esc_html($price) : esc_html($currency . ' ' . $price);
                            ?></span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </section>
</div>
<?php else: ?>
    <div class="container py-5">
        <div class="alert alert-warning text-center" role="alert">
            No popular hotels found at the moment. Please check back later.
        </div>
    </div>
<?php endif; ?>