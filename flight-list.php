
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
                        returnDate: tripType === 'Return' ? returnDate : '',
                        class: travelClass,
                        adults,
                        children,
                        infants
                    });

                    // Get the home URL of the site (to construct the full URL for flight results)
                    const homeUrl = "<?php echo esc_url(site_url()); ?>";
                    // Show loader
                    const loader = document.querySelector('.loader');
                    if (loader) loader.style.display = 'flex';

                    // Redirect after a slight delay to ensure loader is visible
                    setTimeout(() => {
                        window.location.href = `${homeUrl}/flight-list/?${queryParams.toString()}`;
                    }, 100); // delay in ms
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
                <div class="filters-container flight-listing-section ">
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

                                 <div class="multi-range-slider mb-2">
                            <div class="slider-track"></div>
                            <div class="slider-fill" id="slider-fill"></div>
                            <input type="range" id="price-slider-min" min="0" max="100" step="1" value="20">
                            <input type="range" id="price-slider-max" min="0" max="100" step="1" value="80">
                            </div>
                                                        </div>
                                                        
                                                            
                                                        </style>
                        <!-- </div> -->

                             <!--  <div class="multi-range-slider mb-2">
                                    <input type="range" id="price-slider-min" class="noUi-connect" min="0" max="0" step="1" value="0">
                                    <input type="range" id="price-slider-max" class="" min="0" max="0" step="1" value="0">
                                </div>

                            </div> -->
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
                    <div class="loader" style="display: none;"></div>
                    <?php
                        $fareList = $flights['AirSearchResponse']['AirSearchResult']['FareItineraries'] ?? [];
                        if (!empty($fareList)) {
                            foreach ($fareList as $index => $fareItem):
                            
$fare = $fareItem['FareItinerary'];

$fareSourceCode= $fare['AirItineraryFareInfo']['FareSourceCode'];
$fareBreakdown = current($fare['AirItineraryFareInfo']['FareBreakdown']);
$penaltyDetails = $fareBreakdown['PenaltyDetails'];
$totalsegments = count($fare['OriginDestinationOptions'][0]['OriginDestinationOption']);
$returnfare = $fareItem['FareItinerary']['DirectionInd'];

$counttotalstop = $fare['OriginDestinationOptions'][0]['TotalStops'];

//For single trip type//
if($counttotalstop==1){

    for($num=0;$num<$counttotalstop;$num++){
        
        $flightarrival = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]
        ['FlightSegment']['ArrivalDateTime'];
        $flightdepart = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num +1]['FlightSegment']['DepartureDateTime'];
        $arrivaloccode  = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]['FlightSegment']['ArrivalAirportLocationCode'];

        $firstDepartureDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]['FlightSegment']['DepartureDateTime']));
        $firstArrivalDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num+1]['FlightSegment']['ArrivalDateTime']));

        $firstDepartutreCode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]['FlightSegment']['DepartureAirportLocationCode'];

        $start = new DateTime($flightarrival);
        $end = new DateTime($flightdepart);

        // Calculate difference
        $interval = $start->diff($end);

        // Format output
        $layovertime = $interval->format('%h hours %i minutes');
        $arrinalcode = getCityNameByAirPortCode($arrivaloccode);

        $firstDepartureAirportLocationCode = getCityNameByAirPortCode($firstDepartutreCode);

        //Calculating total time//

        $segment_one = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]['FlightSegment']['JourneyDuration'];
        $segment_two = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num+1]['FlightSegment']['JourneyDuration'];
        
        //Calculating total time//
        preg_match('/(\d+)\s*hours?\s*(\d+)\s*minutes?/', $layovertime, $matches);
        $hours = (int)$matches[1];
        $minutes = (int)$matches[2];
        $totalMinutes = ($hours * 60) + $minutes;
        $totalTimeTaken = $segment_one + $segment_two + $totalMinutes;

        // Convert back to hours and minutes
        $totalHours = floor($totalTimeTaken / 60);
        $totalMinut = $totalTimeTaken % 60;
        $totalTime = "{$totalHours}h {$totalMinut}m";
    }
    
}

//For return trip type//
if($counttotalstop==2){

    $flightcnt = 0;
    for($num=0;$num<$counttotalstop;$num++){
        
        if($flightcnt==$num){

            $firstDepartureDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt]['FlightSegment']['DepartureDateTime']));
            $firstArrivalDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+2]['FlightSegment']['ArrivalDateTime']));

            //for first layover time//
            $flightarrival = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt]['FlightSegment']['ArrivalDateTime'];
            $flightdepart = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+1]['FlightSegment']['DepartureDateTime'];

            //for country code//
            $roundflightarrivalcode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt]['FlightSegment']['ArrivalAirportLocationCode'];
            $roundflightdepartcode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+1]['FlightSegment']['ArrivalAirportLocationCode'];

            $start = new DateTime($flightarrival);
            $end = new DateTime($flightdepart);

            // Calculate difference
            $interval = $start->diff($end);

            // Format output
            $layovertime = $interval->format('%h hours %i minutes');

            $firstDepartutreCode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt]['FlightSegment']['DepartureAirportLocationCode'];

            //for second layover time//
             $secondflightarrival = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+1]['FlightSegment']['ArrivalDateTime'];
             $secondepart = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+2]['FlightSegment']['DepartureDateTime'];

             $start = new DateTime($secondflightarrival);
             $end = new DateTime($secondepart);
            $interval = $start->diff($end);
            $seclayovertime = $interval->format('%h hours %i minutes');

            //Calculating total time//
            $segment_one = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt]['FlightSegment']['JourneyDuration'];
            $segment_two = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+1]['FlightSegment']['JourneyDuration'];
            $segment_three = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$flightcnt+2]['FlightSegment']['JourneyDuration'];
            
            //Calculating total time//
            preg_match('/(\d+)\s*hours?\s*(\d+)\s*minutes?/', $layovertime, $matches);
            $hours = (int)$matches[1];
            $minutes = (int)$matches[2];
            $totalfirstMinutes = ($hours * 60) + $minutes;

            preg_match('/(\d+)\s*hours?\s*(\d+)\s*minutes?/', $seclayovertime, $matches);
            $hours = (int)$matches[1];
            $minutes = (int)$matches[2];
            $totalsecMinutes = ($hours * 60) + $minutes;

            $totalTimeTaken = ($segment_one + $totalfirstMinutes + $segment_two + 
                               $totalsecMinutes + $segment_three);

            // Convert back to hours and minutes
            $totalHours = floor($totalTimeTaken / 60);
            $totalMinut = $totalTimeTaken % 60;
            $totalTime = "{$totalHours}h {$totalMinut}m";
        }

        

        $arrivaloccode  = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]['FlightSegment']['ArrivalAirportLocationCode'];
    

        
        $arrinalcode = getCityNameByAirPortCode($arrivaloccode);

        $firstDepartureAirportLocationCode = getCityNameByAirPortCode($firstDepartutreCode);

        //Calculating total time//

        $segment_one = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num]['FlightSegment']['JourneyDuration'];
        $segment_two = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$num+1]['FlightSegment']['JourneyDuration'];

    }
    
}

                                //This is for non stop//
                                if($counttotalstop==0){

                                    
                                    $firstDepartureDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][0]['FlightSegment']['DepartureDateTime']));
                                }

                                for($i=0;$i<$totalsegments;$i++){

                                //calculating total journey time
                                $segment = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment'];


                                //Getting second departure status//
                                $secondArrivalDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['ArrivalDateTime']));
                                $secondDepartureDateTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i+1]['FlightSegment']['FlightSegment']['DepartureDateTime'])); 


                                //calculating arrival and departure status//
                                $origin = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['DepartureAirportLocationCode'] ?? 'XXX';
                                $destination = $$fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['ArrivalAirportLocationCode'] ?? 'YYY';
                                $departureTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['DepartureDateTime']));
                                $arrivalTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['ArrivalDateTime']));

                                if($i==1){

                                    $arrivalTime = date("H:i", strtotime($fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['ArrivalDateTime']));
                                    $secondArrivalAirportLocationByCode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['ArrivalAirportLocationCode'];
                                }

                                //if trip type is return then//
                                if($returnfare=='Return'){

                                    $secondArrivalAirportLocationByCode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$i]['FlightSegment']['ArrivalAirportLocationCode'];
                                }

                                //Difference between departure and arrival time//
                                $start = new DateTime($firstDepartureDateTime);
                                $end = new DateTime($arrivalTime);

                                $interval = $start->diff($end);
                                }//End of inner for loop//
                                                            

                                //calculate time for return trip flights//     
                                    $layoverTimes = [];
                                    $returnflightsegments = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'];                                  

                                    for ($j = 0; $j < count($returnflightsegments); $j++) {

                                        //this is for times//
                                        $arrival = new DateTime($returnflightsegments[$j]['FlightSegment']['ArrivalDateTime']);
                                        $nextDeparture = new DateTime($returnflightsegments[$j + 1]['FlightSegment']['DepartureDateTime']);
                                        $diff = $arrival->diff($nextDeparture);
                                        $layoverTimes = $diff->format('%h hours %i minutes');

                                        //this is for code//
                                        $secondArrivalAirportLocationCode = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'][$j]['FlightSegment']['ArrivalAirportLocationCode'];

                                        $dataarray[] = array('layouttime'=>$layoverTimes,
                                                            'secondArrivalAirportLocationCode'=>$secondArrivalAirportLocationCode);
                                        }         
                               

                                //have to do in dynamic way//
                                $originOptions = $fare['OriginDestinationOptions'][0]['OriginDestinationOption'];
                                $totalStops = $fare['OriginDestinationOptions'][0]['TotalStops'];

                                $airlineName = '';
                                $flightCodes = [];

                                foreach ($originOptions as $option) {
                                    $segment = $option['FlightSegment'];
                                    if (empty($airlineName) && !empty($segment['MarketingAirlineName'])) {
                                        $airlineName = $segment['MarketingAirlineName'];
                                    }

                                    $airlineCode = $segment['MarketingAirlineCode'] ?? 'XX';
                                    $flightNumber = $segment['FlightNumber'] ?? '000';

                                    $flightCodes[] = $airlineCode . ' ' . $flightNumber;
                                }

                                $flightNumberString = implode(', ', $flightCodes);

                                $durationMin = (int)($segment['JourneyDuration'] ?? 0);
                                $durationHours = floor($durationMin / 60);
                                $durationMins = $durationMin % 60;
                                $durationFormatted = sprintf("%02dh %02dm", $durationHours, $durationMins);

                                $price = $fare['AirItineraryFareInfo']['ItinTotalFares']['TotalFare']['Amount'] ?? '0';
                                $currency = $fare['AirItineraryFareInfo']['ItinTotalFares']['TotalFare']['CurrencyCode'] ?? 'USD';

                                $airline_code = $fare['ValidatingAirlineCode'];
                                //$airline_code = $fare['ValidatingAirlineCode'];

                                $airline_codea =$segment['OperatingAirline']['Code'];
                                $logo_url = 'https://travelnext.works/api/airlines/'.$airline_codea.'.gif';//get_airline_logo_url($airline_code);

                                // Unique ID and flight data JSON
                                $flightCardId = 'flight-' . uniqid();
                                $flightDataJson = htmlspecialchars(json_encode(['FareItinerary' => $fare]), ENT_QUOTES, 'UTF-8');                                
                              
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
            <div class="flight-number"><?php echo esc_html($fareItem['FareItinerary']['AirItineraryFareInfo']['FareType']); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if($totalStops){?>
                                    <div class="col-md-1 d-flex flex-column justify-content-center time-place-flight">
    <div class="place-time-flight-thsss">
        <div class="departure-time"><?php echo esc_html($firstDepartureDateTime); ?></div>
        <div class="departure-city"><?php 
        echo esc_html($firstDepartureAirportLocationCode); ?></div>
    </div>  
                                </div>
                                <div class="col-md-3 flight-dustination-duration">
                                    <div class="duration mb-1">
<?php 
if($counttotalstop==1){echo esc_html($totalTime);}else{echo esc_html($totalTime);}?>
                                    </div>
                                     <div class="tooltip-container">
                                 <div class="relative fliStopsSep"><p class="fliStopsSepLine" style="border-top: 3px solid rgb(13 110 253);"></p><span class="fliStopsDisc"></span></div>
                                    <button class="tooltip">------------</button>

                            <span class="tooltip-text">  
<?php 
//For getting time layover//
{ ?>
       
    <?php 
    
    if($counttotalstop==1){
        echo '<span>Plane change</span><br>';
        echo $layovertime . ' (' . getCityNameByAirPortCode($arrivaloccode) . ')';
    }else{
        
        echo "<span>Plane change</span><br>".$layovertime . ' (' . getCityNameByAirPortCode($roundflightarrivalcode) . ')'."</br>".'--------------'."</br>";
        echo "<span>Plane change</span><br>".$seclayovertime . ' (' . getCityNameByAirPortCode($roundflightdepartcode) . ')';
        
    }

    }?> Layover
                           </span>

                                </div>
<div class="flight-type mt-2" ><?php echo $totalStops;?> stop via <?php echo esc_html(getCityNameByAirPortCode($arrivaloccode)); ?>
                                     </div>
                                   
                                </div>

                                <div class="col-md-1 d-flex flex-column justify-content-center">
                                    <div class="delhi-flight-timesss">
                                        <div class="arrival-time"><?php echo esc_html($arrivalTime); ?></div>
    <div class="arrival-city"><?php echo esc_html(getCityNameByAirPortCode($secondArrivalAirportLocationByCode)); ?></div>
                                    </div>
                                </div>
                                    <?php 

                                }else{
                                    ?>
                                <div class="col-md-1 d-flex flex-column justify-content-center time-place-flight">
                                    <div class="place-time-flight-thsss">
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
                                    <div class="delhi-flight-timesss">
    <div class="arrival-time"><?php echo esc_html($arrivalTime); ?></div>
                                        <div class="arrival-city"><?php echo esc_html(getCityNameByAirPortCode($destination)); ?></div>
                                    </div>
                                </div>
                            <?php }?>
                               

                                <div class="col-md-2 d-flex flex-column justify-content-center">
                                    <div class="make-fare-and-fair">
                                        <div class="price"><?php echo esc_html($currency) . ' ' . esc_html($price); ?></div>
                                        <div class="price-type">Total Fare</div>
                                    </div>
                                </div>

                                <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                            <?php
                                $flightCardId = 'flight-' . uniqid();
                    
                                $currentQueryString = $_SERVER['QUERY_STRING']; // original search

                                $paymentUrl = site_url('/flight-payment/') . "?{$currentQueryString}&session_id=" . urlencode($session_id) . "&fareSourceCode=" . urlencode($fareSourceCode) . "&refund_penalty_amount=" . urlencode($penaltyDetails['RefundPenaltyAmount']) . "&change_allowed=" . urlencode($penaltyDetails['ChangeAllowed']) . "&change_penalty_amount=" . urlencode($penaltyDetails['ChangePenaltyAmount']);

                                ?>
                                
                                
                              <!--   <a href="<?php //echo esc_url($paymentUrl); ?>">
                                    <button class="btn btn-primary book-now-btn mb-2 book-now-button">
                                        <span class="book-now-button-detail">Book Now</span>
                                    </button>
                                </a> -->
                                <button class="btn btn-primary book-now-btn mb-2 book-now-button" 
                                        data-url="<?php echo esc_url($paymentUrl); ?>">
                                    <span class="book-now-button-detail">Book Now</span>
                                </button>
                                    <button class="flight-detail btn btn-link">
                                        Flight Details <i class="arrow-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    } else {

                         if (isset($flights['Errors']['ErrorCode']) && ($flights['Errors']['ErrorCode'] == 'FLSEARCHVAL')) {

                        echo '<div class="alert alert-warning" id="message"><center> Search airport to find Flight.</center></div>';
                        }elseif(isset($flights['Errors']['ErrorCode']) && ($flights['Errors']['ErrorCode'] == 'FLERSEA022')){

                            echo '<div class="alert alert-warning" id="message"><center> No Result Found.</center></div>';
                        }elseif(isset($flights['Errors']['ErrorCode'])){

                          echo "<div class='alert alert-warning text-center' role='alert'>{$flights['Errors']['ErrorMessage']}</div>";
                        }else{
                             echo '<div class="alert alert-warning" id="message"><center> Sever taking too much time for response.</center></div>';
                        }
                    } ?>
                </div> <!-- ✅ END of #flights-container -->
                    <button id="show-more-flights" class="btn btn-outline-primary mx-auto mt-4">
                    Show More Flights
                    </button>
            </div> <!-- .col-md-9 -->
        </div>
    </div>
</div>


<?php
        global $wpdb;
       $results = $wpdb->get_results("SELECT airport_code, city FROM airport_list",ARRAY_A);
?>  
<?php get_footer(); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
     document.getElementById("departure_airport_display").addEventListener("click", function () {
          this.select();});

     document.getElementById("flight_location_display").addEventListener("click", function () {
          this.select();
     });
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

                    const getreturntype = flightData.FareItinerary.DirectionInd;

                    const totalstopsegment = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption;

                    //To get return flight detail//
                    const returntotalstopsegment = flightData.FareItinerary?.OriginDestinationOptions?.[1]?.OriginDestinationOption;

                    // Declare these variables in the outer scope
                    let fromcode;
                    let tocode;
                    let citynamebycode;
                    let citynamebyTocode;
                    let arrivaldatetime;

                    // PHP-injected country data
                    const countries = <?php echo json_encode($results); ?>;

                    // Define the function once
                    function getCityNameByAirPortCode(code) {
                        const airportMap = {};
                        countries.forEach(function (airport) {
                            if (airport.airport_code && airport.city) {
                                airportMap[airport.airport_code.toUpperCase()] = airport.city;
                            }
                        });

                        return airportMap[code?.toUpperCase()] || `Unknown (${code})`;
                    }


                    //Get flight data for only one way trip//
                    if (getreturntype == 'OneWay') {
                        for (let i = 0; i < totalstopsegment.length; i++) {
                            if (i == 0) {

                                tocode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i+1]?.FlightSegment.ArrivalAirportLocationCode;

                                citynamebyTocode = getCityNameByAirPortCode(tocode);
                                console.log(citynamebyTocode);
                            }
                        }
                    }

                    if (getreturntype == 'Return') {
                        
                        //for departure flight//
                        for (let i = 0; i < totalstopsegment.length; i++) {
                            if (i == 0) {

                                fromcode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i]?.FlightSegment.DepartureAirportLocationCode;
                                tocode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i + 2]?.FlightSegment.ArrivalAirportLocationCode;

                                citynamebyTocode = getCityNameByAirPortCode(tocode);

                                //this is for one stop//
                                totalstop = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.TotalStops;
                                if(totalstop==1){

                                    tocode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i + 1]?.FlightSegment.ArrivalAirportLocationCode;

                                    citynamebyTocode = getCityNameByAirPortCode(tocode);

                                }

                                //for non stop//
                                if(totalstop==0){

                                    tocode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i]?.FlightSegment.ArrivalAirportLocationCode;

                                    citynamebyTocode = getCityNameByAirPortCode(tocode);
                                }

                                
                                //for two stop//
                                towaycode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i + 2]?.FlightSegment.ArrivalAirportLocationCode;

                                arrivaldatetime  = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i + 2]?.FlightSegment.ArrivalDateTime;

                                citynamebycode = getCityNameByAirPortCode(fromcode);
                            }
                        }
                        //for arrival flight//
                        for (let j = 0; j < returntotalstopsegment.length; j++) {
                            if (j == 0) {

                                returndepartdatetime  = flightData.FareItinerary?.OriginDestinationOptions?.[1]?.OriginDestinationOption?.[j]?.FlightSegment.DepartureDateTime;

                                returnarrivaldatetime  = flightData.FareItinerary?.OriginDestinationOptions?.[1]?.OriginDestinationOption?.[j + 2]?.FlightSegment.ArrivalDateTime;
                            }
                        }
                    }else{

                        //for departure flight//
                        for (let i = 0; i < totalstopsegment.length; i++) {
                            if (i == 0) {

                                fromcode = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[i]?.FlightSegment.DepartureAirportLocationCode;
                                citynamebycode = getCityNameByAirPortCode(fromcode);
                            }
                        }
                    }

                    const duration = segment.JourneyDuration;

                    function formatDuration(durationInMinutes) {
                      const hours = Math.floor(durationInMinutes / 60);
                      const minutes = durationInMinutes % 60;
                      return `${hours}hr${hours !== 1 ? 's' : ''} ${minutes}${minutes !== 1 ? 'm' : ''}`;
                    }
                    const formattedDuration = formatDuration(duration);

                    //Convert DepartureTime to proper formate//
                    const departureDateTime = segment.DepartureDateTime;
                    const date = new Date(departureDateTime);
                    const pad = (n) => n.toString().padStart(2, '0');
                    const formattedDate = `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}`;

                    //Convert ArrivalTime to proper formate//
                    const segmentone = flightData.FareItinerary?.OriginDestinationOptions?.[0]?.OriginDestinationOption?.[1]?.FlightSegment || {};
                    const arrivalDateTime = segmentone.ArrivalDateTime;
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
                         ${getreturntype === 'Return' ? `
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#return-trip-details-${tabIdSuffix}" type="button" role="tab">Return Trip Details</button>
        </li>
        ` : ''}
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
                                <p><strong>From:</strong> ${citynamebycode}(${(fromcode)}) at ${formattedDate}</p>
<p><strong>To:</strong> ${citynamebyTocode}(${(tocode)}) at ${formattedDateArrival}</p>
                                <p><strong>Flight:</strong> ${segment.MarketingAirlineName} ${segment.FlightNumber}</p>
                                <p><strong>Duration:</strong> ${formattedDuration}</p>
                            </div>
        ${getreturntype === 'Return' ? `
        <div class="tab-pane fade" id="return-trip-details-${tabIdSuffix}" role="tabpanel">
             <p><strong>From:</strong> ${citynamebyTocode}(${(tocode)}) at ${returndepartdatetime}</p>
            <p><strong>To:</strong> ${citynamebycode}(${(fromcode)}) at ${returnarrivaldatetime}</p>
                                <p><strong>Flight:</strong> ${segment.MarketingAirlineName} ${segment.FlightNumber}</p>
                                <p><strong>Duration:</strong> ${formattedDuration}</p>
        </div>
        ` : ''}
                            <div class="tab-pane fade" id="fare-details-${tabIdSuffix}" role="tabpanel">
        <p><strong>Base Fare:</strong> $${fare.BaseFare?.Amount || 'N/A'}</p>

                                <p><strong>Total Fare:</strong> $${fare.TotalFare?.Amount || 'N/A'} (${fare.TotalFare?.CurrencyCode || 'USD'})</p>
                                
                               
                            </div>
                            <div class="tab-pane fade" id="baggage-info-${tabIdSuffix}" role="tabpanel">
                                <p><strong>Checked Baggage:</strong> ${baggage}</p>
                                <p><strong>Cabin Baggage:</strong> ${cabinBaggage}</p>
                            </div>
                            <div class="tab-pane fade" id="cancellation-${tabIdSuffix}" role="tabpanel">
                                <p><strong>Refundable:</strong> ${refund}</p>
                                <p><strong>Cancellation Fee:</strong> $${changeAmount}</p>
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
<script>
let allFlights = [];
let filteredFlights = [];
const batchSize = 10;
let visibleCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    allFlights = Array.from(document.querySelectorAll('.flight-card'));
    filteredFlights = [...allFlights];

    document.getElementById('show-more-flights').style.display =
        filteredFlights.length === 0 ? 'none' : 'block';

    setupFilters();

    document.getElementById('clear-all-filters').addEventListener('click', function(e) {
        e.preventDefault();
        resetFilters();
    });

    const showMoreBtn = document.getElementById('show-more-flights');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', showNextBatch);
    }

    initializeFlightsDisplay();
});

function initializeFlightsDisplay() {
    allFlights.forEach(flight => {
        flight.style.display = 'none';
    });

    visibleCount = 0;
    showNextBatch();
}

function showNextBatch() {
    const nextCount = visibleCount + batchSize;
    for (let i = visibleCount; i < nextCount && i < filteredFlights.length; i++) {
        filteredFlights[i].style.display = 'block';
    }

    visibleCount = nextCount;
    updateShowMoreButton();
}

function updateShowMoreButton() {
    const showMoreBtn = document.getElementById('show-more-flights');
    const countDisplay = document.getElementById('visible-flights-count');

    if (countDisplay) {
        countDisplay.textContent = `Showing ${Math.min(visibleCount, filteredFlights.length)} of ${filteredFlights.length} flights`;
    }

    if (showMoreBtn) {
        showMoreBtn.style.display = visibleCount < filteredFlights.length ? 'block' : 'none';
    }
}

function setupFilters() {
    if (allFlights.length === 0) return;

    let minPrice = Infinity;
    let maxPrice = 0;
    const airlines = new Map();

    allFlights.forEach(card => {
        const price = parseFloat(card.dataset.price || 0);
        if (price > 0) {
            minPrice = Math.min(minPrice, price);
            maxPrice = Math.max(maxPrice, price);
        }

        try {
            const flightData = JSON.parse(card.dataset.flight || '{}');
            if (flightData && flightData.FareItinerary) {
                const validatingCode = flightData.FareItinerary.ValidatingAirlineCode;
                if (validatingCode && !airlines.has(validatingCode)) {
                    airlines.set(validatingCode, validatingCode);
                }

                const options = flightData.FareItinerary.OriginDestinationOptions?.[0]?.OriginDestinationOption || [];
                options.forEach(option => {
                    const op = option.FlightSegment?.OperatingAirline;
                    if (op?.Code) {
                        airlines.set(op.Code, op.Name || op.Code);
                    }
                });
            }
        } catch (e) {
            console.error('Error parsing flight data', e);
        }
    });

    if (minPrice === Infinity) minPrice = 0;
    if (maxPrice === 0) maxPrice = 0;

    setupPriceRangeFilter(minPrice, maxPrice);
    setupAirlineFilters(airlines);
}

function setupPriceRangeFilter(minPrice, maxPrice) {
    minPrice = Math.floor(minPrice);
    maxPrice = Math.ceil(maxPrice);

    const minSlider = document.getElementById('price-slider-min');
    const maxSlider = document.getElementById('price-slider-max');
    const minDisplay = document.getElementById('price-min-display');
    const maxDisplay = document.getElementById('price-max-display');

    if (!minSlider || !maxSlider || !minDisplay || !maxDisplay) return;

    minSlider.min = minPrice;
    minSlider.max = maxPrice;
    minSlider.value = minPrice;

    maxSlider.min = minPrice;
    maxSlider.max = maxPrice;
    maxSlider.value = maxPrice;

    minDisplay.textContent = '$' + minPrice;
    maxDisplay.textContent = '$' + maxPrice;

    minSlider.addEventListener('input', function () {
        if (+minSlider.value > +maxSlider.value) {
            minSlider.value = maxSlider.value;
        }
        minDisplay.textContent = '$' + minSlider.value;
        applyFilters();
    });

    maxSlider.addEventListener('input', function () {
        if (+maxSlider.value < +minSlider.value) {
            maxSlider.value = minSlider.value;
        }
        maxDisplay.textContent = '$' + maxSlider.value;
        applyFilters();
    });
}

function setupAirlineFilters(airlines) {
    const container = document.getElementById('airlines-filters');
    if (!container) return;

    container.innerHTML = '';

    airlines.forEach((name, code) => {
        const checkboxId = 'airline-' + code;

        const checkboxDiv = document.createElement('div');
        checkboxDiv.className = 'airline-checkbox';
        checkboxDiv.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="${checkboxId}" value="${code}">
                <label class="form-check-label" for="${checkboxId}">
                    ${name} (${code})
                </label>
            </div>
        `;

        container.appendChild(checkboxDiv);
        document.getElementById(checkboxId).addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const minPrice = parseInt(document.getElementById('price-slider-min').value);
    const maxPrice = parseInt(document.getElementById('price-slider-max').value);

    const selectedAirlines = Array.from(
        document.querySelectorAll('#airlines-filters input[type="checkbox"]:checked')
    ).map(cb => cb.value);

    filteredFlights = allFlights.filter(card => {
        const price = parseFloat(card.dataset.price || 0);
        if (price < minPrice || price > maxPrice) return false;

        if (selectedAirlines.length === 0) return true;

        try {
            const flightData = JSON.parse(card.dataset.flight || '{}');
            if (flightData?.FareItinerary) {
                const validatingCode = flightData.FareItinerary.ValidatingAirlineCode;
                if (validatingCode && selectedAirlines.includes(validatingCode)) return true;

                const options = flightData.FareItinerary.OriginDestinationOptions?.[0]?.OriginDestinationOption || [];
                for (const option of options) {
                    const opCode = option.FlightSegment?.OperatingAirline?.Code;
                    if (opCode && selectedAirlines.includes(opCode)) return true;
                }
            }
        } catch (e) {
            console.error('Error checking airline filter', e);
        }

        return false;
    });

    allFlights.forEach(flight => (flight.style.display = 'none'));
    visibleCount = 0;
    showNextBatch();

    const noFlightsMessage = document.getElementById('no-flights-message');
    if (noFlightsMessage) {
        noFlightsMessage.style.display = filteredFlights.length === 0 ? 'block' : 'none';
    }
}

function resetFilters() {
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

    document.querySelectorAll('#airlines-filters input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });

    filteredFlights = [...allFlights];
    allFlights.forEach(flight => (flight.style.display = 'none'));
    visibleCount = 0;
    showNextBatch();

    const noFlightsMessage = document.getElementById('no-flights-message');
    if (noFlightsMessage) noFlightsMessage.style.display = 'none';
}
</script>
<script>
jQuery(document).ready(function ($) {
    $(".book-now-button").on("click", function (e) {
        e.preventDefault();
        
        const url = $(this).data("url");

        // Optional: Disable button to prevent double-clicks
        $(this).prop("disabled", true);
        $(".loader").show(); // Show loader

        // Add small delay to let loader show before navigating
        setTimeout(() => {
            window.location.href = url;
        }, 300); // Adjust delay as needed
    });
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

/*040625*/
.filters-container.flight-listing-section .price-range-slider {
  position: relative;
  width: 100%;
  height: 40px;
}
.filters-container.flight-listing-section .filters-container .multi-range-slider {
    position: relative;
    height: 30px;
    background: transparent;
}

.filters-container.flight-listing-section .multi-range-slider {
  position: relative;
  height: 8px;
    background-color: transparent;
  border-radius: 10px;
  margin: 20px 0;
}

.filters-container.flight-listing-section #slider-fill {
  position: absolute;
  height: 8px;
  background-color:#0d6efd;
  border-radius: 10px;
  top: 0;
  z-index: 2;
}

.filters-container.flight-listing-section input[type="range"] {
  -webkit-appearance: none;
  position: absolute;
  top: 0;
  width: 100%;
  height: 8px;
  background: transparent;
  pointer-events: none; /* disables track interaction */
  z-index: 3;
}

/* THUMB STYLE */
.filters-container.flight-listing-section input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 22px;
  width: 22px;
  border-radius: 50%;
  background: #2196F3;
  border: 3px solid white;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  pointer-events: auto;
  margin-top: -7px; /* aligns thumb vertically */
}

.filters-container.flight-listing-section input[type="range"]::-moz-range-thumb {
  height: 22px;
  width: 22px;
  border-radius: 50%;
  background: #2196F3;
  border: 3px solid white;
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  pointer-events: auto;
}

/* Remove default track style */
.filters-container.flight-listing-section input[type="range"]::-webkit-slider-runnable-track {
  background: transparent;
}

.filters-container.flight-listing-section input[type="range"]::-moz-range-track {
  background: transparent;
}

.filters-container.flight-listing-section .multi-range-slider {
  position: relative;
  width: 100%;
  height: 30px;
}

.filters-container.flight-listing-section .multi-range-slider input[type="range"] {
  position: absolute;
  pointer-events: none;
  -webkit-appearance: none;
  width: 100%;
  height: 8px;
  background: transparent;
}
.filters-container.flight-listing-section .filters-container .multi-range-slider input[type="range"] {
    position: absolute;
    width: 100%;
    pointer-events: none;
    appearance: none;
    height: 5px;
    background: transparent;
    border-radius: 5px;
    outline: none;
}
.filters-container.flight-listing-section .multi-range-slider input[type="range"]::-webkit-slider-thumb {
  pointer-events: auto;
  -webkit-appearance: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: blue;
  border: 2px solid white;
  cursor: pointer;
}

.filters-container.flight-listing-section .multi-range-slider input[type="range"]::-webkit-slider-runnable-track {
  height: 8px;
  background: transparent;
}

.filters-container.flight-listing-section .multi-range-slider .slider-track {
  position: absolute;
  height: 8px;
    background: transparent;
  border-radius: 5px;
  z-index: 1;
  top: 50%;
  transform: translateY(-50%);
  width: 100%;
}

.filters-container.flight-listing-section .multi-range-slider .slider-fill {
  position: absolute;
  height: 8px;
  background: green;
  border-radius: 5px;
  z-index: 2;
  top: 50%;
  transform: translateY(-50%);
}
.filters-container.flight-listing-section .multi-range-slider .slider-track {
  position: absolute;
  height: 8px;
  background: #e5e5e5;  /* Add this line - light gray color */
  border-radius: 5px;
  z-index: 1;
  top: 4%;
  transform: translateY(-50%);
  width: 100%;
}
 </style>
 
