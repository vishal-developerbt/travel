<?php

/**
 * Template Name: Hotel Booking list
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
<?php
$location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
$rooms = isset($_GET['rooms']) ? sanitize_text_field($_GET['rooms']) : '';
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'most-popular';
if (!empty($location) && !empty($checkin) && !empty($checkout) && !empty($rooms)) {
    // If search parameters exist, fetch filtered hotels
    $booking_data = fetch_homeHotel_booking_listings($location, $checkin, $checkout, $rooms);
    $status = $booking_data['status'];
    $hotels = $booking_data['itineraries'];
} else {
    // Otherwise, fetch all listings
    $booking_data = fetch_booking_listings();

    $status = isset($booking_data['status']) ? $booking_data['status'] : 0;
    $hotels = isset($booking_data['itineraries']) ? $booking_data['itineraries'] : 0;


    // echo "<pre>"; print_r($status); die();
}
$totalHotels = isset($status['totalResults']) ? $status['totalResults'] : 0;
?>
<!-- Navbar and Search Bar -->
<div class="flight-select-section mt-4">
    <div class="container">
        <form method="GET" action="/travel/booking-lists/" onsubmit="formatRoomsInput()">
            <div class="row g-2 align-items-center">
                <!-- City, Property Name -->
                <div class="col-md-3 text-start">
                    <div class="location-box mt-2">
                        <label class="location-label">
                            <i class="fa-solid fa-location-dot"></i> City, Location
                        </label>
                        <input type="text" name="location" class="form-control search-city-property"
                            value="<?php echo isset($_GET['location']) ? esc_attr($_GET['location']) : ''; ?>" 
                            placeholder="Enter a city or location">
                    </div>
                </div>

                <!-- Check-in Date -->
                <div class="col-md-2">
                    <div class="date-box">
                        <label class="date-label">Check-in</label>
                        <input type="date" name="checkin" id="check-in" 
                            value="<?php echo isset($_GET['checkin']) ? esc_attr($_GET['checkin']) : ''; ?>" 
                            class="form-control date-input" placeholder="Check-in Date">
                    </div>
                </div>

                <!-- Check-out Date -->
                <div class="col-md-2">
                    <div class="date-box">
                        <label class="date-label">Check-out</label>
                        <input type="date" name="checkout" id="check-out" 
                            value="<?php echo isset($_GET['checkout']) ? esc_attr($_GET['checkout']) : ''; ?>" 
                            class="form-control date-input" placeholder="Check-out Date">
                    </div>
                </div>

                <!-- Rooms & Guests -->
                <div class="col-md-3 mt-2 text-start rooms-home-border" id="roomsDropdownContainer">
                    <label class="rooms-home-page-text">
                        <span><i class="fa-solid fa-user"></i></span>
                        <span class="rooms-home-page-text-main">Rooms for</span>
                    </label>

                    <!-- Custom Button for Dropdown -->
                    <div id="roomsToggleBtn" class="form-control check-in-passenger-text">
                      Select Rooms <i class="fa-solid fa-caret-down"></i>
                    </div>

                    <!-- Hidden Dropdown Content -->
                    <div id="roomsDropdown" class="rooms-popup" style="display: none;">
                        <strong>Rooms & Guests</strong>
                        <div class="rooms-options">
                            <!-- Rooms -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <span>Rooms</span>
                              <div>
                                <button   type="button"  class="btn btn-sm btn-outline-secondary me-1" onclick="updateRoomGuest('roomSelector1_rooms', -1)">-</button>
                                <span id="roomSelector1_roomsCount">1</span>
                                <button  type="button"   class="btn btn-sm btn-outline-secondary ms-1" onclick="updateRoomGuest('roomSelector1_rooms', 1)">+</button>
                              </div>
                            </div>
                      
                            <!-- Adults -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <span>Adults</span>
                              <div>
                                <button   type="button"  class="btn btn-sm btn-outline-secondary me-1" onclick="updateRoomGuest('roomSelector1_adults', -1)">-</button>
                                <span id="roomSelector1_adultsCount">2</span>
                                <button  type="button"  class="btn btn-sm btn-outline-secondary ms-1" onclick="updateRoomGuest('roomSelector1_adults', 1)">+</button>
                              </div>
                            </div>
                      
                            <!-- Children -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <div>
                                <span class="childeren-text">Children</span><br>
                                <small class="children-age">0 - 17 Years Old</small>
                              </div>
                              <div>
                                <button  type="button"  class="btn btn-sm btn-outline-secondary me-1" onclick="updateRoomGuest('roomSelector1_children', -1)">-</button>
                                <span id="roomSelector1_childrenCount">0</span>
                                <button  type="button"  class="btn btn-sm btn-outline-secondary ms-1" onclick="updateRoomGuest('roomSelector1_children', 1)">+</button>
                              </div>
                            </div>
                        </div>
              
                        <!-- Apply button -->
                        <div class="mt-3">
                            <button type="button"   class="btn-apply-rooms" onclick="applyRoomSelection()">APPLY</button>
                        </div>
                    </div>
                </div>

                <!-- Hidden Input for Rooms -->
                <input type="hidden" name="rooms" id="roomsInput">

                <!-- Modify Search Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn modify-search-btn w-100">
                        <span class="modify-search-text">Modify Search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sorting Options -->
<!-- <div class="container mt-3 sort-by-section">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h5 class="me-3 sort-by-text-main">Sort By:</h5>

            <a href="?sort=most-popular" class="btn btn-sort most-popular-select <?php //echo ($sort_option === 'most-popular') ? 'active' : ''; ?>">
                <span class="most-popular-select-text">Most Popular</span>
            </a>

            <a href="?sort=low-to-high" class="btn btn-sort price-low-to-high <?php //echo ($sort_option === 'low-to-high') ? 'active' : ''; ?>">
                <span class="price-section-bottom-popular price-low-to-high-sections">Price - Low to High</span>
            </a>

            <a href="?sort=high-to-low" class="btn btn-sort price-high-to-low <?php //echo ($sort_option === 'high-to-low') ? 'active' : ''; ?>">
                <span class="price-section-bottom-popular price-low-to-high-sections">Price - High to Low</span>
            </a>
        </div>
    </div>
</div> -->

<div class="container mt-4  properties-in-france-section">
    <div class="row">
        <!-- Filters Section -->
        <div class="col-md-3">
            <div class="filter-section">
                <div class="d-flex justify-content-between align-items">
                    <div>
                        <h5 class="select-filter-section">Select Filters</h5>
                    </div>

                    <div>
                        <p><a href="#" class="clear-all-text">Clear All</a></p>
                    </div>
                </div>
                <hr>
                <form id="filterForm">
                    <!-- Rating Checkboxes -->
                    <strong class="mb-3">Sort by Rating</strong>
                    <div class="d-flex flex-column gap-2">
                        <label><input type="checkbox" name="rating[]" value="1"> 1 Star</label>
                        <label><input type="checkbox" name="rating[]" value="2"> 2 Stars</label>
                        <label><input type="checkbox" name="rating[]" value="3"> 3 Stars</label>
                        <label><input type="checkbox" name="rating[]" value="4"> 4 Stars</label>
                        <label><input type="checkbox" name="rating[]" value="5"> 5 Stars</label>
                    </div>
                    <hr/>
                        <!-- noUiSlider -->
                    <div class="mt-3" id="sorting">
                        <div class="d-flex flex-column gap-3">
                            <strong>Sort by</strong>
                            <label><input type="radio" name="sorting" value="price-low-high"> Price: Low to High</label>
                            <label><input type="radio" name="sorting" value="price-high-low"> Price: High to Low</label>
                            <label><input type="radio" name="sorting" value="rating-low-high"> Rating: Low to High</label>
                            <label><input type="radio" name="sorting" value="rating-high-low"> Rating: High to Low</label>
                        </div>
                    </div>
                    <hr/>
                    <h5 class="select-filter-section mt-3 mb-3">Sort BY Price</h5>

                    <div id="price-range"></div>
                    <div id="price-values">₹100 – ₹500000</div>
                    <input type="hidden" name="price_min" id="price_min">
                    <input type="hidden" name="price_max" id="price_max">
                    <input type="hidden" name="location" value="<?php echo $location ?>" id="location">
                    <input type="hidden" name="checkin" value="<?php echo $checkin ?>" id="checkin">
                    <input type="hidden" name="checkout" value="<?php echo $checkout ?>" id="checkout">
                    <input type="hidden" name="rooms" value="<?php echo $rooms ?>" id="rooms">
                </form>
                <hr>

                <link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css" rel="stylesheet">
             
                <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
                <script>
                  const slider = document.getElementById('price-range');
                  const priceDisplay = document.getElementById('price-values');
                  const priceMin = document.getElementById('price_min');
                  const priceMax = document.getElementById('price_max');
                  const sorting = document.getElementById('sorting');

                  noUiSlider.create(slider, {
                    start: [100, 500000],
                    connect: true,
                    step: 100,
                    range: {
                      'min': 100,
                      'max': 500000
                    },
                    tooltips: [true, true],
                    format: {
                      to: value => Math.round(value),
                      from: value => Number(value)
                    }
                  });

                  slider.noUiSlider.on('update', function(values) {
                    priceMin.value = values[0];
                    priceMax.value = values[1];
                    priceDisplay.textContent = `₹${values[0]} – ₹${values[1]}`;
                  });

                  slider.noUiSlider.on('change', fetchHotels);

                  // Fetch hotels via AJAX
                  function fetchHotels() {
                    const form = document.getElementById('filterForm');
                    const formData = new FormData(form);
                    const ajaxBaseUrl = "<?php echo esc_url(site_url('/filter_hotels.php')); ?>";

                    fetch(ajaxBaseUrl, {
                      method: 'POST',
                      body: formData
                    })
                    .then(res => res.text())
                    .then(data => {
                      document.getElementById('hotelResults').innerHTML = data;
                    });
                  }

                  // Trigger fetch on rating checkbox change
                  document.querySelectorAll('#filterForm input[type="checkbox"]').forEach(cb => {
                    cb.addEventListener('change', fetchHotels);
                  });

                  // ✅ Trigger fetch on sorting radio change
                  document.querySelectorAll('#sorting input[type="radio"]').forEach(rb => {
                    rb.addEventListener('change', fetchHotels);
                  });

                  // Initial load
                  window.onload = fetchHotels;
                </script>       
            </div>
        </div>

        <!-- Hotel Listings -->
        <div class="col-md-9">
            <h5 class="france-section-text">
                <?php echo $totalHotels; ?> Properties in
                <?php echo esc_html(isset($location) && !empty($location) ? $location : 'this location'); ?>
            </h5>


            <?php if (!$hotels) {
                $hotels_paginated = [];
                $current_page = '';
                $total_pages = '';

                echo "<div class='alert alert-warning text-center' role='alert'>No hotels found for this location.        </div>";
            } else {
                // Pagination settings
           
                $per_page = 5;
                $total_hotels = count($hotels);
                $total_pages = ceil($total_hotels / $per_page);

                // Get current page number (fix for Pretty URLs & Query Strings)
                $current_page = get_query_var('paged') ? get_query_var('paged') : 1;

                // Ensure the page number is valid
                $current_page = max(1, min($current_page, $total_pages));

                // Calculate the offset for slicing the array
                $offset = ($current_page - 1) * $per_page;
                $hotels_paginated = array_slice($hotels, $offset, $per_page);
            } ?>
            <!-- Hotel Card -->
            <div id="hotelResults" > <div class="loader"></div></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // ✅ Populate Room/Guest values from URL
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const roomsParam = urlParams.get("rooms");

    if (roomsParam) {
        const [rooms, adults, children] = roomsParam.split("-").map(Number);

        // Set values to counters
        document.getElementById("roomSelector1_roomsCount").textContent = rooms;
        document.getElementById("roomSelector1_adultsCount").textContent = adults;
        document.getElementById("roomSelector1_childrenCount").textContent = children;

        // Set display text for toggle button
        const displayText = `${rooms} Room${rooms > 1 ? 's' : ''} · ${adults} Adult${adults > 1 ? 's' : ''} · ${children} Child${children !== 1 ? 'ren' : ''}`;
        document.getElementById("roomsToggleBtn").innerHTML = `${displayText} <i class="fa-solid fa-caret-down"></i>`;

        // Set hidden input value so it's not lost if user re-submits
        document.getElementById("roomsInput").value = `${rooms}-${adults}-${children}`;
    }
});

    // Function to format the 'rooms' input before submitting the form
    function formatRoomsInput() {
        let roomNo = document.getElementById("roomNo").value;
        let adultNo = document.getElementById("adultNo").value;
        let childNo = document.getElementById("childNo").value;

        let roomsValue = `${roomNo}-${adultNo}-${childNo}`;
        document.getElementById("roomsInput").value = roomsValue;
    }

    // Function to preselect dropdown values based on URL parameters
    function prefillRoomsFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("rooms")) {
            let roomsParam = urlParams.get("rooms").split("-");
            if (roomsParam.length === 3) {
                document.getElementById("roomNo").value = roomsParam[0];
                document.getElementById("adultNo").value = roomsParam[1];
                document.getElementById("childNo").value = roomsParam[2];
            }
        }
    }

    // Run prefill function on page load
    document.addEventListener("DOMContentLoaded", prefillRoomsFromURL);
</script>
<script>

     
const roomsToggleBtn = document.getElementById("roomsToggleBtn");
const roomsDropdown = document.getElementById("roomsDropdown");
  
// Toggle dropdown
 roomsToggleBtn.addEventListener("click", function (e) {
  console.log('Toggle clicked'); // ✅ Check if this runs
  e.stopPropagation();
  e.preventDefault();

  roomsDropdown.style.display = roomsDropdown.style.display === "block" ? "none" : "block";
});


  
// Close dropdown when clicking outside
document.addEventListener("click", function (e) {

  if (!roomsDropdown.contains(e.target) && e.target !== roomsToggleBtn) {
    roomsDropdown.style.display = "none";
  }
});

// Update counter
function updateRoomGuest(type, change) {
  const el = document.getElementById(type + 'Count');
  let value = parseInt(el.textContent);
  let newValue = value + change;

  if (type.endsWith('rooms') && newValue < 1) newValue = 1;
  if (type.endsWith('adults') && newValue < 1) newValue = 1;
  if (type.endsWith('children') && newValue < 0) newValue = 0;

  el.textContent = newValue;
}
  
// Apply selection
function applyRoomSelection() {
    const rooms = parseInt(document.getElementById("roomSelector1_roomsCount").textContent);
    const adults = parseInt(document.getElementById("roomSelector1_adultsCount").textContent);
    const children = parseInt(document.getElementById("roomSelector1_childrenCount").textContent);

    const displayText = `${rooms} Room${rooms > 1 ? 's' : ''} · ${adults} Adult${adults > 1 ? 's' : ''} · ${children} Child${children !== 1 ? 'ren' : ''}`;
    roomsToggleBtn.innerHTML = `${displayText} <i class="fa-solid fa-caret-down"></i>`;
    roomsDropdown.style.display = "none";

    // ✅ Set the value to hidden input in "rooms-adults-children" format
    document.getElementById("roomsInput").value = `${rooms}-${adults}-${children}`;
}

  
flatpickr("#check-in, #check-out", {
    mode: "range",
    dateFormat: "d M Y",
    minDate: "today",
    showMonths: 2,
    onClose: function(selectedDates) {
        if (selectedDates.length === 2) {
            document.getElementById("check-in").value = flatpickr.formatDate(selectedDates[0], "Y-m-d");
            document.getElementById("check-out").value = flatpickr.formatDate(selectedDates[1], "Y-m-d");
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
   // alert('nugjkjb');
    const cards = document.querySelectorAll('.hotel-card');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const batchSize = 5;
    let currentVisible = 10;

    loadMoreBtn.addEventListener('click', () => {
        let revealed = 0;

        for (let i = currentVisible; i < cards.length && revealed < batchSize; i++) {
            cards[i].style.display = 'block';
            revealed++;
        }

        currentVisible += revealed;

        // Hide button if all are visible
        if (currentVisible >= cards.length) {
            loadMoreBtn.style.display = 'none';
        }
    });
});
</script>
<?php get_footer(); ?>