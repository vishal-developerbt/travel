<section class="hero">
    <div class="container d-flex flex-column align-items-center text-center home-page-banner">
        <h1 class="fw-bold text-dark great-experience">Find and Book a Great Experience</h1>
        <p class="text-dark book-hotels-homestay">Book hotels and homestays @Up to 35% OFF</p>
        <div class="search-container mt-3">
            <div class="hotels-section-booking">
                <div class="d-flex justify-content-center mb-3">
                    <button
                    class="tab-btn active-tab search-hotel-homepage d-flex align-items-center justify-content-center px-3 py-2   home-page-hotels-sections"
                            onclick="showSearchTab('search-hotels', event)">
                        <div class="flight-section-home-page d-flex align-items-center gap-2">
                            <span class="home-image-homepage">
                                <img src="<?php echo get_template_directory_uri(); ?>/photos/star-hotel.png" alt="" class="img-fluid"
                                    style="max-height: 24px;">
                            </span>
                            <span class="hotels-tab-section-home-page">Hotels</span>
                        </div>
                    </button>
                    <button class="tab-btn " onclick="showSearchTab('search-flights', event)">
                        <div class="flight-section-home-page d-flex align-items-center gap-3">
                            <span class="home-image-homepage  aeroplane-image-home-page"> <img src="<?php echo get_template_directory_uri(); ?>/photos/aeroplane.png" alt=""></span>
                            <span class="hotels-tab-section-home-page">Flights</span>
                        </div>
                    </button>
                </div>
            </div>
            <!-- Hotels Tab Content -->
            <form action="<?php echo site_url('/booking-lists/'); ?>" method="GET" onsubmit="formatRoomsInput()">
                <div id="search-hotels" class="tab-content active">
                    <div class="row g-3 search-container-booking flight-section-search-sections">
                        <!-- Location Input -->
                    <div class="col-12 col-md-6 col-lg-3 text-start">
                            <label class="fw-bold loaction-text-homepage">
                                <i class="fa-solid fa-location-dot"></i> Locations
                            </label>
                            <input type="text" name="location" class="form-control search-city-property"
                                placeholder="Search City, Locations" required>
                        </div>

                        <!-- Check-in Date -->
                        <div class="col-12 col-md-6 col-lg-2 text-start check-in-page">
                            <label class="fw-bold check-in-text-home-page">
                                <div class="d-flex align-items-center">
                                    <span><i class="fa-solid fa-calendar-days"></i></span>
                                    <span class="check-in-text-main-page">Check-in</span>
                                </div>
                            </label>
                            <input type="text" required name="checkin" id="check-in" class="form-control check-in-passenger-text" placeholder="Check-in Date">
                        </div>

                        <!-- Check-out Date -->
                        <div class="col-12 col-md-6 col-lg-2 text-start checkout-page">
                            <label class="fw-bold check-out-page-home-page">
                                <div class="d-flex align-items-center">
                                    <span><i class="fa-solid fa-calendar-days"></i></span>
                                    <span class="check-out-text-main-page">Check-out</span>
                                </div>
                            </label>
                            <input type="text" required name="checkout" id="check-out" class="form-control check-out-passenger-text" placeholder="Check-out Date">
                        </div>
                    <!-- Rooms & Guests -->
                    <div class="col-12 col-md-6 col-lg-3 text-start rooms-home-border" id="roomsDropdownContainer">
                        <label class="rooms-home-page-text">
                                <span><i class="fa-solid fa-user"></i></span>
                                <span class="rooms-home-page-text-main">Rooms for</span>
                            </label>
                
                        <!-- Button to open dropdown -->
                        <div id="roomsToggleBtn" class="form-control check-in-passenger-text">
                            Select Rooms <i class="fa-solid fa-caret-down"></i>
                        </div>

            
                        <!-- Dropdown Content -->
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
                        <!-- Search Button -->
                        <div class="col-md-2 text-start d-flex align-items-end search-button-home-page-banner">
                            <button type="submit" class="btn search-btn w-100 search-button-home-pages">
                            <span class="search-button-text">Search</span> </button>
                        </div>
                    </div>
                </div>
            </form>
         <!-- Flights Tab Content -->
        <div id="search-flights" class="tab-content d-none">
            <div class="row g-3 search-container-booking">
                <!-- Radio Buttons (One-Way / Round-Trip) -->
                <div class="col-12 d-flex gap-3  main-section-home-page">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tripType" id="oneWay" checked>
                            <label class="form-check-label" for="oneWay" class="one-way-text">
                                One-Way
                            </label>
                    </div>
                    <div class="form-checks aeroplane-radio-button">
                        <input class="form-check-input" type="radio" name="tripType" id="roundTrip">
                        <label class="form-check-label" for="roundTrip" class="round-trip-home">
                            Round-Trip
                        </label>
                    </div>
                 </div>
                <!-- Location -->
                <div class="col-12 col-md-6 col-lg-2 text-start">
                    <label class="rooms-home-page-text">
                        <div class="d-flex gap-1 align-items-center">
                            <div>
                                <i class="fa-solid fa-plane aeroplane-image-home-page"></i>
                            </div>
                            <div>
                                <span class="departure-text-home-page">From</span>
                            </div>
                        </div>
                    </label>
                    <input type="text" class="form-control" id="departure_airport" name="departure_airport" placeholder="Departure Airport">
               </div> 
                  
                <div class="col-12 col-md-6 col-lg-2 text-start location-flight-section">
                    <label class="fw-bold loaction-text-homepage">
                        <i class="fa-solid fa-location-dot"></i> To
                    </label>
                    <input type="text" class="form-control search-flight-location"  id="flight_location" name="flight_location" placeholder="Search Location">
                 </div>

                <!-- Destination -->
               <div class="col-12 col-md-6 col-lg-2 text-start checkout-page">
                    <label class="fw-bold check-out-page-home-page">
                        <div class="d-flex  align-items-center">
                            <div>
                                <span><i class="fa-solid fa-calendar-days"></i></span>
                            </div>
                            <div class="mb-1">
                                <span class="check-in-text-main-page">
                                    Departure
                                </span>
                            </div>
                        </div>
                    </label>
                    <input type="text" id="checkinDate" class="form-control check-in-passanger-text" placeholder="Departure Date">
               </div>
                <!-- Departure -->
                <div class="col-12 col-md-6 col-lg-2 text-start checkout-page">
                    <label class="fw-bold check-out-page-home-page">
                        <div class="d-flex  align-items-center">
                            <div>
                                <span><i class="fa-solid fa-calendar-days"></i></span>
                            </div>
                            <div class="mb-1">
                                <span class="check-in-text-main-page">
                                    Return
                                </span>
                            </div>
                        </div>
                    </label>
                    <input type="text" id="checkoutDate" class="form-control check-in-passanger-text" placeholder="Return date">
                </div>
                <div class="col-12 col-md-6 col-lg-2 mb-0 text-start depature-test-home-page position-relative">
                    <label class="rooms-home-page-text">
                        <div class="align-items-center gap-1">
                            <div>
                              <i class="fa-solid fa-user"></i>
                              <label for="">Travellers</label>
                            </div>
                            <div class="mb-2 mt-2">
                              <button id="toggleDropdown" class="form-control Travellers-text">
                                Select travel type <i class="fa-solid fa-caret-down"></i>
                              </button>
                            </div>
                        </div>
                    </label>
                    <!-- Dropdown -->
                    <div id="passengerDropdown" class="popup">
                       <!-- Adults -->
                        <div class="d-flex justify-content-between mt-3 align-items-center">
                            <h6 class="adult-text">ADULTS (12y+)</h6>
                            <div class="d-flex align-items-center">
                              <button class="count-btn" onclick="updateCount('adults', -1)">-</button>
                              <span id="adultsCount" class="count-value">1</span>
                              <button class="count-btn" onclick="updateCount('adults', 1)">+</button>
                            </div>
                        </div>
                          <!-- Children -->
                          <div class="d-flex justify-content-between mt-3 align-items-center">
                            <h6 class="adult-text">CHILDREN (2-12y)</h6>
                            <div class="d-flex align-items-center">
                              <button class="count-btn" onclick="updateCount('children', -1)">-</button>
                              <span id="childrenCount" class="count-value">0</span>
                              <button class="count-btn" onclick="updateCount('children', 1)">+</button>
                            </div>
                          </div>
                          <!-- Infants -->
                          <div class="d-flex justify-content-between mt-3 align-items-center">
                            <h6 class="adult-text">INFANTS (below 2y)</h6>
                            <div class="d-flex align-items-center">
                              <button class="count-btn" onclick="updateCount('infants', -1)">-</button>
                              <span id="infantsCount" class="count-value">0</span>
                              <button class="count-btn" onclick="updateCount('infants', 1)">+</button>
                            </div>
                          </div>
                          <!-- Travel Class -->
                           <div class="mt-3">
                            <h6 class="adult-text">CHOOSE TRAVEL CLASS</h6>
                            <div class="flex gap-2">
                              <button class="btn-class active">Economy</button>
                              <button class="btn-class">Business</button>
                              <button class="btn-class">First Class</button>
                            </div>
                          </div>
                      
                          <div class="mt-3">
                            <button class="btn-apply" onclick="applySelection()">APPLY</button>
                          </div> 
                    </div>
                </div>
                <!-- Search Button -->
                <div class="col-12 col-md-6 col-lg-2 text-start d-flex align-items-end search-button-home-page-banner">
                    <button class="btn search-btn w-100 search-button-home-pages">
                        <span class="search-button-text">Search</span>
                    </button>
                </div>
            </div>
        </div>
   </div>
</div>
</section>
<div class="shape-text-image">
  <img src="<?php echo get_template_directory_uri(); ?>/photos/shape-image.png" alt="" style="width: 100%; height: 100%;">
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    function formatRoomsInput() {
        const rooms = document.getElementById('roomSelector1_roomsCount').innerText.trim();
        const adults = document.getElementById('roomSelector1_adultsCount').innerText.trim();
        const children = document.getElementById('roomSelector1_childrenCount').innerText.trim();

        const value = `${rooms}-${adults}-${children}`;
        document.getElementById('roomsInput').value = value;
    }

    function validateForm() {
        formatRoomsInput();

        const location = document.querySelector('input[name="location"]').value;
        const checkin = document.querySelector('input[name="checkin"]').value;
        const checkout = document.querySelector('input[name="checkout"]').value;
        const rooms = document.getElementById('roomsInput').value;

        if (!location || !checkin || !checkout || !rooms) {
            alert("Please fill in all required fields.");
            return false;
        }
        return true;
    }
</script>
<script>
  
    document.addEventListener("DOMContentLoaded", function () {
        const oneWay = document.getElementById("oneWay");
        const roundTrip = document.getElementById("roundTrip");
        const checkinInput = document.getElementById("checkinDate");
        const checkoutInput = document.getElementById("checkoutDate");

        let datePicker;

        function initFlatpickr(mode = "range") {
            if (datePicker) {
                datePicker.destroy(); // destroy old instance
            }

            datePicker = flatpickr(checkinInput, {
                mode: mode,
                dateFormat: "Y-m-d",
                minDate: "today",
                showMonths: 2,
                onClose: function (selectedDates) {
                    if (mode === "range") {
                        if (selectedDates.length === 2) {
                            checkinInput.value = this.formatDate(selectedDates[0], "Y-m-d");
                            checkoutInput.value = this.formatDate(selectedDates[1], "Y-m-d");
                        }
                    } else if (mode === "single") {
                        if (selectedDates.length === 1) {
                            checkinInput.value = this.formatDate(selectedDates[0], "Y-m-d");
                            checkoutInput.value = "";
                        }
                    }
                }
            });
        }

        function setOneWay() {
            checkoutInput.value = "";
            checkoutInput.disabled = true;
            checkoutInput.classList.add("bg-light", "pointer-events-none");
            initFlatpickr("single");
        }

        function setRoundTrip() {
            checkoutInput.disabled = false;
            checkoutInput.classList.remove("bg-light", "pointer-events-none");
            initFlatpickr("range");
        }

        // Initial mode
        if (oneWay.checked) {
            setOneWay();
        } else {
            setRoundTrip();
        }

        oneWay.addEventListener("change", function () {
            if (this.checked) {
                setOneWay();
            }
        });

        roundTrip.addEventListener("change", function () {
            if (this.checked) {
                setRoundTrip();
            }
        });

        // Open calendar when focusing return date
        checkoutInput.addEventListener("focus", function () {
            checkinInput.focus();
            datePicker.open();
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.getElementById("toggleDropdown");
        const dropdown = document.getElementById("passengerDropdown");

        // Toggle dropdown visibility
        toggleBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        // Hide dropdown on outside click
        document.addEventListener("click", function (e) {
            if (!dropdown.contains(e.target) && e.target !== toggleBtn) {
                dropdown.style.display = "none";
            }
        });

        // Handle travel class active button
        document.querySelectorAll('.btn-class').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.btn-class').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Update traveller count
        window.updateCount = function (type, change) {
            const countEl = document.getElementById(type + 'Count');
            let current = parseInt(countEl.textContent);
            let newCount = current + change;

            if (type === 'adults' && newCount < 1) newCount = 1;
            else if (newCount < 0) newCount = 0;

            countEl.textContent = newCount;
        }

        // Apply button action
        window.applySelection = function () {
            const adults = parseInt(document.getElementById("adultsCount").textContent);
            const children = parseInt(document.getElementById("childrenCount").textContent);
            const infants = parseInt(document.getElementById("infantsCount").textContent);
            const total = adults + children + infants;
            const travelClass = document.querySelector(".btn-class.active")?.textContent || "Not Selected";

            toggleBtn.innerHTML = `
                <div style="text-align:center; display:flex; flex-direction:column; width:100%;">
                    <span style="font-size:14px; margin-bottom:3px; margin-left:auto;">${total} Traveller${total > 1 ? 's' : ''}</span>
                    <span style="font-size:14px; margin-left:auto;">${travelClass}</span>
                </div>
            `;

            dropdown.style.display = "none";
        }
    });
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

        const displayText = `${rooms} Room${rooms > 1 ? 's' : ''} , ${adults} Adult${adults > 1 ? 's' : ''} , ${children} Child${children !== 1 ? 'ren' : ''}`;
        roomsToggleBtn.innerHTML = `${displayText} <i class="fa-solid fa-caret-down"></i>`;
        roomsDropdown.style.display = "none";

        // ✅ Set value in hidden input using hyphen format: rooms-adults-children
        document.getElementById("roomsInput").value = `${rooms}-${adults}-${children}`;
    }

    
        // end hotel-room tab section

        document.addEventListener("DOMContentLoaded", function() {
            function initDatePicker(checkInId, checkOutId) {
                flatpickr("#" + checkInId, {
                    mode: "range",
                    dateFormat: "d M Y",
                    minDate: "today",
                    showMonths: 2,
                    onClose: function(selectedDates) {
                        if (selectedDates.length === 2) {
                            document.getElementById(checkInId).value = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                            document.getElementById(checkOutId).value = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                        }
                    }
                });
            }

            // Initialize Flatpickr for each pair
            initDatePicker("check-in", "check-out");
            initDatePicker("check-in-1", "check-out-1");

            // room section javscript
            // Open and Close Dropdown

        });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        
         document.getElementById("departure_airport").addEventListener("click", function () {
              this.select();
         });

         document.getElementById("flight_location").addEventListener("click", function () {
              this.select();
         });

        document.body.addEventListener("click", function (e) {
            const searchBtn = e.target.closest("#search-flights .search-btn");
            if (!searchBtn) return;

            e.preventDefault();
            console.log("✈️ Flight search triggered!");
            // Trip type
            const tripType = document.querySelector('input[name="tripType"]:checked')?.id === 'oneWay' ? 'OneWay' : 'Return';

            // Get full input text
            const originFull = document.getElementById("departure_airport")?.value || '';
            const destinationFull = document.getElementById("flight_location")?.value || '';

            // Extract airport codes (the value between first comma and second comma)
            const extractCode = (text) => {
                const parts = text.split(',');
                return parts.length >= 2 ? parts[1].trim() : '';
            };

            const origin = extractCode(originFull);
            const destination = extractCode(destinationFull);

            // Dates
            const departureDate = document.querySelector('#checkinDate')?.value;
            const returnDate = document.querySelector('#checkoutDate')?.value;

            // Passenger counts
            const adults = parseInt(document.getElementById('adultsCount')?.innerText || 1);
            const children = parseInt(document.getElementById('childrenCount')?.innerText || 0);
            const infants = parseInt(document.getElementById('infantsCount')?.innerText || 0);

            // Class
            const travelClass = document.querySelector('.btn-class.active')?.innerText || 'Economy';

            // Validate
            if (!origin || !destination || !departureDate) {
                alert("Please fill in all required fields.");
                return;
            }

            // Build query string
            const queryParams = new URLSearchParams({
                tripType,
                origin,
                destination,
                departureDate,
                returnDate: tripType === 'Return' ? returnDate : '',
                class: travelClass,
                adults,
                children,
                infants
            });
        const homeUrl = "<?php echo esc_url(site_url()); ?>";

            // Redirect to flight results page
        window.location.href = `${homeUrl}/flight-list/?${queryParams.toString()}`;

        });
    });
</script>

<script>
    function extractAirportCode(fullText) {
        const parts = fullText.split(',');
        // Airport code is expected to be the 2nd item (index 1)
        return parts.length > 1 ? parts[1].trim().toUpperCase() : '';
    }

</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
