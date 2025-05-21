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
                            <input type="hidden" id="pickup-location-id" name="car-pickup-location-id">
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
                            <input type="hidden" id="dropoff-location-id" name="car-dropoff-location-id">
                        </div>
                    </div>
                    <!-- Date Range Picker for Pick-up and Drop-off -->
                    <div class="col-md-3 col-12 pickup-drop-up-dates">
                        <label for="rental-dates" class="date-label">Pick-up & Drop-off Date</label>
                        <input type="text" id="rental-dates" class="form-control" placeholder="Select pick-up and drop-off dates" />
                    </div>
                    <!-- Time Inputs -->
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
                <form id="filterForm">
                  <strong>Sort by Rating</strong>
                  <div>
                    <label><input type="checkbox" name="rating[]" value="1"> 1 Star</label>
                    <label><input type="checkbox" name="rating[]" value="2"> 2 Stars</label>
                    <label><input type="checkbox" name="rating[]" value="3"> 3 Stars</label>
                    <label><input type="checkbox" name="rating[]" value="4"> 4 Stars</label>
                    <label><input type="checkbox" name="rating[]" value="5"> 5 Stars</label>
                  </div>
                  <hr>

                  <strong>Sort by</strong>
                  <div>
                    <label><input type="radio" name="sorting" value="price-low-high"> Price: Low to High</label>
                    <label><input type="radio" name="sorting" value="price-high-low"> Price: High to Low</label>
                    <label><input type="radio" name="sorting" value="rating-low-high"> Rating: Low to High</label>
                    <label><input type="radio" name="sorting" value="rating-high-low"> Rating: High to Low</label>
                  </div>
                  <hr>

                  <!-- Price Filter -->
                  <div class="wrapper">
                    <header><h2>Sort by Price</h2></header>
                    <div class="price-input">
                      <div class="field">
                        <span>Min</span>
                        <input type="number" class="input-min" value="2500">
                      </div>
                      <div class="separator">-</div>
                      <div class="field">
                        <span>Max</span>
                        <input type="number" class="input-max" value="7500">
                      </div>
                    </div>
                    <div class="range-input">
                      <input type="range" class="range-min" min="0" max="10000" value="2500" step="100">
                      <input type="range" class="range-max" min="0" max="10000" value="7500" step="100">
                    </div>
                  </div>
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
            <div id="car-results">
                <section class="car-renatal-listing-page">
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
                                        ?>

                                        <div class="view-button mb-3">
                                            <a href="/car-rental-detail-page/?session_id=<?= urlencode($sessionId) ?>&reference_id=<?= urlencode($referenceId) ?>">
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
</section>              
            </div>
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

<script type="text/javascript">
    const rangeInput = document.querySelectorAll(".range-input input"),
  priceInput = document.querySelectorAll(".price-input input"),
  range = document.querySelector(".slider .progress");
let priceGap = 1000;

priceInput.forEach((input) => {
  input.addEventListener("input", (e) => {
    let minPrice = parseInt(priceInput[0].value),
      maxPrice = parseInt(priceInput[1].value);

    if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
      if (e.target.className === "input-min") {
        rangeInput[0].value = minPrice;
        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
      } else {
        rangeInput[1].value = maxPrice;
        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
      }
    }
  });
});

rangeInput.forEach((input) => {
  input.addEventListener("input", (e) => {
    let minVal = parseInt(rangeInput[0].value),
      maxVal = parseInt(rangeInput[1].value);

    if (maxVal - minVal < priceGap) {
      if (e.target.className === "range-min") {
        rangeInput[0].value = maxVal - priceGap;
      } else {
        rangeInput[1].value = minVal + priceGap;
      }
    } else {
      priceInput[0].value = minVal;
      priceInput[1].value = maxVal;
      range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
      range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
    }
  });
});

</script>
<style type="text/css">
 
</style>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const filterForm = document.getElementById('filterForm');
  const carCards = document.querySelectorAll('.car-card-wrapper');

  // Helper function to apply the filters and sorting
  function applyFilters() {
    const formData = new FormData(filterForm);

    // Get selected ratings
    const selectedRatings = formData.getAll('rating[]').map(Number);

    // Get sorting option
    const sorting = formData.get('sorting');

    // Get price range from input fields
    const minPrice = parseFloat(document.querySelector('.input-min').value) || 0;
    const maxPrice = parseFloat(document.querySelector('.input-max').value) || Infinity;

    let cardsArray = Array.from(carCards);

    // Filtering logic
    cardsArray.forEach(card => {
      const price = parseFloat(card.dataset.price);
      const rating = parseInt(card.dataset.rating);

      const matchesRating = selectedRatings.length === 0 || selectedRatings.includes(rating);
      const matchesPrice = price >= minPrice && price <= maxPrice;

      // Show/hide based on filters
      if (matchesRating && matchesPrice) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });

    // Sorting logic
    if (sorting) {
      cardsArray.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price);
        const priceB = parseFloat(b.dataset.price);
        const ratingA = parseFloat(a.dataset.rating);
        const ratingB = parseFloat(b.dataset.rating);

        switch (sorting) {
          case 'price-low-high':
            return priceA - priceB;
          case 'price-high-low':
            return priceB - priceA;
          case 'rating-low-high':
            return ratingA - ratingB;
          case 'rating-high-low':
            return ratingB - ratingA;
        }
      });

      // Reorder the DOM based on sorted order
      const container = document.getElementById('car-results');
      cardsArray.forEach(card => container.appendChild(card)); // Move the sorted cards back to the container
    }
  }

  // Bind the change event to filter form inputs (checkboxes, radio buttons, and range inputs)
  filterForm.addEventListener('change', applyFilters);

  // Synchronize price input fields with sliders
  const minPriceInput = document.querySelector('.input-min');
  const maxPriceInput = document.querySelector('.input-max');
  const minRange = document.querySelector('.range-min');
  const maxRange = document.querySelector('.range-max');

  minPriceInput.addEventListener('input', () => {
    minRange.value = minPriceInput.value;
    applyFilters(); // Re-apply filters when price inputs change
  });

  maxPriceInput.addEventListener('input', () => {
    maxRange.value = maxPriceInput.value;
    applyFilters(); // Re-apply filters when price inputs change
  });

  minRange.addEventListener('input', () => {
    minPriceInput.value = minRange.value;
    applyFilters(); // Re-apply filters when range sliders change
  });

  maxRange.addEventListener('input', () => {
    maxPriceInput.value = maxRange.value;
    applyFilters(); // Re-apply filters when range sliders change
  });

  // Optional: Apply filters on page load
  applyFilters();
});
</script>


<?php get_footer(); ?>
