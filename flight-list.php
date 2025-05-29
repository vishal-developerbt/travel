
<?php 
/**
 * Template Name: Flight Booking list
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
<?php 
    $args = [
        'tripType' => $_GET['tripType'] ?? 'OneWay',
        'origin' => $_GET['origin'] ?? '',
        'destination' => $_GET['destination'] ?? '',
        'departureDate' => $_GET['departureDate'] ?? '',
        'returnDate' => $_GET['returnDate'] ?? '',
        'class' => $_GET['class'] ?? 'Economy',
        'adults' => $_GET['adults'] ?? 1,
        'children' => $_GET['children'] ?? 0,
        'infants' => $_GET['infants'] ?? 0,
    ];
    $tripType = $args['tripType'];
    $origin = $args['origin'];
    $destination = $args['destination'];
    $departureDate = $_GET['departureDate'] ?? '';
    $returnDate = $_GET['returnDate'] ?? '';

    // Call API via your function
    $session_id='';

    $flights = get_flight_availability($args);
    if (!empty($flights['AirSearchResponse']['session_id'])) {
        $session_id = $flights['AirSearchResponse']['session_id'];
       
    }
?>
<div class="flight-list-page">
    <div class="container">
        <div class="trip-type">
            <label class="radio-container">
                <input type="radio" name="trip-type" id="one-way" value="OneWay" <?= $tripType === 'OneWay' ? 'checked' : '' ?>>
                <span id="one-way-text-about-trip">One way</span>
            </label>
            <label class="radio-container radio-container-for-round-trip">
                <input type="radio" name="trip-type" id="round-trip" value="Return" <?= $tripType === 'Return' ? 'checked' : '' ?>>
                <span id="round-trip-text">Round trip</span>
            </label>
        </div>

        <div class="search-form" id="search-flights">
            <div class="form-group">
                <label class="date-label">From</label>
                <!-- Visible city name for UI -->
                <input type="text" value="<?php echo esc_html(getCityNameByAirPortCode($origin)); ?>"       class="form-control" id="departure_airport_display" 
                       name="departure_airport_display" placeholder="Departure Airport">
                <!-- Hidden airport code for form submission -->
                <input type="hidden" value="<?php echo esc_attr($origin);?>" name="departure_airport"       id="departure_airport">
            </div>

            <div class="swipper-toggle-dustination">
                <div class="swap-icon" onclick="swapLocations()">⇄</div>
            </div>

            <div class="form-group">
                <label for="to">To</label>
                <!-- Visible city name for UI -->
                <input type="text" 
                       value="<?php echo esc_html(getCityNameByAirPortCode($destination)); ?>" 
                       class="form-control search-flight-location"  id="flight_location_display" 
                       name="flight_location_display"  placeholder="Search Location">
                <!-- Hidden airport code for form submission -->
                <input type="hidden" value="<?php echo esc_attr($destination); ?>" 
                       name="flight_location" id="flight_location">
            </div>

            <div class="form-group">
                <label for="departure">Departure</label>
                <!-- <input type="date" id="departure"> -->
                <input type="text" id="checkinDate" class="form-control check-in-passanger-text" placeholder="Departure Date" value="<?= esc_attr($departureDate) ?>">
            </div>

            <div class="form-group" id="return-group">
                <label for="return">Return</label>
                <!-- <input type="date" id="return"> -->

                <input type="text" id="checkoutDate" class="form-control check-in-passanger-text" placeholder="Return date" value="<?= esc_attr($returnDate) ?>">
            </div>

            <div class="form-group mb-0 text-start depature-test-home-page position-relative">
                <label class="rooms-home-page-text">
                    <div class="align-items-center gap-1">
                        <div>
                          <label for="">Travellers</label>
                        </div>
                        <div class="mb-2">
                            <button id="toggleDropdown" class="form-control Travellers-text">
                                Select travel type <i class="fa-solid fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                </label>
              
                <!-- Dropdown -->
                <div id="passengerDropdown"class="popup" style="display: none;">
                    <!-- Adults -->
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                        <h6 class="adult-text">ADULTS (12y+)</h6>
                        <div class="d-flex align-items-center">
                            <button class="count-btn" onclick="updateCount('adults', -1)">-</button>
                            <span id="adultsCount" class="count-value"><?= (int)($args['adults']) ?></span>
                            <button class="count-btn" onclick="updateCount('adults', 1)">+</button>
                        </div>
                    </div>
              
                    <!-- Children -->
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                    <h6 class="adult-text">CHILDREN (2-12y)</h6>
                        <div class="d-flex align-items-center">
                            <button class="count-btn" onclick="updateCount('children', -1)">-</button>
                            <span id="childrenCount" class="count-value"><?= (int)($args['children']) ?></span>
                            <button class="count-btn" onclick="updateCount('children', 1)">+</button>
                        </div>
                    </div>

                    <!-- Infants -->
                    <div class="d-flex justify-content-between mt-3 align-items-center">
                        <h6 class="adult-text">INFANTS (below 2y)</h6>
                        <div class="d-flex align-items-center">
                            <button class="count-btn" onclick="updateCount('infants', -1)">-</button>
                            <span id="infantsCount" class="count-value"><?= (int)($args['infants']) ?></span>
                            <button class="count-btn" onclick="updateCount('infants', 1)">+</button>
                        </div>
                    </div>
              
                    <!-- Travel Class -->
                    <div class="mt-3">
                        <h6 class="adult-text">CHOOSE TRAVEL CLASS</h6>
                        <div class="flex gap-2">
                            <button class="btn-class active">Economy</button>
                            <button class="btn-class">Business</button>
                            <button class="btn-class">PremiumEconomy</button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn-apply" onclick="applySelection()">APPLY</button>
                    </div>
                </div>
            </div>
            <div class="modified-btn-1">
                <button class="modify-btn search-flight" >Modify Search</button>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.body.addEventListener("click", function (e) {
                    const searchBtn = e.target.closest("#search-flights .search-flight");
                    if (!searchBtn) return;
                    
                    e.preventDefault();
                    console.log("✈️ Flight search triggered!");

                    const tripTypeElement = document.querySelector('input[name="trip-type"]:checked');
                    const tripType = tripTypeElement ? tripTypeElement.value : '';
                    const departureAirportFull = document.getElementById('departure_airport').value || '';
                    const destinationAirportFull = document.getElementById('flight_location').value || '';
                    const extractCode = (text) => {
                        const parts = text.split(',');
                        if (parts.length >= 2) {
                            return parts[1].trim();
                        }
                        return text.trim();
                    };

                    const originFromPHP = "<?php echo esc_js(isset($args['origin']) ? $args['origin'] : ''); ?>";
                    const destinationFromPHP = "<?php echo esc_js(isset($args['destination']) ? $args['destination'] : ''); ?>";
                    const origin = departureAirportFull ? extractCode(departureAirportFull) : originFromPHP;
                    const destination = destinationAirportFull ? extractCode(destinationAirportFull) : destinationFromPHP;
                    const departureDate = document.getElementById('checkinDate').value;
                    const returnDate = document.getElementById('checkoutDate').value;
                    const adults = parseInt(document.getElementById('adultsCount')?.innerText || 1);
                    const children = parseInt(document.getElementById('childrenCount')?.innerText || 0);
                    const infants = parseInt(document.getElementById('infantsCount')?.innerText || 0);
                    const travelClass = document.querySelector('.btn-class.active')?.innerText || 'Economy';
                    if (!origin || !destination) {
                        alert("Please fill in all required fields.");
                        return;
                    }

                    const queryParams = new URLSearchParams({
                        tripType,
                        origin,
                        destination,
                        departureDate,
                        returnDate: tripType === 'RoundTrip' ? returnDate : '',
                        class: travelClass,
                        adults,
                        children,
                        infants
                    });

                    // Get the home URL of the site (to construct the full URL for flight results)
                    const homeUrl = "<?php echo esc_url(site_url()); ?>";

                    // Redirect to the flight results page with the query parameters
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
    </div>
</div>

<div class="sort-section mb-3  ">
    <div class="container sort-section-1">
        <div class="d-flex align-items-center gap-2">
            <span class="sort-label">Sort By:</span>
            <div class="btn-group" role="group" aria-label="Sort options">
                <button type="button" class="btn btn-primary btn-sm active lowest-fares">Lowest fares</button>
                <button type="button" class="btn btn-outline-primary btn-sm shortest-duration">Shortest
                    Duration</button>
            </div>
        </div>
        <div class="flights-count" id="visible-flights-count">
            Showing 0 of 0 flights
        </div>
    </div>
</div>
<div class="container-fluid py-4">
    <div class="container">
        <div class="row">
            <!-- Left Sidebar - Filters -->
            <div class="col-md-3">
                <div class="filters-container flight-listing-section">
                    <!-- Select Filters -->
                    <div class="filter-section">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold filter-label">Select Filters</h6>
                            <a href="#" id="clear-all-filters" class="clear-all">Clear All</a>
                        </div>
    
                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <h6 class="filter-title">Price Range</h6>
                            <div class="price-range-slider">
                                <div class="d-flex justify-content-between mb-2">
                                    <span id="price-min-display">$0</span>
                                    <span id="price-max-display">$0</span>
                                </div>
                                <!-- <div class="multi-range-slider mb-2">
                                    <input type="range" id="price-slider-min" class="noUi-connect" min="0" max="0" step="1" value="0">
                                    <input type="range" id="price-slider-max" class="" min="0" max="0" step="1" value="0">
                                </div> -->
                    <div class="multi-range-slider mb-2">
                <div class="slider-track"></div>
                <div class="slider-fill" id="slider-fill"></div>
                <input type="range" id="price-slider-min" min="0" max="100" step="1" value="20">
                <input type="range" id="price-slider-max" min="0" max="100" step="1" value="80">
                </div>
                </div>
                        </div>
    
                        <!-- Airlines Filter -->
                        <div class="mb-4">
                            <h6 class="filter-title">Airlines</h6>
                            <div id="airlines-filters" class="airlines-checkbox-list">
                                <!-- Airlines checkboxes will be populated here by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Flight Results -->
            <div class="col-md-9">
                <div id="flights-container"> 
                    <?php
                        $fareList = $flights['AirSearchResponse']['AirSearchResult']['FareItineraries'] ?? [];
                        if (!empty($fareList)) {
                            foreach ($fareList as $fareItem):
                               
                                $fare = $fareItem['FareItinerary'];
                                $segment = $fare['OriginDestinationOptions'];
            
            foreach($fare['OriginDestinationOptions'] as $index => $farevalue){

                //Find out total time
                 $segment = $farevalue['OriginDestinationOption'][$index]['FlightSegment'];
                 $total_minutes = $segment['JourneyDuration'] + $segment['JourneyDuration'];
                 $hours = floor($total_minutes / 60);
                 $minutes = $total_minutes % 60;
                 $totalTime = sprintf("%02dh %02dm", $hours, $minutes);
                 
                 //Get other information from options//
                 $originOptions = $fare['OriginDestinationOptions'][$index]['OriginDestinationOption'];
                 $airlineName = '';
                 $flightCodes = [];

                 $flightsegment = $originOptions[$index]['FlightSegment'];
                 if (empty($airlineName) && !empty($flightsegment['MarketingAirlineName'])) {
                    $airlineName = $flightsegment['MarketingAirlineName'];
                 }

                 $airlineCode = $flightsegment['MarketingAirlineCode'] ?? 'XX';
                 $flightNumber = $flightsegment['FlightNumber'] ?? '000';
                 $flightCodes[] = $airlineCode . ' ' . $flightNumber;
                 $flightNumberString = implode(', ', $flightCodes);

                 $origin = $flightsegment['DepartureAirportLocationCode'] ?? 'XXX';
                 $destination = $flightsegment['ArrivalAirportLocationCode'] ?? 'YYY';
                 $departureTime = date("H:i", strtotime($flightsegment['DepartureDateTime']));
                 $arrivalTime = date("H:i", strtotime($flightsegment['ArrivalDateTime']));

                 $durationMin = (int)($flightsegment['JourneyDuration'] ?? 0);
                 $durationHours = floor($durationMin / 60);
                 $durationMins = $durationMin % 60;
                 $durationFormatted = sprintf("%02dh %02dm", $durationHours, $durationMins);

                 $price = $fare['AirItineraryFareInfo']['ItinTotalFares']['TotalFare']['Amount'] ?? '0';
                 $currency = $fare['AirItineraryFareInfo']['ItinTotalFares']['TotalFare']['CurrencyCode'] ?? 'USD';

                 //related to airline//
                 $airline_code = $fare['ValidatingAirlineCode'];
                 $airline_codea =$flightsegment['OperatingAirline']['Code'];
                 $logo_url = 'https://travelnext.works/api/airlines/'.$airline_codea.'.gif';

                // Unique ID and flight data JSON
                 $flightCardId = 'flight-' . uniqid();
                 $flightDataJson = htmlspecialchars(json_encode(['FareItinerary' => $fare]), ENT_QUOTES, 'UTF-8');
                 

                 $data = $fare['OriginDestinationOptions'][$index]['OriginDestinationOption'];
                 $firstDepartureAirportLocationCode = $data[$index]['FlightSegment']['DepartureAirportLocationCode'];
                 $firstDepartureDateTime = date("H:i", strtotime($data[$index]['FlightSegment']['DepartureDateTime']));
                 $secondArrivalAirportLocationCode = $data[$index]['FlightSegment']['ArrivalAirportLocationCode'];
                 $secondArrivalDateTime = date("H:i", strtotime($data[$index]['FlightSegment']['ArrivalDateTime']));
                 $secondDepartureDateTime = date("H:i", strtotime($data[$index]['FlightSegment']['DepartureDateTime']));

                 $totalStops = $fare['OriginDestinationOptions'][$index]['TotalStops'];
                 $data = array('totalstop'=>$totalStops);
                 $datasecuritycode['securitycode'][] = $secondArrivalAirportLocationCode;

                 //To finding layover time//
            // $currentArrival = $farevalue['OriginDestinationOption'][$index]['FlightSegment'];
            // $nextDeparture = $farevalue['OriginDestinationOption'][$index + 1]['FlightSegment']['DepartureDateTime'];

            //     // Convert to DateTime objects
            //     $arrivalTime = new DateTime($currentArrival);
            //     $departureTime = new DateTime($nextDeparture);

            //     // Calculate difference
            //     $interval = $arrivalTime->diff($departureTime);

            //     // Format difference - for example: "3 hrs 20 mins"
            //     $layover = '';
            //     if ($interval->h > 0) {
            //         $layover .= $interval->h . ' hrs ';
            //     }
            //     if ($interval->i > 0) {
            //         $layover .= $interval->i . ' mins';
            //     }

            //     echo "Layover between segment $i and " . ($i + 1) . ": " . trim($layover) . "\n";

                
            }//end foreach loop//    
                ?>
                        <div class="flight-card mb-3"
                             id="<?php echo esc_attr($flightCardId); ?>"
                             data-price="<?php echo esc_attr($price); ?>"
                             data-duration="<?php echo esc_attr($durationMin); ?>"
                             data-flight='<?php echo $flightDataJson; ?>'>

                            <div class="row g-0">
                                <div class="col-md-3 d-flex align-items-center">
                                    <div class="airline-info text-center">
                                        <div class="airline-logo">
                                            <div class="logo-bg">
                                                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($airlineName); ?> Logo" height="40" width="40">
                                            </div>
                                        </div>
                                        <div class="flight-details-1">
                                            <div class="airline-name"><?php echo esc_html($airlineName); ?></div>
                                            <div class="flight-number"><?php echo esc_html($flightNumberString); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php 

                                if($totalStops){?>
                                    <div class="col-md-1 d-flex flex-column justify-content-center time-place-flight">
                                    <div class="place-time-flight-th">
                                        <div class="departure-time"><?php echo esc_html($firstDepartureDateTime); ?></div>
                                        <div class="departure-city"><?php echo esc_html(getCityNameByAirPortCode($firstDepartureAirportLocationCode)); ?></div>
                                    </div>  
                                </div>
                                <div class="col-md-3 flight-dustination-duration">
                                    <div class="duration mb-1">

                                        <?php echo esc_html($totalTime); 

                $segments = $fare['OriginDestinationOptions'];
                $totalStops = $data['totalstop'];
                $datacity = [];
                 
                for ($i = 0; $i < $totalStops; $i++) {
                   
                   $arrivalAirport = $segments[$i]['OriginDestinationOption'][0]['FlightSegment']['ArrivalAirportLocationCode'] ?? null;
                    $countryCode = getCityNameByAirPortCode($arrivalAirport);
                    $datacity['countrycode'][] = $countryCode; 
                    
                }

                    ?>
                                    </div>
                                     <div class="tooltip-container">
     <div class="relative fliStopsSep"><p class="fliStopsSepLine" style="border-top: 3px solid rgb(13 110 253);"></p><span class="fliStopsDisc"></span></div>
        <button class="tooltip">------------</button>
        <span class="tooltip-text"> 
<?php

$totalStops = $data['totalstop'];
//echo "<pre/>"; print_r($datasecuritycode['securitycode']);
for ($i = 0; $i < $totalStops; $i++) {?>
    Plane change<span></span>
    <?php echo esc_html($datacity['countrycode'][$i]);if ($i < $totalStops) {echo ', ';}
    echo esc_html($datasecuritycode['securitycode'][$i]).'</br>'.'|';
}?>

(1st arrival diff 2nd departure 3 hrs 20 mins Layover</span>

                                </div>
                                     <div class="flight-type mt-2" ><?php echo $totalStops;?> stop via <?php echo esc_html(getCityNameByAirPortCode($secondArrivalAirportLocationCode)); ?>
                                     </div>
                                   
                                </div>

                                <div class="col-md-1 d-flex flex-column justify-content-center">
                                    <div class="delhi-flight-time">
                                        <div class="arrival-time"><?php echo esc_html($arrivalTime); ?></div>
                                        <div class="arrival-city"><?php echo esc_html(getCityNameByAirPortCode($destination)); ?></div>
                                    </div>
                                </div>
                                    <?php 

                                }else{
                                    ?>
                                <div class="col-md-1 d-flex flex-column justify-content-center time-place-flight">
                                    <div class="place-time-flight-th">
                                        <div class="departure-time"><?php echo esc_html($departureTime); ?></div>
                                        <div class="departure-city"><?php echo esc_html(getCityNameByAirPortCode($origin)); ?></div>
                                    </div>  
                                </div>

                                 <div class="col-md-3 flight-dustination-duration">
                                    <div class="duration"><?php echo esc_html($durationFormatted); ?></div>
                                    <div>--------------------</div>
                    
                                    <div class="flight-type">Non stop</div>
                                </div>

                                <div class="col-md-1 d-flex flex-column justify-content-center">
                                    <div class="delhi-flight-time">
                                        <div class="arrival-time"><?php echo esc_html($arrivalTime); ?></div>
                                        <div class="arrival-city"><?php echo esc_html(getCityNameByAirPortCode($destination)); ?></div>
                                    </div>
                                </div>
                            <?php }//end of if else//?>
                               

                                <div class="col-md-2 d-flex flex-column justify-content-center">
                                    <div class="make-fare-and-fair">
                                        <div class="price"><?php echo esc_html($currency) . ' ' . esc_html($price); ?></div>
                                        <div class="price-type">Total Fare</div>
                                    </div>
                                </div>

                                <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                                <?php
                                    $flightCardId = 'flight-' . uniqid();
                                    $flightDataJson = json_encode($fareItem); // full $fareItem
                                    $encodedFlightData = base64_encode($flightDataJson);
                                    $currentQueryString = $_SERVER['QUERY_STRING']; // original search
                                   $paymentUrl = site_url('/flight-payment/') . "?{$currentQueryString}&session_id=" . urlencode($session_id) . "&flightData=" . urlencode($encodedFlightData);

                                    ?>
                                
                                
                                <a href="<?php echo esc_url($paymentUrl); ?>">
                                    <button class="btn btn-primary book-now-btn mb-2 book-now-button">
                                        <span class="book-now-button-detail">Book Now</span>
                                    </button>
                                </a>
                                    <button class="flight-detail btn btn-link">
                                        Flight Details <i class="arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    } else {
                        echo '<div class="alert alert-warning">No flights found for your search.</div>';
                    } ?>
                </div> <!-- ✅ END of #flights-container -->
                    <button id="show-more-flights" class="btn btn-outline-primary d-block mx-auto mt-4">
                    Show More Flights
                    </button>
            </div> <!-- .col-md-9 -->
        </div>
    </div>
</div>
  
<?php get_footer(); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const adults = parseInt(params.get('adults')) || 0;
    const children = parseInt(params.get('children')) || 0;
    const infants = parseInt(params.get('infants')) || 0;
    const travelClass = params.get('class') || 'Economy';

    const totalTravellers = adults + children + infants;

    if (totalTravellers > 0) {
        const btn = document.getElementById('toggleDropdown');
        btn.innerHTML = `${totalTravellers} Traveller${totalTravellers > 1 ? 's' : ''} | ${travelClass} <i class="fa-solid fa-caret-down"></i>`;
    }
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const flightDetailLinks = document.querySelectorAll('.flight-detail');

    flightDetailLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            this.classList.toggle('active');

            const flightCard = this.closest('.flight-card');
            let detailsSection = flightCard.querySelector('.flight-details-section');

            // Parse flight data from data attribute
            const flightData = JSON.parse(flightCard.dataset.flight || '{}');

            const tabIdSuffix = flightCard.id;

            if (this.classList.contains('active')) {
                if (!detailsSection) {
                    // Build dynamic content
                    const segment = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[0]?.FlightSegment || {};

                    const duration = segment.JourneyDuration;
                    function formatDuration(durationInMinutes) {
                      const hours = Math.floor(durationInMinutes / 60);
                      const minutes = durationInMinutes % 60;
                      return `${hours}hr${hours !== 1 ? 's' : ''} ${minutes}${minutes !== 1 ? 'm' : ''}`;
                    }
                    const formattedDuration = formatDuration(duration);
                    //Getting city code to city name//
                    const fromcode = segment.DepartureAirportLocationCode;
                    const tocode = segment.ArrivalAirportLocationCode;

                    function getCityNameByAirPortCode(code) {
                      const airportCityMap = {
                        JFK: "New York City",
                        KWI :"Kuwait",
                        AMM: "Amman",
                        RUH: "Riyadh",
                        FCO: "Rome",
                        DXB: "Dubai",
                        CAI: "Cairo",
                        HND: "Tokyo",
                        SIN: "Singapore",
                        SYD: "Sydney",
                        FRA: "Frankfurt",
                      };

                      return airportCityMap[code] || `Unknown (${code})`;
                    }
                    const citynamebycode = getCityNameByAirPortCode(fromcode);
                    const citynamebyTocode = getCityNameByAirPortCode(tocode);

                    //Convert DepartureTime to proper formate//
                    const departureDateTime = segment.DepartureDateTime;
                    const date = new Date(departureDateTime);
                    const pad = (n) => n.toString().padStart(2, '0');
                    const formattedDate = `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}`;

                    //Convert ArrivalTime to proper formate//
                    const arrivalDateTime = segment.ArrivalDateTime;
                    const dateArrival = new Date(arrivalDateTime);
                    const padArrival = (n) => n.toString().padStart(2, '0');
                    const formattedDateArrival = `${dateArrival.getFullYear()}-${padArrival(dateArrival.getMonth() + 1)}-${padArrival(dateArrival.getDate())} ${padArrival(dateArrival.getHours())}:${padArrival(dateArrival.getMinutes())}`;
                    
                    const fare = flightData.FareItinerary?.AirItineraryFareInfo?.ItinTotalFares || {};
                    const baggage = flightData.FareItinerary?.AirItineraryFareInfo?.FareBreakdown?.[0]?.Baggage?.[0] || '';
                    const cabinBaggage = flightData.FareItinerary?.AirItineraryFareInfo?.FareBreakdown?.[0]?.CabinBaggage?.[0] || '';
                    const refund = flightData.FareItinerary?.AirItineraryFareInfo?.FareBreakdown?.[0]?.PenaltyDetails?.RefundAllowed ? 'Yes' : 'No';
                    const changeAmount = flightData.FareItinerary?.AirItineraryFareInfo?.FareBreakdown?.[0]?.PenaltyDetails?.ChangePenaltyAmount || 0;

                    // Build the detail section
                    detailsSection = document.createElement('div');
                    detailsSection.className = 'flight-details-section mt-3';

                    detailsSection.innerHTML = `
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#flight-info-${tabIdSuffix}" type="button" role="tab">Flight Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fare-details-${tabIdSuffix}" type="button" role="tab">Fare Details & Rules</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#baggage-info-${tabIdSuffix}" type="button" role="tab">Baggage Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancellation-${tabIdSuffix}" type="button" role="tab">Cancellation</button>
                            </li>
                        </ul>

                        <div class="tab-content p-3 border border-top-0 rounded-bottom">
                            <div class="tab-pane fade show active" id="flight-info-${tabIdSuffix}" role="tabpanel">
                                <p><strong>From:</strong> ${citynamebycode} at ${formattedDate}</p>
                                <p><strong>To:</strong> ${citynamebyTocode} at ${formattedDateArrival}</p>
                                <p><strong>Flight:</strong> ${segment.MarketingAirlineName} ${segment.FlightNumber}</p>
                                <p><strong>Duration:</strong> ${formattedDuration}</p>
                            </div>
                            <div class="tab-pane fade" id="fare-details-${tabIdSuffix}" role="tabpanel">
                                <p><strong>Total Fare:</strong> $${fare.TotalFare?.Amount || 'N/A'} (${fare.TotalFare?.CurrencyCode || 'USD'})</p>
                                <p><strong>Base Fare:</strong> $${fare.BaseFare?.Amount || 'N/A'}</p>
                                <p><strong>Taxes:</strong> $${fare.TotalTax?.Amount || 'N/A'}</p>
                            </div>
                            <div class="tab-pane fade" id="baggage-info-${tabIdSuffix}" role="tabpanel">
                                <p><strong>Checked Baggage:</strong> ${baggage}</p>
                                <p><strong>Cabin Baggage:</strong> ${cabinBaggage}</p>
                            </div>
                            <div class="tab-pane fade" id="cancellation-${tabIdSuffix}" role="tabpanel">
                                <p><strong>Refundable:</strong> ${refund}</p>
                                <p><strong>Change Fee:</strong> $${changeAmount}</p>
                            </div>
                        </div>
                    `;

                    flightCard.appendChild(detailsSection);
                } else {
                    detailsSection.style.display = 'block';
                }

                const arrow = this.querySelector('i');
                if (arrow) arrow.className = 'arrow-up';

            } else {
                if (detailsSection) detailsSection.style.display = 'none';
                const arrow = this.querySelector('i');
                if (arrow) arrow.className = 'arrow-down';
            }
        });
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const sortButtons = document.querySelectorAll('.sort-section .btn-group .btn');
    const flightsContainer = document.getElementById('flights-container');

    if (!flightsContainer) {
        console.warn('Missing #flights-container — sorting will not work.');
        return;
    }

    sortButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all buttons
            sortButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            // Activate clicked button
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-primary');

            const sortBy = this.getAttribute('data-sort');

            const flights = Array.from(flightsContainer.querySelectorAll('.flight-card'));

            flights.sort((a, b) => {
                const valA = parseFloat(a.getAttribute(`data-${sortBy}`)) || 0;
                const valB = parseFloat(b.getAttribute(`data-${sortBy}`)) || 0;
                return valA - valB;
            });

            flights.forEach(flight => flightsContainer.appendChild(flight));
        });
    });
});
</script>
<script>
// document.addEventListener("DOMContentLoaded", function () {
//     const flights = document.querySelectorAll('.flight-card');
//     const showMoreBtn = document.getElementById('show-more-flights');
//     const countDisplay = document.getElementById('visible-flights-count');
//     const batchSize = 10;
//     const totalFlights = flights.length;
//     let visibleCount = 0;

//     function updateFlightCount() {
//         const count = document.querySelectorAll('.flight-card:not([style*="display: none"])').length;
//         if (countDisplay) {
//             countDisplay.textContent = `Showing ${count} of ${totalFlights} flights`;
//         }
//     }

//     function showNextBatch() {
//         const nextCount = visibleCount + batchSize;

//         for (let i = visibleCount; i < nextCount && i < totalFlights; i++) {
//             flights[i].style.display = 'block';
//         }

//         visibleCount = nextCount;

//         updateFlightCount(); // ✅ Update visible/total display

//         if (visibleCount >= totalFlights) {
//             showMoreBtn.style.display = 'none';
//         }
//     }

//     // ✅ Hide all initially
//     flights.forEach(flight => flight.style.display = 'none');

//     // ✅ Show first batch
//     showNextBatch();

//     // ✅ On click, show next batch
//     showMoreBtn.addEventListener('click', showNextBatch);
// });

// travel tab dropdown section 9-04-2024 js start
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggleDropdown");
    const dropdown = document.getElementById("passengerDropdown");
  
    // Toggle dropdown visibility
    toggleBtn.addEventListener("click", function (e) {
        console.log("Toggle clicked"); // Debug
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });
  
    // Hide on outside click
    document.addEventListener("click", function (e) {
        if (!dropdown.contains(e.target) && e.target !== toggleBtn) {
            dropdown.style.display = "none";
        }
    });
      
    // Travel class toggle
    document.querySelectorAll('.btn-class').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.btn-class').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
     });
      
    // Count update
    window.updateCount = function (type, change) {
        const countEl = document.getElementById(type + 'Count');
        let current = parseInt(countEl.textContent);
        let newCount = current + change;
  
        if (type === 'adults' && newCount < 1) newCount = 1;
        else if (newCount < 0) newCount = 0;
  
        countEl.textContent = newCount;
    }
      
      // Apply
      window.applySelection = function () {
          const adults = parseInt(document.getElementById("adultsCount").textContent);
          const children = parseInt(document.getElementById("childrenCount").textContent);
          const infants = parseInt(document.getElementById("infantsCount").textContent);
          const total = adults + children + infants;
          const travelClass = document.querySelector(".btn-class.active")?.textContent || "Not Selected";
  
          toggleBtn.innerHTML = `
              <div style="flex-direction:column; width:100%;">
                  <span style="font-size:14px; margin-bottom:3px; margin-left:auto;">${total} Traveller${total > 1 ? 's' : ''}</span>
                  <span style="font-size:14px; margin-left:auto;">${travelClass}</span>
              </div>
          `;
  
          dropdown.style.display = "none";
      }
});
// end javascript travel dropdown

// start clock in timer javscript
document.addEventListener("DOMContentLoaded", function () {
      const checkinInput = document.getElementById("checkinDate");
      const checkoutInput = document.getElementById("checkoutDate");
      const returnGroup = document.getElementById("return-group");

      let isRoundTrip = document.getElementById("round-trip").checked;

      let datePicker = flatpickr(checkinInput, {
        mode: isRoundTrip ? "range" : "single",
        dateFormat: "Y-m-d",
        minDate: "today",
        showMonths: 2,
        onClose: function (selectedDates) {
          if (this.config.mode === "range" && selectedDates.length === 2) {
            checkinInput.value = this.formatDate(selectedDates[0], "Y-m-d");
            checkoutInput.value = this.formatDate(selectedDates[1], "Y-m-d");
          } else if (this.config.mode === "single" && selectedDates.length === 1) {
            checkinInput.value = this.formatDate(selectedDates[0], "Y-m-d");
            checkoutInput.value = "";
          }
        }
      });

      checkoutInput.addEventListener("focus", function (e) {
        if (!isRoundTrip) {
          e.preventDefault();
          return;
        }
        checkinInput.focus();
        datePicker.open();
      });

      document.getElementById("one-way").addEventListener("change", function () {
        isRoundTrip = false;
        datePicker.clear();
        datePicker.set("mode", "single");
        checkoutInput.value = "";
        checkoutInput.setAttribute("readonly", true);
        checkoutInput.classList.add("disabled-return");
        returnGroup.classList.add("disabled");
      });

      document.getElementById("round-trip").addEventListener("change", function () {
        isRoundTrip = true;
        datePicker.clear();
        datePicker.set("mode", "range");
        checkoutInput.removeAttribute("readonly");
        checkoutInput.classList.remove("disabled-return");
        returnGroup.classList.remove("disabled");
      });
    });
// end clock in clock out javscript
</script>


<!-- Add this div after the flight container -->
<div id="no-flights-message" class="alert alert-info">
    No flights match your filter criteria.
</div>

<script>
// Global variables for filter and pagination
let allFlights = [];
let filteredFlights = [];
const batchSize = 10;
let visibleCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all flights
    allFlights = Array.from(document.querySelectorAll('.flight-card'));
    filteredFlights = [...allFlights];
    
    // Set up filter functionality
    setupFilters();
    
    // Add event listener for the "Clear All" button
    document.getElementById('clear-all-filters').addEventListener('click', function(e) {
        e.preventDefault();
        resetFilters();
    });
    
    // Set up show more button
    const showMoreBtn = document.getElementById('show-more-flights');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', showNextBatch);
    }
    
    // Initialize flights display
    initializeFlightsDisplay();
});

function initializeFlightsDisplay() {
    // Hide all flights initially
    allFlights.forEach(flight => {
        flight.style.display = 'none';
    });
    
    // Reset visible count
    visibleCount = 0;
    
    // Show first batch
    showNextBatch();
}

function showNextBatch() {
    const nextCount = visibleCount + batchSize;
    
    // Show next batch of filtered flights
    for (let i = visibleCount; i < nextCount && i < filteredFlights.length; i++) {
        filteredFlights[i].style.display = 'block';
    }
    
    visibleCount = nextCount;
    
    // Update show more button visibility
    updateShowMoreButton();
}

function updateShowMoreButton() {
    const showMoreBtn = document.getElementById('show-more-flights');
    if (showMoreBtn) {
        // Show button only if there are more filtered flights to display
        showMoreBtn.style.display = visibleCount < filteredFlights.length ? 'block' : 'none';
    }
    
    // Update count display if it exists
    const countDisplay = document.getElementById('visible-flights-count');
    if (countDisplay) {
        countDisplay.textContent = `Showing ${Math.min(visibleCount, filteredFlights.length)} of ${filteredFlights.length} flights`;
    }
}

function setupFilters() {
    if (allFlights.length === 0) {
        console.log('No flight cards found');
        return;
    }
    
    // Extract price data and airline codes
    let minPrice = Infinity;
    let maxPrice = 0;
    const airlines = new Map(); // Map to store airline code -> name pairs
    
    allFlights.forEach(card => {
        // Get price from data attribute
        const price = parseFloat(card.dataset.price || 0);
        if (price > 0) {
            minPrice = Math.min(minPrice, price);
            maxPrice = Math.max(maxPrice, price);
        }
        
        // Extract flight data
        try {
            const flightData = JSON.parse(card.dataset.flight || '{}');
            if (flightData && flightData.FareItinerary) {
                // Get ValidatingAirlineCode
                const validatingCode = flightData.FareItinerary.ValidatingAirlineCode;
                
                if (validatingCode) {
                    // If we don't have a name for this airline code yet, use the code as name
                    if (!airlines.has(validatingCode)) {
                        airlines.set(validatingCode, validatingCode);
                    }
                }
                
                // Get OperatingAirline Code from segments if it exists
                if (flightData.FareItinerary.OriginDestinationOptions && 
                    flightData.FareItinerary.OriginDestinationOptions[0] &&
                    flightData.FareItinerary.OriginDestinationOptions[0].OriginDestinationOption) {
                    
                    const options = flightData.FareItinerary.OriginDestinationOptions[0].OriginDestinationOption;
                    
                    for (const option of options) {
                        if (option.FlightSegment && option.FlightSegment.OperatingAirline) {
                            const opCode = option.FlightSegment.OperatingAirline.Code;
                            const opName = option.FlightSegment.OperatingAirline.Name;
                            
                            if (opCode) {
                                // Store airline code with name if available
                                airlines.set(opCode, opName || opCode);
                            }
                        }
                    }
                }
            }
        } catch (e) {
            console.error('Error parsing flight data', e);
        }
    });
    
    // Handle edge case if no prices found
    if (minPrice === Infinity) minPrice = 0;
    if (maxPrice === 0) maxPrice = 0;
    
    // Set up price range filter
    setupPriceRangeFilter(minPrice, maxPrice);
    
    // Set up airline filters
    setupAirlineFilters(airlines);
}

function setupPriceRangeFilter(minPrice, maxPrice) {
    // Round values to make them more user-friendly
    minPrice = Math.floor(minPrice);
    maxPrice = Math.ceil(maxPrice);
    
    const minSlider = document.getElementById('price-slider-min');
    const maxSlider = document.getElementById('price-slider-max');
    const minDisplay = document.getElementById('price-min-display');
    const maxDisplay = document.getElementById('price-max-display');
    
    if (!minSlider || !maxSlider || !minDisplay || !maxDisplay) {
        console.error('Price range slider elements not found');
        return;
    }
    
    // Set slider attributes
    minSlider.min = minPrice;
    minSlider.max = maxPrice;
    minSlider.value = minPrice;
    
    maxSlider.min = minPrice;
    maxSlider.max = maxPrice;
    maxSlider.value = maxPrice;
    
    // Display initial values
    minDisplay.textContent = '$' + minPrice;
    maxDisplay.textContent = '$' + maxPrice;
    
    // Add event listeners for sliders
    minSlider.addEventListener('input', function() {
        if (parseInt(minSlider.value) > parseInt(maxSlider.value)) {
            minSlider.value = maxSlider.value;
        }
        minDisplay.textContent = '$' + minSlider.value;
        applyFilters();
    });
    
    maxSlider.addEventListener('input', function() {
        if (parseInt(maxSlider.value) < parseInt(minSlider.value)) {
            maxSlider.value = minSlider.value;
        }
        maxDisplay.textContent = '$' + maxSlider.value;
        applyFilters();
    });
}

function setupAirlineFilters(airlines) {
    const airlinesContainer = document.getElementById('airlines-filters');
    if (!airlinesContainer) {
        console.error('Airlines filter container not found');
        return;
    }
    
    airlinesContainer.innerHTML = ''; // Clear existing content
    
    // Create checkbox for each airline
    airlines.forEach((airlineName, airlineCode) => {
        const checkboxDiv = document.createElement('div');
        checkboxDiv.className = 'airline-checkbox';
        
        const checkboxId = 'airline-' + airlineCode;
        
        checkboxDiv.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="${checkboxId}" value="${airlineCode}" checked>
                <label class="form-check-label" for="${checkboxId}">
                    ${airlineName} (${airlineCode})
                </label>
            </div>
        `;
        
        airlinesContainer.appendChild(checkboxDiv);
        
        // Add event listener to checkbox
        document.getElementById(checkboxId).addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const minPrice = parseInt(document.getElementById('price-slider-min').value);
    const maxPrice = parseInt(document.getElementById('price-slider-max').value);
    
    // Get selected airlines
    const selectedAirlines = [];
    document.querySelectorAll('#airlines-filters input[type="checkbox"]:checked').forEach(checkbox => {
        selectedAirlines.push(checkbox.value);
    });
    
    // Filter all flights
    filteredFlights = allFlights.filter(card => {
        let matchesFilter = true;
        
        // Check price
        const price = parseFloat(card.dataset.price || 0);
        if (price < minPrice || price > maxPrice) {
            matchesFilter = false;
        }
        
        // Check airline
        if (matchesFilter && selectedAirlines.length > 0) {
            let matchesAirline = false;
            
            try {
                const flightData = JSON.parse(card.dataset.flight || '{}');
                if (flightData && flightData.FareItinerary) {
                    // Check ValidatingAirlineCode
                    const validatingCode = flightData.FareItinerary.ValidatingAirlineCode;
                    if (validatingCode && selectedAirlines.includes(validatingCode)) {
                        matchesAirline = true;
                    }
                    
                    // Check OperatingAirline Code
                    if (!matchesAirline && 
                        flightData.FareItinerary.OriginDestinationOptions && 
                        flightData.FareItinerary.OriginDestinationOptions[0] &&
                        flightData.FareItinerary.OriginDestinationOptions[0].OriginDestinationOption) {
                        
                        const options = flightData.FareItinerary.OriginDestinationOptions[0].OriginDestinationOption;
                        
                        for (const option of options) {
                            if (option.FlightSegment && option.FlightSegment.OperatingAirline) {
                                const opCode = option.FlightSegment.OperatingAirline.Code;
                                if (opCode && selectedAirlines.includes(opCode)) {
                                    matchesAirline = true;
                                    break;
                                }
                            }
                        }
                    }
                }
                
                if (!matchesAirline) {
                    matchesFilter = false;
                }
            } catch (e) {
                console.error('Error checking airline filter', e);
            }
        }
        
        return matchesFilter;
    });
    
    // Hide all flights
    allFlights.forEach(flight => {
        flight.style.display = 'none';
    });
    
    // Reset visible count
    visibleCount = 0;
    
    // Show first batch of filtered flights
    showNextBatch();
    
    // Show/hide "no flights" message
    const noFlightsMessage = document.getElementById('no-flights-message');
    if (noFlightsMessage) {
        noFlightsMessage.style.display = filteredFlights.length === 0 ? 'block' : 'none';
    }
}

function resetFilters() {
    // Reset price range sliders
    const minSlider = document.getElementById('price-slider-min');
    const maxSlider = document.getElementById('price-slider-max');
    const minDisplay = document.getElementById('price-min-display');
    const maxDisplay = document.getElementById('price-max-display');
    
    if (minSlider && maxSlider && minDisplay && maxDisplay) {
        minSlider.value = minSlider.min;
        maxSlider.value = maxSlider.max;
        minDisplay.textContent = '$' + minSlider.min;
        maxDisplay.textContent = '$' + maxSlider.max;
    }
    
    // Check all airline checkboxes
    document.querySelectorAll('#airlines-filters input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = true;
    });
    
    // Reset filtered flights to all flights
    filteredFlights = [...allFlights];
    
    // Hide all flights
    allFlights.forEach(flight => {
        flight.style.display = 'none';
    });
    
    // Reset visible count
    visibleCount = 0;
    
    // Show first batch
    showNextBatch();
    
    // Hide "no flights" message
    const noFlightsMessage = document.getElementById('no-flights-message');
    if (noFlightsMessage) {
        noFlightsMessage.style.display = 'none';
    }
}
</script>
<script>
    const minSlider = document.getElementById('price-slider-min');
    const maxSlider = document.getElementById('price-slider-max');
    const fill = document.getElementById('slider-fill');

    function updateFill() {
      const min = parseInt(minSlider.value);
      const max = parseInt(maxSlider.value);

      const range = parseInt(minSlider.max) - parseInt(minSlider.min);
      const left = ((min - minSlider.min) / range) * 100;
      const width = ((max - min) / range) * 100;

      fill.style.left = left + '%';
      fill.style.width = width + '%';
    }

    minSlider.addEventListener('input', () => {
      if (parseInt(minSlider.value) > parseInt(maxSlider.value)) {
        minSlider.value = maxSlider.value;
      }
      updateFill();
    });

    maxSlider.addEventListener('input', () => {
      if (parseInt(maxSlider.value) < parseInt(minSlider.value)) {
        maxSlider.value = minSlider.value;
      }
      updateFill();
    });

    window.addEventListener('load', () => {
      minSlider.value = minSlider.min;
      maxSlider.value = maxSlider.max;
      updateFill();
    });
  </script>

 <style>
     /* Basic Styling */
span.fliStopsDisc {
    width: 8px;
    position: absolute ! IMPORTANT;
    height: 8px;
    border: 2px solid #e7e7e7;
    display: inline-block;
    background-color: #959595;
    position: relative;
    z-index: 2;
    top: -4px ! IMPORTANT;
    margin: 0 2px;
    border-radius: 20px;
}
.fliStopsSepLine {
    border-top: 3px solid #979797;
    width: 50px;
    height: 2px;
    position: absolute;
    left: -25px;
    right: 21px;
    margin: auto;
    top: 0px;
    bottom: 0;
    z-index: 1;
}
.tooltip-container {
    position: relative;
    display: inline-block;
}

/* Tooltip Button */
.tooltip {
       position: absolute;
    font-size: 16px;
    cursor: pointer;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

/* Tooltip Text Styling */
.tooltip-text {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    bottom: 125%; /* Position above the button */
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 5px;
    padding: 8px;
    width: 120px;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    font-size: 14px;
}

/* Tooltip Text Arrow */
.tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}

/* Show Tooltip on Hover */
.tooltip-container:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

/* Hover Effect for Button */
.tooltip:hover {
    background-color: #2980b9;
}

 </style>
