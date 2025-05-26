<?php

/**
 * Template Name: Car Rental list
 * Description: A custom page template for special layouts.
 */

get_header(); 

$carPickupLocation = isset($_GET['car-pickup-location-id']) ? sanitize_text_field($_GET['car-pickup-location-id']) : '';
$carDropoffLocation = isset($_GET['car-dropoff-location-id']) ? sanitize_text_field($_GET['car-dropoff-location-id']) : '';
$pickupTime = isset($_GET['pickup_time']) ? sanitize_text_field($_GET['pickup_time']) : '';
$dropoffTime = isset($_GET['dropoff_time']) ? sanitize_text_field($_GET['dropoff_time']) : '';
$pickupDate = isset($_GET['pickup_date']) ? sanitize_text_field($_GET['pickup_date']) : '';
$dropoffDate = isset($_GET['dropoff_date']) ? sanitize_text_field($_GET['dropoff_date']) : '';
?>

<!-- Navbar and Search Bar -->
<div class="car-renatl-section mt-4">
    <div class="container">
        <form id="car-search-form">
            <div class="row g-2">
                <div class="d-flex flex-wrap w-100 align-items-center gap-4">
                    <!-- Pick-up location -->
                    <div class="col-md-2 text-start">
                        <div class="location-box">
                            <label class="location-label">
                                <i class="fa-solid fa-location-dot"></i> Pick-up location
                            </label>
                            <input type="text" id="pickup-location" name="car-pickup-location"
                                   class="form-control search-city-property"
                                   placeholder="Enter a city or location">
                            <input type="hidden" id="pickup-location-id" value="<?php echo $carPickupLocation ;?>" name="car-pickup-location-id">
                        </div>
                    </div>
                    <!-- Drop-off location -->
                    <div class="col-md-2 text-start">
                        <div class="location-box">
                            <label class="location-label">
                                <i class="fa-solid fa-location-dot"></i> Drop-off location
                            </label>
                            <input type="text" id="dropoff-location" name="car-dropoff-location"
                                   class="form-control search-city-property"
                                   placeholder="Enter a city or location">
                            <input type="hidden" id="dropoff-location-id" value="<?php echo $carDropoffLocation ;?>" name="car-dropoff-location-id">
                        </div>
                    </div>
                    <!-- Date Range Picker for Pick-up and Drop-off -->
                   <!--  <div class="col-md-3 col-12 pickup-drop-up-dates">
                        <label for="rental-dates" class="date-label">Pick-up & Drop-off Date</label>
                        <input type="text" id="rental-dates" class="form-control" placeholder="Select pick-up and drop-off dates" />
                    </div> -->
                      <div class="col-md-3 col-12 pickup-drop-up-dates">
                        <label for="rental-dates" class="date-label">Pick-up & Drop-off Date</label>
                        <input type="text" id="rental-dates" class="form-control" placeholder="Select pick-up and drop-off dates" />
                    </div>
                    <!-- Time Inputs -->
                    <!-- <div class="col-md-2 col-12">
                        <div class="date-box">
                            <label class="date-label">Pick-up & Drop-off Time</label>
                            <div class="d-flex gap-2 flex-column flex-md-row">
                                <input type="time" name="pickup_time" class="form-control" placeholder="10:00" />
                                <input type="time" name="dropoff_time" class="form-control" placeholder="10:30" />
                            </div>
                        </div>
                    </div> -->
                     <div class="col-md-2 col-12">
                        <div class="date-box">
                            <label class="date-label">Pick-up & Drop-off Time</label>
                            <div class="d-flex gap-2 flex-column flex-md-row">
                                <input type="time" name="pickup_time" class="form-control" placeholder="10:00" />
                                <input type="time" name="dropoff_time" class="form-control" placeholder="10:30" />
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="col-md-1 modify-serach-button">
                        <button type="submit" class="btn modify-search-btn w-100 mt-2 mt-md-0">
                            <span class="modify-search-text">Modify Search</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php 
  if ( empty($carPickupLocation) && empty($carDropoffLocation) && empty($pickupTime) &&
        empty($dropoffTime) && empty($pickupDate) && empty($dropoffDate))
    {
        echo '<h2 class="text-center mt-5">Search car for Rental</h2>';
    } else {

        $results = getsearchCarRentalList($carPickupLocation, $carDropoffLocation, $pickupTime, 
            $dropoffTime, $pickupDate, $dropoffDate);
       // echo "<pre>+";print_r($results); die;
    }?>
<!-- Car Listings Section -->
<div class="container mt-4 car-reantal-section-container-section">
    <div class="row">
        <!-- Filters Section -->
        <div class="col-md-3">
            <div class="filter-section">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="select-filter-section">Select Filters</h5>
                    <p><a href="#" class="clear-all-text">Clear All</a></p>
                </div>
                <hr>
                <form id="car-filter-form">
                  <strong>Sort by Rating</strong>
                    <div class="sort-by-rating">
                        <label><input type="checkbox" name="rating[]" value="1"> 1 Star</label>
                        <label><input type="checkbox" name="rating[]" value="2"> 2 Stars</label>
                        <label><input type="checkbox" name="rating[]" value="3"> 3 Stars</label>
                        <label><input type="checkbox" name="rating[]" value="4"> 4 Stars</label>
                        <label><input type="checkbox" name="rating[]" value="5"> 5 Stars</label>
                    </div>
                    <hr>

                  <strong>Sort by</strong>
                    <div class="sort-by-price">
                        <label><input type="radio" name="sorting" value="price-low-high"> Price: Low to High</label>
                        <label><input type="radio" name="sorting" value="price-high-low"> Price: High to Low</label>
                        <label><input type="radio" name="sorting" value="rating-low-high"> Rating: Low to High</label>
                        <label><input type="radio" name="sorting" value="rating-high-low"> Rating: High to Low</label>
                    </div>
                    <hr>
                </form>

            </div>
        </div>
        <!-- Car Listings -->
        <div class="col-md-9">
    <?php  if (!is_wp_error($results) && !empty($results['sessionId']) && !empty($results['count']) && !empty($results['data'])) {
 ?>

            <h5 class="france-section-text mb-3">
                <span id="totalProperties"><?php echo intval($results['count']);?>Cars in this location</span>
            </h5>
            <section class="car-renatal-listing-page">
            <div id="car-results">
                
                <?php foreach ($results['data'] as $index => $car): 
                   // echo "<pre>++"; print_r($car); die;
                    ?>
                    <div class="car-card-wrapper"
                     data-price="<?php echo $car['fees']['rateTotalAmount']; ?>"
                     data-rating="<?php echo round($car['vendor']['reviewsOverall']); ?>"
                     style="<?php echo $index < 20 ? 'display: block;' : 'display: none;'; ?>">

                        <div class="car-card-section">
                            <div class="row">
                                <!-- Car Image -->
                                <div class="col-md-12 col-lg-4">
                                    <div class="car-image-wrapper-sections position-relative">
                                        <img src="<?= htmlspecialchars($car['carDetails']['carImage']) ?>"
                                             class="hotel-image img-fluid" alt="<?= htmlspecialchars($car['carDetails']['carModel']) ?>">
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-8">
                                    <div class="d-flex justify-content-between car-renatl-car-main-sections">
                                        <!-- Car Info Left -->
                                        <div class="car-buisness-flex-section car-rental-buisness-section">
                                            <div class="d-flex gap-2 align-items-center mb-1">
                                                <h6 class="proprty-buisness-pargraph mb-0">Car Name:</h6>
                                                <span class="car-name-section"><?= htmlspecialchars($car['carDetails']['carModel']) ?></span>
                                            </div>

                                            <div class="star-rating mb-2" style="color: #f5c518;">
                                                <?php
                                                $rating = round($car['vendor']['reviewsOverall'] ?? 0); // Default 4 if no rating
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '<i class="fa-solid fa-star"></i>';
                                                    } else {
                                                        echo '<i class="fa-regular fa-star"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>

                                            <div class="booking-features d-flex gap-1 flex-wrap">
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-user"></i>
                                                    <span class="car-feature-text">Passengers: <?= (int)($car['carDetails']['passengerQuantity'] ?? 0) ?></span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-suitcase"></i>
                                                    <span class="car-feature-text">Baggage: <?= (int)($car['carDetails']['baggageQuantity'] ?? 0) ?></span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-door-open"></i>
                                                    <span class="car-feature-text">Doors: <?= (int)($car['carDetails']['vehicleDoor'] ?? 0) ?></span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-cogs"></i>
                                                    <span class="car-feature-text">Transmission: <?= htmlspecialchars($car['carDetails']['transmissionType'] ?? 'N/A') ?></span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-snowflake"></i>
                                                    <span class="car-feature-text">AC: <?= (!empty($car['carDetails']['ac']) ? 'Yes' : 'No') ?></span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-cogs"></i>
                                                    <span class="car-feature-text">Drive Type: <?= htmlspecialchars($car['carDetails']['driveType'] ?? 'N/A') ?></span>
                                                </div>
                                                <div class="feature-item d-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-cogs"></i>
                                                    <span class="car-feature-text">Fuel Type: <?= htmlspecialchars($car['carDetails']['fuelType'] ?? 'N/A') ?></span>
                                                </div>
                                            </div>

                                            <p class="mt-2" style="font-size: 13px; color: #666;">
                                                <strong>Fuel Policy:</strong> <?= htmlspecialchars($car['carDetails']['fuelPolicy']['description'] ?? 'N/A') ?>
                                            </p>
                                        </div>

                                        <!-- Car Info Right -->
                                        <div>
                                            <div class="d-flex gap-3 car-flex-section align-items-center mb-3">
                                                <img src="<?= htmlspecialchars($car['vendor']['vendorImage']) ?>"
                                                     alt="<?= htmlspecialchars($car['vendor']['name']) ?>" style="height: 40px;">
                                                <div class="car-list-page-section-end">
                                                    <p class="excellent-text mb-1">
                                                        <span class="excellent-text-paragraph"><?= htmlspecialchars($car['vendor']['reviewRatingText'] ?? 'Excellent') ?></span> reviews
                                                    </p>
                                                    <div class="bg-rating-section d-flex align-items-center justify-content-center rating-count-section-car">
                                                        <?= htmlspecialchars($car['vendor']['reviewsOverall'] ?? '') ?>
                                                    </div>
                                                    <small style="font-size: 12px; color: #777;">(<?= (int)($car['vendor']['reviewsTotal'] ?? 0) ?> reviews)</small>
                                                </div>
                                            </div>
                                      <?php
                                            $sessionId = $results['sessionId'];
                                            $referenceId = $car['referenceId'];
                                            $carImg = $car['carDetails']['carImage'];
                                            $carModel = $car['carDetails']['carModel'];
                                            $carRatting = round($car['vendor']['reviewsOverall']);
                                            $carPrice = $car['fees']['rateTotalAmount'];
                                            $reviewCount = $car['vendor']['reviewsTotal'];
                                        ?>

                                        <div class="view-button mb-3">
                                            <a href="/car-rental-detail-page/?session_id=<?= urlencode($sessionId) ?>&reference_id=<?= urlencode($referenceId) ?>&car_name=<?= urlencode($carModel) ?>&car_img=<?= urlencode($carImg) ?>&ratting=<?= urlencode($carRatting) ?>&ratting_count=<?= urlencode($reviewCount) ?>&car_price=<?= urlencode($carPrice) ?>">
                                                <button type="button" class="btn btn-primary hotel-view-link">View</button>
                                            </a>
                                        </div>

                                            <div class="d-flex gap-3 section-price-home align-items-center mb-1">
                                                <div class="bg-rating-sections">
                                                    <div class="price-section-final" style="font-size: 20px; font-weight: 700; color: #222;">
                                                        $<?= number_format($car['fees']['rateTotalAmount'] ?? 0, 2) ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tax-section" style="font-size: 13px; color: #666;">
                                                <p class="taxes-pay-fees-section mb-0">Estimated Deposit:
                                                <?php
                                            $currency = get_option('travelx_required_currency');
                                            $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                            echo $symbol . number_format($car['fees']['estimatedDeposit'] ?? 0, 2);
                                            ?>  
                                                <?php // number_format($car['fees']['estimatedDeposit'] ?? 0, 2) ?></p>
                                                <p class="taxes-pay-fees-section mb-0">Remaining Amount:<?php
                                            $currency = get_option('travelx_required_currency');
                                            $symbol = ($currency === 'USD') ? '$' : esc_html($currency);
                                            echo $symbol . number_format(($car['fees']['rateTotalAmount'] ?? 0) - ($car['fees']['estimatedDeposit'] ?? 0), 2);
                                            ?>  
                                            <?php // number_format(($car['fees']['rateTotalAmount'] ?? 0) - ($car['fees']['estimatedDeposit'] ?? 0), 2) ?></p>
                                                <p class="taxes-pay-fees-section mb-0">Fees Per Day</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($results['data']) > 20): ?>
                <div class="text-center mt-4">
                    <button id="showMoreBtn" class="btn btn-outline-primary">Show More Cars</button>
                </div>
            <?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const showMoreBtn = document.getElementById('showMoreBtn');
    const carCards = document.querySelectorAll('.car-card-wrapper');
    let visibleCount = 20;
    const increment = 20;

    showMoreBtn.addEventListener('click', function () {
        for (let i = visibleCount; i < visibleCount + increment; i++) {
            if (carCards[i]) {
                carCards[i].style.display = 'block';
            }
        }
        visibleCount += increment;

        if (visibleCount >= carCards.length) {
            showMoreBtn.style.display = 'none'; // Hide button if all shown
        }
    });
});
</script>
</div>
</section>              
            
        <?php }else {
    // Optionally handle the error
    if (is_wp_error($results)) {
        echo '<p>Error: ' . esc_html($results->get_error_message()) . '</p>';
    } else {
        echo '<p>No results found.</p>';
    }
} ?>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Flatpickr for date range picker
    flatpickr("#rental-dates", {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        numberOfMonths: 2,
        showMonths: 2,
        defaultDate: [new Date()],
        disableMobile: true
    });
    // Initialize noUiSlider for price range filter
  }); 
</script>
<script>
document.getElementById('car-search-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const pickupLocationId = document.getElementById('pickup-location-id').value;
    const dropoffLocationId = document.getElementById('dropoff-location-id').value;
    const pickupTime = document.querySelector('input[name="pickup_time"]').value;
    const dropoffTime = document.querySelector('input[name="dropoff_time"]').value;

    const dateRange = document.getElementById('rental-dates').value.trim();

    // Handle both "YYYY-MM-DD - YYYY-MM-DD" and "YYYY-MM-DD to YYYY-MM-DD"
    let pickupDate = '';
    let dropoffDate = '';
    if (dateRange.includes(' to ')) {
        [pickupDate, dropoffDate] = dateRange.split(' to ');
    } else if (dateRange.includes(' - ')) {
        [pickupDate, dropoffDate] = dateRange.split(' - ');
    }

    const params = new URLSearchParams();
    params.append('car-pickup-location-id', pickupLocationId);
    params.append('car-dropoff-location-id', dropoffLocationId);
    params.append('pickup_time', pickupTime);
    params.append('dropoff_time', dropoffTime);
    params.append('pickup_date', pickupDate);
    params.append('dropoff_date', dropoffDate);

    window.location.href = `/car-rental-list/?${params.toString()}`;
});
</script>
<script type="text/javascript">
    // Function to handle form submission dynamically
    function handleFormSubmit() {
        const pickupLocationId = document.getElementById('pickup-location-id').value;
        const dropoffLocationId = document.getElementById('dropoff-location-id').value;
        const pickupTime = document.querySelector('input[name="pickup_time"]').value;
        const dropoffTime = document.querySelector('input[name="dropoff_time"]').value;

        const dateRange = document.getElementById('rental-dates').value.trim();
        let pickupDate = '';
        let dropoffDate = '';
        if (dateRange.includes(' to ')) {
            [pickupDate, dropoffDate] = dateRange.split(' to ');
        } else if (dateRange.includes(' - ')) {
            [pickupDate, dropoffDate] = dateRange.split(' - ');
        }

        // Collect rating filters
        const ratings = [];
        document.querySelectorAll('input[name="rating[]"]:checked').forEach(input => {
            ratings.push(input.value);
        });

        // Collect sorting preference
        const sorting = document.querySelector('input[name="sorting"]:checked')?.value || '';

        // Prepare query parameters
        const params = new URLSearchParams();
        params.append('car-pickup-location-id', carPickupLocation);
        params.append('car-dropoff-location-id', carDropoffLocation);
        params.append('pickup_time', pickupTime);
        params.append('dropoff_time', dropoffTime);
        params.append('pickup_date', pickupDate);
        params.append('dropoff_date', dropoffDate);

        if (ratings.length > 0) {
            params.append('rating', ratings);
        }

        if (sorting) {
            params.append('sorting', sorting);
        }

        // Redirect to the new URL with query parameters
        window.location.href = `/car-rental-list/?${params.toString()}`;
    }

    // Listen for changes on checkboxes and radio buttons
    document.querySelectorAll('input[name="rating[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', handleFormSubmit);
    });

    document.querySelectorAll('input[name="sorting"]').forEach(radio => {
        radio.addEventListener('change', handleFormSubmit);
    });

    // Optional: You can also listen for form submission manually if needed (if you have a submit button)
    document.getElementById('car-filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        handleFormSubmit();
    });
</script>
<!-- Pass PHP values to JavaScript -->
<script type="text/javascript">
    var carPickupLocation = "<?php echo esc_js($carPickupLocation); ?>";
    var carDropoffLocation = "<?php echo esc_js($carDropoffLocation); ?>";
    var pickupTime = "<?php echo esc_js($pickupTime); ?>";
    var dropoffTime = "<?php echo esc_js($dropoffTime); ?>";
    var pickupDate = "<?php echo esc_js($pickupDate); ?>";
    var dropoffDate = "<?php echo esc_js($dropoffDate); ?>";

    document.addEventListener('DOMContentLoaded', function () {
    // Populate the date range input field
    if (pickupDate && dropoffDate) {
        var dateRange = pickupDate + ' to ' + dropoffDate;
        document.getElementById('rental-dates').value = dateRange;
    }

    // Populate the time input fields
    if (pickupTime) {
        document.querySelector('input[name="pickup_time"]').value = pickupTime;
    }
    if (dropoffTime) {
        document.querySelector('input[name="dropoff_time"]').value = dropoffTime;
    }
});

</script>
<?php get_footer(); ?>
