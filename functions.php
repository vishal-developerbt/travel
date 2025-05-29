<?php

function create_special_offers_cpt() {
    $labels = array(
        'name' => 'Special Offers',
        'singular_name' => 'Special Offer',
        'menu_name' => 'Special Offers',
        'name_admin_bar' => 'Special Offer',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-tag',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
    );

    register_post_type('special_offers', $args);
}

add_action('init', 'create_special_offers_cpt');

function create_special_flight_cpt() {
    $labels = array(
        'name' => 'Special Flight',
        'singular_name' => 'Special Flight',
        'menu_name' => 'Special Flight',
        'name_admin_bar' => 'Special Flight',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-tag',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
    );

    register_post_type('special_flight', $args);
}

add_action('init', 'create_special_flight_cpt');

function create_trending_destinations_cpt() {
    $labels = array(
        'name' => 'Trending Destinations',
        'singular_name' => 'Trending Destinations',
        'menu_name' => 'Trending Destinations',
        'name_admin_bar' => 'Trending Destinations',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-tag',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => true,
    );

    register_post_type('Trending Destinations', $args);
}

add_action('init', 'create_trending_destinations_cpt');

function create_why_choose_us_cpt() {
    $labels = array(
        'name' => __('Why Choose Us', 'textdomain'),
        'singular_name' => __('Why Choose Us', 'textdomain'),
        'add_new' => __('Add New Reason', 'textdomain'),
        'add_new_item' => __('Add New Reason', 'textdomain'),
        'edit_item' => __('Edit Reason', 'textdomain'),
        'new_item' => __('New Reason', 'textdomain'),
        'view_item' => __('View Reason', 'textdomain'),
        'search_items' => __('Search Reasons', 'textdomain'),
        'not_found' => __('No reasons found', 'textdomain'),
        'not_found_in_trash' => __('No reasons found in Trash', 'textdomain'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-awards', // Change this icon if needed
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'why-choose-us'),
    );

    register_post_type('why_choose_us', $args);
}
add_action('init', 'create_why_choose_us_cpt');


function create_faq_post_type() {
    register_post_type('faq',
        array(
            'labels'      => array(
                'name'          => __('FAQs'),
                'singular_name' => __('FAQ'),
            ),
            'public'      => true,
            'has_archive' => false,
            'supports'    => array('title', 'editor'),
            'menu_icon'   => 'dashicons-editor-help',
        )
    );
}
add_action('init', 'create_faq_post_type');
function create_faq_taxonomy() {
    register_taxonomy(
        'faq_category',
        'faq',
        array(
            'label'        => __('FAQ Categories'),
            'rewrite'      => array('slug' => 'faq-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_faq_taxonomy');

// Register Custom Post Type: FAQ
function register_faq_post_type() {
    $labels = array(
        'name'          => 'FAQs',
        'singular_name' => 'FAQ',
        'menu_name'     => 'FAQs',
        'all_items'     => 'All FAQs',
        'add_new_item'  => 'Add New FAQ',
        'edit_item'     => 'Edit FAQ',
    );

    $args = array(
        'label'               => 'FAQs',
        'labels'              => $labels,
        'public'              => true,
        'show_in_menu'        => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-editor-help',
        'supports'            => array('title', 'editor'),
        'hierarchical'        => false,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'faq'),
    );

    register_post_type('faq', $args);
}
add_action('init', 'register_faq_post_type');

// Register Custom Taxonomy: FAQ Category
function register_faq_category_taxonomy() {
    $labels = array(
        'name'              => 'FAQ Categories',
        'singular_name'     => 'FAQ Category',
        'search_items'      => 'Search FAQ Categories',
        'all_items'         => 'All FAQ Categories',
        'edit_item'         => 'Edit FAQ Category',
        'update_item'       => 'Update FAQ Category',
        'add_new_item'      => 'Add New FAQ Category',
        'new_item_name'     => 'New FAQ Category Name',
        'menu_name'         => 'FAQ Categories',
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'faq-category'),
    );

    register_taxonomy('faq_category', 'faq', $args);
}
add_action('init', 'register_faq_category_taxonomy');


require_once get_template_directory() . '/class-bootstrap-navwalker.php';

add_theme_support( 'menus' );
function fetch_booking_listings() {
 
    global $wpdb;

      // Get user search inputs without default values
      $location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
      $checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
      $checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
      $rooms = isset($_GET['rooms']) ? explode('-', $_GET['rooms']) : [];
    if (empty($location) || empty($checkin) || empty($checkout) || empty($rooms)) {
        return [];
    }
    // Fetch city data from wp_cities table
    $city_data = $wpdb->get_row(
        $wpdb->prepare("SELECT city_name, country_name FROM wp_cities WHERE city_name LIKE %s LIMIT 1", $location),
        ARRAY_A
    );

    // Set city_name and country_name dynamically
    if ($city_data) {
        $city_name = $city_data['city_name'];
        $country_name = $city_data['country_name'];
    }

    // API request body
    $request_body = array(
        "user_id" => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access" => get_option('travelx_access'),
        "ip_address" => get_option('travelx_user_ip_address'),
        "requiredCurrency" => get_option('travelx_required_currency'),
        "checkin" => $checkin,
        "checkout" => $checkout,
        "city_name" => $city_name, // Updated from database
        "country_name" => $country_name, // Updated from database
        "radius" => 20,
        "maxResult" => 20,
        "occupancy" => array(
            array(
                "room_no" => intval($rooms[0]), // Convert to integer
                "adult" => intval($rooms[1]),   // Convert to integer
                "child" => 0,
                "child_age" => array(0)
            )
        )
    );
     $travelxHotelApi = get_option('travelx_hotel_api');
    // API Request
    $response = wp_remote_post($travelxHotelApi.'/hotel_search', array(
        'body'    => json_encode($request_body),
        'headers' => array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ),
        'method'  => 'POST',
        'timeout' => 20
    ));

    // Check for errors
    if (is_wp_error($response)) {
        return ['error' => 'API request failed: ' . $response->get_error_message()];
    }

    // Retrieve response body
    $body = wp_remote_retrieve_body($response);
    if (empty($body)) {
        return ['error' => 'Empty API response'];
    }

    // Decode JSON response
    $data = json_decode($body, true);
die('test fetch_booking_listings');
    // Ensure both `status` and `itineraries` exist in response
    return [
        'status' => isset($data['status']) ? $data['status'] : [],
        'itineraries' => isset($data['itineraries']) ? $data['itineraries'] : []
    ];
}
    

function add_custom_query_vars($vars) {
    $vars[] = 'paged';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');

// Flush permalinks after activating the theme (Run once)
function flush_rewrite_rules_on_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activate');


function fetch_homeHotel_booking_listings($location, $checkin, $checkout, $rooms) {
    global $wpdb;
    
    $travelxHotelApi = get_option('travelx_hotel_api');
    $api_url = $travelxHotelApi.'/hotel_search';

    // Validate input values and set defaults if needed
    $location = !empty($location) ? sanitize_text_field($location) : '';
    $checkin = !empty($checkin) ? sanitize_text_field($checkin) : date('Y-m-d');
    $checkout = !empty($checkout) ? sanitize_text_field($checkout) : date('Y-m-d', strtotime('+1 day'));
    $rooms = !empty($rooms) ? explode('-', $rooms) :'';



    $num_rooms = intval($rooms[0]);
    $total_adults = intval($rooms[1]);
    $total_children = intval($rooms[2]);

    // Distribute adults and children as evenly as possible
    $occupancy = [];
    for ($i = 0; $i < $num_rooms; $i++) {
        $remaining_rooms = $num_rooms - $i;
        $adults_in_room = intdiv($total_adults, $remaining_rooms);
        $children_in_room = intdiv($total_children, $remaining_rooms);

        $occupancy[] = array(
            "room_no" => $i + 1,
            "adult" => $adults_in_room,
            "child" => $children_in_room,
            "child_age" => array_fill(0, $children_in_room, 2)

        );

        $total_adults -= $adults_in_room;
        $total_children -= $children_in_room;
    }

$occupancy_serialized = serialize($occupancy);

// Set the cookie (expire in 1 hour)
setcookie('hotel_occupancy_data', $occupancy_serialized, time() + 3600, "/");

    // Check if the location exists in wp_cities table
    $city_data = $wpdb->get_row(
        $wpdb->prepare("SELECT city_name, country_name FROM wp_cities WHERE city_name LIKE %s LIMIT 1", $location),
        ARRAY_A
    );

    // If found, use the city_name and country_name from the database
    if ($city_data) {
        $city_name = $city_data['city_name'];
        $country_name = $city_data['country_name'];
    }
   // echo "rooms----".print_r($rooms);

    // API request body
    $request_body = array(
        "user_id" => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access" => get_option('travelx_access'),
        "ip_address" => get_option('travelx_user_ip_address'),
        "requiredCurrency" => get_option('travelx_required_currency'),  
        "search_method" => "citywise", 
        "checkin" => $checkin,
        "checkout" => $checkout,
        "city_name" => $city_name, // Updated from DB
        "country_name" => $country_name, // Updated from DB
        "radius" => 20,
        "maxResult" => 20,
        "occupancy" => $occupancy
    );

//echo "<pre>+"; print_r($request_body); die;
    // API Request
    $response = wp_remote_post($api_url, array(
        'body'    => json_encode($request_body),
        'headers' => array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ),
        'method'  => 'POST',
        'timeout' => 20
    ));

    // Check for errors
    if (is_wp_error($response)) {
        return ['error' => 'API request failed: ' . $response->get_error_message()];
    }

    // Retrieve response body
    $body = wp_remote_retrieve_body($response);

    if (empty($body)) {
        return ['error' => 'Empty API response'];
    }

    // Decode JSON response
    $data = json_decode($body, true);
    // Return both `status` and `itineraries`
    return [
        'status' => isset($data['status']) ? $data['status'] : [],
        'itineraries' => isset($data['itineraries']) ? $data['itineraries'] : []
    ];
}

//For hotel detail page
function fetch_hotel_details_by_id($hotelId, $productId, $tokenId, $sessionId) {
    if (empty($hotelId) || empty($productId) || empty($tokenId) || empty($sessionId)) {
        return ['error' => 'Missing required parameters'];
    }

    $travelxHotelApi = get_option('travelx_hotel_api');
    $api_url = $travelxHotelApi."/hotelDetails/?hotelId={$hotelId}&sessionId={$sessionId}&productId={$productId}&tokenId={$tokenId}";

    // Log API URL for debugging
    error_log("API Request URL: " . $api_url);

    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ),
        'timeout' => 20
    ));

    if (is_wp_error($response)) {
        return ['error' => 'API request failed: ' . $response->get_error_message()];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Handle expired sessionId
    if (isset($data['status']['error']) && strpos($data['status']['error'], 'Invalid sessionId') !== false) {
        error_log("Session expired, requesting new sessionId...");
        

        // Retry request with new sessionId
        return fetch_hotel_details_by_id($hotelId, $productId, $tokenId, $sessionId);
    }

    return !empty($data) ? $data : ['error' => 'Invalid API response'];
}
//For cities suggestion start
// Handle AJAX request for city autocomplete
add_action('wp_ajax_get_city_suggestions', 'get_city_suggestions');
add_action('wp_ajax_nopriv_get_city_suggestions', 'get_city_suggestions');

function get_city_suggestions() {
    global $wpdb;

    // Get the search term from AJAX request
    $search_term = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
    if(empty($search_term)){
          $input = file_get_contents('php://input');
        $data = json_decode($input, true);
       
        $search_term = isset($data['keyword']) ? sanitize_text_field($data['keyword']) : '';
    }
    if (strlen($search_term) < 3) {
        wp_send_json([]); // Do not search if less than 3 characters
        wp_die();
    }

    // Search cities in the wp_cities table
    $cities = $wpdb->get_results(
        $wpdb->prepare("SELECT city_name, country_name FROM wp_cities WHERE city_name LIKE %s LIMIT 10", '%' . $wpdb->esc_like($search_term) . '%'),
        ARRAY_A
    );

    if (!empty($cities)) {
        $suggestions = [];
        foreach ($cities as $city) {
            $suggestions[] = [
                'label' => $city['city_name'] . ', ' . $city['country_name'], // Shown in dropdown
                'value' => $city['city_name'], // Sent to the input field
            ];
        }
        wp_send_json($suggestions);
    } else {
        wp_send_json([['label' => "No results found", 'value' => '']]);
    }


    wp_die();
}

function get_city_suggestions_for_api() {
    global $wpdb;

    // Get the search term from AJAX request
    $search_term = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
    if(empty($search_term)){
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
       
        $search_term = isset($data['keyword']) ? sanitize_text_field($data['keyword']) : '';
    }

    // Don't search if less than 3 characters
    if (strlen($search_term) < 3) {
        wp_send_json([]); // Do not search if less than 3 characters
        wp_die();
    }

    // Search cities in the wp_cities table
    $cities = $wpdb->get_results(
        $wpdb->prepare("SELECT city_name, country_name FROM wp_cities WHERE city_name LIKE %s LIMIT 10", '%' . $wpdb->esc_like($search_term) . '%'),
        ARRAY_A
    );

    // If cities found, return them in the desired format
    if (!empty($cities)) {
        $response = [];
        foreach ($cities as $city) {
            $response[] = [
                'city_name' => $city['city_name'],
                'country_name' => $city['country_name'],
            ];
        }
        wp_send_json($response);
    } else {
        // If no results found, return a message with empty values
        wp_send_json([['city_name' => '', 'country_name' => '', 'message' => 'No results found']]);
    }

    wp_die();
}

function enqueue_city_autocomplete_script() {
    wp_enqueue_script('jquery-ui-autocomplete'); // Load jQuery UI
    wp_enqueue_script('city-autocomplete', get_template_directory_uri() . '/js/city-autocomplete.js', ['jquery'], null, true);
    wp_localize_script('city-autocomplete', 'citySearch', ['ajax_url' => admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts', 'enqueue_city_autocomplete_script');
//For cities suggestion End

//For hotel Room Option start
function fetch_room_option_for_hotel_details_page($hotelId, $productId, $tokenId, $sessionId) {
    // API URL
    $travelxHotelApi = get_option('travelx_hotel_api');
    $api_url = $travelxHotelApi.'/get_room_rates';

    // Prepare the request body
    $request_body = array(
        "sessionId"  => $sessionId,
        "productId"  => $productId,
        "tokenId"    => $tokenId,
        "hotelId"    => $hotelId
    );

    // Make the API request
    $response = wp_remote_post($api_url, array(
        'body'    => json_encode($request_body),
        'headers' => array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ),
        'method'  => 'POST',
        'timeout' => 20
    ));

    // Check for errors
    if (is_wp_error($response)) {
        return ['error' => 'API request failed: ' . $response->get_error_message()];
    }

    // Retrieve and decode the response
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Return the API response
    return !empty($data) ? $data : ['error' => 'Empty API response'];
}


//For hotel Room Option End

//=========================Flight Api======================================

function get_flight_availability($args = []) {
    $defaults = [
        'tripType' => 'OneWay',
        'origin' => '',
        'destination' => '',
        'departureDate' => '',
        'returnDate' => '',
        'class' => 'Economy',
        'adults' => 1,
        'children' => 0,
        'infants' => 0,
    ];
    $travelxFlightApi = get_option('travelx_flight_api');
    $params = wp_parse_args($args, $defaults);

    // Create OriginDestinationInfo as a single object
    $originDest = [
        "departureDate" => $params['departureDate'],
        "airportOriginCode" => strtoupper($params['origin']),
        "airportDestinationCode" => strtoupper($params['destination']),
    ];

    // ✅ Add returnDate ONLY if it's a Return trip
    if (strtolower($params['tripType']) === 'return' && !empty($params['returnDate'])) {
        $originDest['returnDate'] = $params['returnDate'];
    }

    $request_payload = [
        "user_id" => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access" => get_option('travelx_access'),
        "ip_address" => get_option('travelx_user_ip_address'),
        "requiredCurrency" => get_option('travelx_required_currency'),
        "journeyType" => $params['tripType'],
        "OriginDestinationInfo" => [$originDest], // Only one object
        "class" => $params['class'],
        "adults" => (int)$params['adults'],
        "childs" => (int)$params['children'],
        "infants" => (int)$params['infants']
    ];

    // 🔍 Debug (optional)
    // echo json_encode($request_payload); die;

    // API endpoint
    $api_url = $travelxFlightApi.'/availability';

    $response = wp_remote_post($api_url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($request_payload),
        'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
        return ['error' => $response->get_error_message()];
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}


function render_airport_dropdown() {
    global $wpdb;

    // Use full table name with prefix (if needed, otherwise keep as is if custom)
    $table_name = 'airport_list';

    // Fetch data
    $airports = $wpdb->get_results("SELECT id, airport_code, airport_name, city, country FROM $table_name");

    ob_start(); ?>
    
    

        <!-- Departure Airport Dropdown -->
        <div class="col-md-2 text-start location-flight-section">
            <label class="fw-bold loaction-text-homepage">
                <i class="fa-solid fa-plane-departure"></i> Departure Airport
            </label>
            <select id="departure-airport" name="departure_airport" class="form-control">
                <option value="">Select an airport...</option>
                <?php foreach ( $airports as $airport ): ?>
                    <option value="<?php echo esc_attr($airport->airport_code); ?>">
                        <?php echo esc_html("{$airport->airport_name} ({$airport->airport_code}) - {$airport->city}, {$airport->country}"); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Location Airport Dropdown (Duplicated) -->
        <div class="col-md-2 text-start location-flight-section">
            <label class="fw-bold loaction-text-homepage">
                <i class="fa-solid fa-location-dot"></i> Location
            </label>
            <select id="location-airport" name="location_airport" class="form-control">
                <option value="">Select a location...</option>
                <?php foreach ( $airports as $airport ): ?>
                    <option value="<?php echo esc_attr($airport->airport_code); ?>">
                        <?php echo esc_html("{$airport->airport_name} ({$airport->airport_code}) - {$airport->city}, {$airport->country}"); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


    

    <?php
    return ob_get_clean();
}
add_shortcode('airport_dropdown', 'render_airport_dropdown');

function get_airline_logo_url( $airline_code ) {
    global $wpdb;

    // Table name (with or without prefix depending on how you've stored it)
    $table_name = 'airline_list'; // Or $wpdb->prefix . 'airline_list' if it's using WP prefix

    // Sanitize input
    $airline_code = sanitize_text_field( strtoupper( $airline_code ) );

    // Query to fetch logo
    $logo_url = $wpdb->get_var( $wpdb->prepare(
        "SELECT airline_logo FROM $table_name WHERE airline_code = %s LIMIT 1",
        $airline_code
    ));

    return $logo_url ?: '';
}
//for trave airport search

// Handle airport suggestion request
add_action('wp_ajax_get_airport_suggestions', 'get_airport_suggestions');
add_action('wp_ajax_nopriv_get_airport_suggestions', 'get_airport_suggestions');

function get_airport_suggestions() {
    global $wpdb;

    $term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';

    if (strlen($term) < 2) {
        wp_send_json([]);
        wp_die();
    }

    $results = $wpdb->get_results(
        $wpdb->prepare("
            SELECT airport_name, airport_code, city, country
            FROM airport_list
            WHERE airport_name LIKE %s OR city LIKE %s OR airport_code LIKE %s
            LIMIT 10
        ", '%' . $wpdb->esc_like($term) . '%', '%' . $wpdb->esc_like($term) . '%', '%' . $wpdb->esc_like($term) . '%'),
        ARRAY_A
    );

    $suggestions = [];
    foreach ($results as $airport) {
        $label = sprintf(
            "%s ,%s , %s, %s",
            $airport['airport_name'],
            $airport['airport_code'],
            $airport['city'],
            $airport['country']
        );

        $suggestions[] = [
            'label' => $label,
            'value' => $label//$airport['airport_code'] // this will be set as input value
        ];
    }

    wp_send_json($suggestions);
    wp_die();
}

function enqueue_airport_autocomplete_script() {
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_script('airport-autocomplete', get_template_directory_uri() . '/js/airport-autocomplete.js', ['jquery', 'jquery-ui-autocomplete'], null, true);
    wp_localize_script('airport-autocomplete', 'airportSearch', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_airport_autocomplete_script');

function enqueue_guest_ajax_script() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('guest-script', get_template_directory_uri() . '/js/guest-ajax.js', array('jquery'), null, true);
    wp_localize_script('guest-script', 'guestAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('guest_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_guest_ajax_script');
add_action('wp_ajax_save_guest_data', 'save_guest_data_callback');

function save_guest_data_callback() {
    check_ajax_referer('guest_nonce', 'nonce');
    global $wpdb;
    $table = 'flight_booking_guest_details';

    $g_id = isset($_POST['g_id']) && !empty($_POST['g_id']) ? intval($_POST['g_id']) : 0;

    $data = array(
        'parent_user_id' => intval($_POST['user_id']),
        'guest_type'     => sanitize_text_field($_POST['guest_type']),
        'title'          => sanitize_text_field($_POST['title']),
        'first_name'     => sanitize_text_field($_POST['first_name']),
        'last_name'      => sanitize_text_field($_POST['last_name']),
        'dob'            => sanitize_text_field($_POST['dob']),
        'nationality'    => sanitize_text_field($_POST['nationality']),
        'guest_passport_number'    => sanitize_text_field($_POST['guest_passport_number']),
        'guest_issue_country'    => sanitize_text_field($_POST['guest_issue_country']),
        'guest_passport_expiry'    => sanitize_text_field($_POST['guest_passport_expiry']),
    );

    if ($g_id > 0) {
        // Update existing guest
        $updated = $wpdb->update(
            $table,
            $data,
            array('id' => $g_id)
        );

        if ($updated !== false) {
            wp_send_json_success(array_merge($data, ['id' => $g_id]));
        } else {
            wp_send_json_error('Update failed.');
        }
    } else {
        // Insert new guest
        $insert = $wpdb->insert($table, $data);

        if ($insert) {
            $id = $wpdb->insert_id;
            wp_send_json_success(array_merge($data, ['id' => $id]));
        } else {
            wp_send_json_error('Insert failed.');
        }
    }
}
// function save_guest_data_callback() {
//     check_ajax_referer('guest_nonce', 'nonce');
//     global $wpdb;
//     $table = 'flight_booking_guest_details';

//     $data = array(
//         'parent_user_id' => intval($_POST['user_id']),
//         'guest_type' => sanitize_text_field($_POST['guest_type']),
//         'title' => sanitize_text_field($_POST['title']),
//         'first_name' => sanitize_text_field($_POST['first_name']),
//         'last_name' => sanitize_text_field($_POST['last_name']),
//         'dob' => sanitize_text_field($_POST['dob']),
//         'nationality' => sanitize_text_field($_POST['nationality']),
//     );

//     $insert = $wpdb->insert($table, $data);

//     if ($insert) {
//         $id = $wpdb->insert_id;
//         wp_send_json_success(array_merge($data, ['id' => $id]));
//     } else {
//         wp_send_json_error('Database insert failed.');
//     }
// }

add_action('wp_ajax_get_flight_booking_guests', 'get_flight_booking_guests_callback');

function get_flight_booking_guests_callback() {
  check_ajax_referer('guest_nonce', 'nonce');

  $user_id = get_current_user_id();
  if (!$user_id) {
    wp_send_json_error('User not logged in');
  }

  global $wpdb;
  $table = 'flight_booking_guest_details';

  $results = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $table WHERE parent_user_id = %d", $user_id),
    ARRAY_A
  );

  if ($results) {
    wp_send_json_success($results);
  } else {
    wp_send_json_success([]); // no guests found
  }
}

add_action('wp_ajax_delete_guest', 'delete_guest_callback');

function delete_guest_callback() {
    check_ajax_referer('hotelguest_nonce', 'nonce');

    if (!isset($_POST['guest_id']) || empty($_POST['guest_id'])) {
        wp_send_json_error('Guest ID is required.');
    }

    $guest_id = intval($_POST['guest_id']);
    $user_id = get_current_user_id();

    if (!$user_id) {
        wp_send_json_error('User not authenticated.');
    }

    global $wpdb;
    $table = 'hotel_booking_guest_details';

    // Ensure the guest belongs to the current user
    $guest = $wpdb->get_row(
        $wpdb->prepare("SELECT id FROM $table WHERE id = %d AND parent_user_id = %d", $guest_id, $user_id)
    );
    if (!$guest) {
        wp_send_json_error('Guest not found or access denied.');
    }

    $deleted = $wpdb->delete($table, ['id' => $guest_id]);

    if ($deleted) {
        wp_send_json_success('Guest deleted successfully.');
    } else {
        wp_send_json_error('Failed to delete guest.');
    }
}

add_action('wp_ajax_flight_delete_guest', 'flight_delete_guest_callback');

function flight_delete_guest_callback() {
    check_ajax_referer('guest_nonce', 'nonce');

    if (!isset($_POST['guest_id']) || empty($_POST['guest_id'])) {
        wp_send_json_error('Guest ID is required.');
    }

    $guest_id = intval($_POST['guest_id']);
    $user_id = get_current_user_id();

    if (!$user_id) {
        wp_send_json_error('User not authenticated.');
    }

    global $wpdb;
    $table = 'flight_booking_guest_details';

    // Ensure the guest belongs to the current user
    $guest = $wpdb->get_row(
        $wpdb->prepare("SELECT id FROM $table WHERE id = %d AND parent_user_id = %d", $guest_id, $user_id)
    );

    if (!$guest) {
        wp_send_json_error('Guest not found or access denied.');
    }

    $deleted = $wpdb->delete($table, ['id' => $guest_id]);

    if ($deleted) {
        wp_send_json_success('Guest deleted successfully.');
    } else {
        wp_send_json_error('Failed to delete guest.');
    }
}



add_action('wp_ajax_get_guest_by_id', 'get_guest_by_id_callback');
function get_guest_by_id_callback() {
    check_ajax_referer('guest_nonce', 'nonce');

    global $wpdb;
    $guest_id = intval($_POST['guest_id']);

    $guest = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM flight_booking_guest_details WHERE id = %d", $guest_id
    ), ARRAY_A);

    if ($guest) {
        wp_send_json_success($guest);
    } else {
        wp_send_json_error(['message' => 'Guest not found.']);
    }
}
//for hotel save guest

function enqueue_hotel_guest_ajax_script() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('hotelguest-script', get_template_directory_uri() . '/js/hotelguest-ajax.js', array('jquery'), null, true);
    wp_localize_script('hotelguest-script', 'hotelguestAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('hotelguest_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_hotel_guest_ajax_script');
add_action('wp_ajax_save_hotel_guest_data', 'save_hotel_guest_data_callback');

function save_hotel_guest_data_callback() {
    check_ajax_referer('hotelguest_nonce', 'nonce');
    global $wpdb;
    $table = 'hotel_booking_guest_details';

    $g_id = isset($_POST['g_id']) && !empty($_POST['g_id']) ? intval($_POST['g_id']) : 0;

    $data = array(
        'parent_user_id' => intval($_POST['user_id']),
        'guest_type'     => sanitize_text_field($_POST['guest_type']),
        'guest_title'     => sanitize_text_field($_POST['guest_title']),
        'first_name'     => sanitize_text_field($_POST['first_name']),
        'last_name'      => sanitize_text_field($_POST['last_name']),
      
    );

    if ($g_id > 0) {
        // Update existing guest
        $updated = $wpdb->update(
            $table,
            $data,
            array('id' => $g_id)
        );

        if ($updated !== false) {
            wp_send_json_success(array_merge($data, ['id' => $g_id]));
        } else {
            wp_send_json_error('Update failed.');
        }
    } else {
        // Insert new guest
        $insert = $wpdb->insert($table, $data);

        if ($insert) {
            $id = $wpdb->insert_id;
            wp_send_json_success(array_merge($data, ['id' => $id]));
        } else {
            wp_send_json_error('Insert failed.');
        }
    }
}


add_action('wp_ajax_get_hotel_booking_guests', 'get_hotel_booking_guests_callback');

function get_hotel_booking_guests_callback() {
  check_ajax_referer('hotelguest_nonce', 'nonce');

  $user_id = get_current_user_id();
  if (!$user_id) {
    wp_send_json_error('User not logged in');
  }

  global $wpdb;
  $table = 'hotel_booking_guest_details';

  $results = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $table WHERE parent_user_id = %d", $user_id),
    ARRAY_A
  );

  if ($results) {
    wp_send_json_success($results);
  } else {
    wp_send_json_success([]); // no guests found
  }
}


add_action('wp_ajax_get_hotel_guest_by_id', 'get_hotel_guest_by_id_callback');
function get_hotel_guest_by_id_callback() {
    check_ajax_referer('hotelguest_nonce', 'nonce');

    global $wpdb;
    $guest_id = intval($_POST['guest_id']);

    $guest = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM hotel_booking_guest_details WHERE id = %d", $guest_id
    ), ARRAY_A);

    if ($guest) {
        wp_send_json_success($guest);
    } else {
        wp_send_json_error(['message' => 'Guest not found.']);
    }
}




add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/hotel-search', [
            'methods'  => 'POST',
            'callback' => 'my_custom_hotel_search',
            'permission_callback' => '__return_true',
        ]);
    });
     
    function my_custom_hotel_search($request) {

    $params = $request->get_json_params(); 
    $params['user_id'] = get_option('travelx_user_id');
    $params['user_password'] = get_option('travelx_user_password');
    $params['access'] = get_option('travelx_access');
    $params['ip_address'] = get_option('travelx_user_ip_address');
    //$params['requiredCurrency'] = get_option('travelx_required_currency');
    $travelxHotelApi = get_option('travelx_hotel_api');
    $cityName = isset($params['city_name']) ? sanitize_text_field($params['city_name']) : '';
    $countryName = isset($params['country_name']) ? sanitize_text_field($params['country_name']) : '';
        global $wpdb;
          if($countryName ==''){
             $city_data = $wpdb->get_row(
            $wpdb->prepare("SELECT city_name, country_name FROM wp_cities WHERE city_name LIKE %s LIMIT 1", $cityName),
            ARRAY_A
        );
      }
        if ($city_data) {
        $params['city_name'] = $city_data['city_name'];
        $params['country_name'] = $city_data['country_name'];
    }

    
    $response = wp_remote_post($travelxHotelApi.'/hotel_search', [
    'timeout' => 20, // default is 5-15 seconds, you can try 20-30
    'headers' => [ 'Content-Type' => 'application/json' ],
    'body'    => json_encode($params),
]); 
  if (is_wp_error($response)) {
    $error_code = $response->get_error_code();
    $error_message = $response->get_error_message();
    $error_data = $response->get_error_data(); // Can be array, string, or null

    // Log it (optional, useful for debugging)
    error_log("API Error [$error_code]: $error_message");
    if ($error_data) {
        error_log('Error Data: ' . print_r($error_data, true));
    }

    // Return a detailed WP_Error
    return new WP_Error(
        'api_error',
        "TravelNext API Error [$error_code]: $error_message",
        [
            'status' => 500,
            'details' => $error_data
        ]
    );
}
 
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    return rest_ensure_response($data);
}


 add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/hotel-details', [
            'methods'  => 'POST',
            'callback' => 'get_hotel_details_proxy',
            'permission_callback' => '__return_true',
        ]);
    });

function get_hotel_details_proxy($request) {
    $sessionId = sanitize_text_field($request->get_param('sessionId'));
    $hotelId   = sanitize_text_field($request->get_param('hotelId'));
    $productId = sanitize_text_field($request->get_param('productId'));
    $tokenId   = sanitize_text_field($request->get_param('tokenId'));

    if (empty($sessionId) || empty($hotelId) || empty($productId) || empty($tokenId)) {
        return new WP_Error('missing_params', 'All parameters are required.', ['status' => 400]);
    }
    $travelxHotelApi = get_option('travelx_hotel_api');

    $hotelDetails_url =   $travelxHotelApi.'/hotelDetails';  

    $api_url = add_query_arg([
        'sessionId' => $sessionId,
        'hotelId'   => $hotelId,
        'productId' => $productId,
        'tokenId'   => $tokenId,
    ], $hotelDetails_url);

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Error fetching hotel details.', ['status' => 500]);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return new WP_REST_Response($data, 200);
}


  //Check More Hotels Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/hotel-search

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/filter-results', [
            'methods'  => 'POST',
            'callback' => 'my_custom_hotel_filter_results',
            'permission_callback' => '__return_true',
        ]);
    });
     
    function my_custom_hotel_filter_results($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $travelxHotelApi = get_option('travelx_hotel_api');
        $response = wp_remote_post($travelxHotelApi.'/filterResults', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }
  //Hotel Room Rates Request
    //// https://travel.nexdew.com/wp-json/myplugin/v1/hotel-search


    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/get-room-rates', [
            'methods'  => 'POST',
            'callback' => 'my_custom_get_hotel_room_rates',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_get_hotel_room_rates($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $travelxHotelApi = get_option('travelx_hotel_api');
        $response = wp_remote_post($travelxHotelApi.'/get_room_rates', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }
//Hotel Check Room Rates Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/get-rate_rules


    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/get-rate_rules', [
            'methods'  => 'POST',
            'callback' => 'my_custom_get_hotel__rate_rules',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_get_hotel__rate_rules($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $travelxHotelApi = get_option('travelx_hotel_api');
        $response = wp_remote_post($travelxHotelApi.'/get_rate_rules', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

     //Hotel Booking Request
    //// https://travel.nexdew.com/wp-json/myplugin/v1/hotel-book


    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/hotel-book', [
            'methods'  => 'POST',
            'callback' => 'my_custom_hotel_book',
            'permission_callback' => '__return_true',
        ]);
    });

     
   function my_custom_hotel_book($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $customerPhone = $params['customerPhone'];
$insertData = [];

foreach ($params['paxDetails'] as $room) {
    $roomNo = $room["room_no"];

    if (isset($room["adult"])) {
        $adult = $room["adult"];
        $count = count($adult["firstName"]);
        for ($i = 0; $i < $count; $i++) {
            $insertData[] = [
                "customer_id" => $params['customer_id'],
                "title" => $adult["title"][$i],
                "firstName" => $adult["firstName"][$i],
                "lastName" => $adult["lastName"][$i],
                "guest_type" => "adult",
                "customer_email" => $params['customerEmail'],
                "location" => $params['location'],
                "checkin" => $params['checkin'],
                "checkout" => $params['checkout'],
                "rooms" => $params['rooms'],
                "productid" => $params['productId'],
                "hottel_id" => $params['hottel_id'],
                "price" => $params['price'],
                "booking_status" => 0,
                "payment_status" => $params['payment_status'],
                "transaction_id" =>$params['transaction_id'],
                "hotel_session_id" => $params['sessionId'],
                "hotel_token_id" => $params['tokenId'],
                "rateBasisId" => $params['rateBasisId'],
                "phone" => $params['customerPhone'],
                "specialRequests" => $params['specialRequests'],
                "fare_type" => $params['fare_type'],
                "referenceNum" => NULL,
                "supplierConfirmationNum" =>  NULL,
            ];
        }
    }

    if (isset($room["child"])) {
        $child = $room["child"];
        $count = count($child["firstName"]);
        for ($i = 0; $i < $count; $i++) {
            $insertData[] = [
                "customer_id" => $params['customer_id'],
                "title" => $adult["title"][$i],
                "firstName" => $child["firstName"][$i],
                "lastName" => $child["lastName"][$i],
                "guest_type" => "child",
                "customer_email" => $params['customerEmail'],
                "location" => $params['location'],
                "checkin" => $params['checkin'],
                "checkout" => $params['checkout'],
                "rooms" => $params['rooms'],
                "productid" => $params['productId'],
                "hottel_id" => $params['hottel_id'],
                "price" => $params['price'],
                "booking_status" => 0,
                "payment_status" => $params['payment_status'],
                "transaction_id" => $params['transaction_id'],
                "hotel_session_id" => $params['sessionId'],
                "hotel_token_id" => $params['tokenId'],
                "rateBasisId" => $params['rateBasisId'],
                "phone" => $params['customerPhone'],
                "specialRequests" => $params['specialRequests'],
                "fare_type" => $params['fare_type'],
                "referenceNum" => NULL,
                "supplierConfirmationNum" =>  NULL,

            ];
        }
    }
}

  saveMyBookings($insertData);

 //echo "<pre>"; print_r($insertData); die;

        $travelxHotelApi = get_option('travelx_hotel_api');
        $response = wp_remote_post($travelxHotelApi.'/hotel_book', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        $api_result = $data;
        updateMyBookings($api_result,$params);  

        return rest_ensure_response($data);
    }

    function updateMyBookings($api_result, $params) {
    $response_data = [];

    if (isset($api_result['status']) && in_array($api_result['status'], ['success', 'CONFIRMED'])) {
        global $wpdb;

        $supplierConfNum = $api_result['supplierConfirmationNum'] ?? '';
        $referenceNum = $api_result['referenceNum'] ?? '';
        $hotel_token_id = $params['tokenId'] ?? '';
         $updated_at = current_time('mysql'); 

        // Prepare the SQL query
        $sql = $wpdb->prepare(
            "UPDATE hotel_booking_details 
             SET booking_status = %s, supplierConfirmationNum = %s, referenceNum = %s,  updated_at = %s 
             WHERE hotel_token_id = %s",
            1,
            $supplierConfNum,
            $referenceNum,
            $updated_at,
            $hotel_token_id
        );

        $updated = $wpdb->query($sql);

        if ($updated === false) {
            // Handle DB error
            return new WP_Error('db_error', 'Booking confirmed but failed to update DB.');
        }

        $response_data['message'] = 'Booking confirmed and database updated.';
    } else {
        $response_data['message'] = 'Hotel API booking failed.';
    }

    return $response_data;
    }

function saveMyBookings($data){
    global $wpdb;

    $placeholders = [];
    $values = [];

    foreach ($data as $row) {
        $placeholders[] = '(' . implode(',', array_fill(0, count($row), '%s')) . ')';
        foreach ($row as $value) {
            $values[] = $value;
        }
    }

    $query = "INSERT INTO hotel_booking_details (
        customer_id, title, firstName, lastName, guest_type, customer_email,
        location, checkin, checkout, rooms, productid, hottel_id, price,
        booking_status, payment_status, transaction_id, hotel_session_id,
        hotel_token_id, rateBasisId, phone, specialRequests,fare_type, referenceNum, supplierConfirmationNum
    ) VALUES " . implode(', ', $placeholders);

    $wpdb->query( $wpdb->prepare( $query, ...$values ) );
}


      //Hotel Booking Request
    //// https://travel.nexdew.com/wp-json/myplugin/v1/booking-details


    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/booking-details', [
            'methods'  => 'POST',
            'callback' => 'my_custom_hotel_booking_details',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_hotel_booking_details($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
        $travelxHotelApi = get_option('travelx_hotel_api');
        $response = wp_remote_post($travelxHotelApi.'/bookingDetails', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

     //Hotel Booking Cancel Request
    //// https://travel.nexdew.com/wp-json/myplugin/v1/hotel-booking-cancel


    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/hotel-booking-cancel', [
            'methods'  => 'POST',
            'callback' => 'my_custom_hotel_booking_cancel',
            'permission_callback' => '__return_true',
        ]);
    });

     function my_custom_hotel_booking_cancel($request) {
    global $wpdb;

    $params = $request->get_json_params();
    $params['user_id'] = get_option('travelx_user_id');
    $params['user_password'] = get_option('travelx_user_password');
    $params['access'] = get_option('travelx_access');
    $params['ip_address'] = get_option('travelx_user_ip_address');
    $travelxHotelApi = get_option('travelx_hotel_api');

    $referenceNum = sanitize_text_field($params['referenceNum']);
    $supplierConfirmationNum = sanitize_text_field($params['supplierConfirmationNum']);

    $response = wp_remote_post($travelxHotelApi . '/cancel', [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($params),
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // If cancellation is successful, update the database
    if (!empty($data['status']) && $data['status'] === 'CANCELLED') {
        $cancelReferenceNum = sanitize_text_field($data['cancelReferenceNum']);

        $table = 'hotel_booking_details';

        $wpdb->update(
            $table,
            [ // Data to update
                'booking_status' => 0, // Assume 2 = cancelled
                'cancelReferenceNum' => $cancelReferenceNum,
                'updated_at' => current_time('mysql', 1)
            ],
            [ // Where conditions
                'referenceNum' => $referenceNum,
                'supplierConfirmationNum' => $supplierConfirmationNum
            ],
            [ '%d', '%s', '%s' ],
            [ '%s', '%s' ]
        );
    }

    return rest_ensure_response($data);
}


      //Hotel city suggestions Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/get-city


    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/get-city', [
            'methods'  => 'POST',
            'callback' => 'get_city_suggestions_for_api',
            'permission_callback' => '__return_true',
        ]);
    });

    // API FOR FLIGHT BOOKING

   // https://travel.nexdew.com/wp-json/myplugin/v1/flight-availability

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/flight-availability', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_availability',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_availability($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
         $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
       // $params['requiredCurrency'] = get_option('travelx_required_currency');
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/availability', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
            'timeout' => 50,
        ]);
     //echo "<pre>"; print_r($response); die;
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

    //Flight Fare Rules 
    //https://travel.nexdew.com/wp-json/myplugin/v1/fare-rules

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/fare-rules', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_fare_rules',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_fare_rules($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/fare_rules', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

    //Flight Revalidate Console
    //https://travel.nexdew.com/wp-json/myplugin/v1/flight-revalidate

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/flight-revalidate', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_revalidate',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_revalidate($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/revalidate', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

    //Flight Revalidate Console
    //https://travel.nexdew.com/wp-json/myplugin/v1/flight-booking

    
    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/flight-booking', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_booking',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_booking($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $travelxFlightApi = get_option('travelx_flight_api');
     
        $insertData = [];

        $adult_no = 0;
        $child_no = 0;
        $infant_no = 0;

        // First, calculate totals from all rooms
        foreach ($params['paxInfo']['paxDetails'] as $room) {
            foreach (['adult', 'child', 'infants'] as $type) {
                if (isset($room[$type]) && isset($room[$type]['firstName'])) {
                    $count = count($room[$type]['firstName']);
                    if ($type === 'adult') {
                        $adult_no += $count;
                    } elseif ($type === 'child') {
                        $child_no += $count;
                    } elseif ($type === 'infants') {
                        $infant_no += $count;
                    }
                }
            }
        }

    // Now build traveler array
    foreach ($params['paxInfo']['paxDetails'] as $room) {
        foreach (['adult', 'child', 'infants'] as $type) {
            if (isset($room[$type])) {
                $group = $room[$type];
                $count = count($group['firstName']);

                for ($i = 0; $i < $count; $i++) {
                    $traveler = [
                        'customer_id'    => $params['customer_id'] ?? '',
                        'trip_type'      => $params['trip_type'] ?? '',
                        'title'          => $group['title'][$i] ?? 'Mr',
                        'first_name'     => $group['firstName'][$i] ?? '',
                        'last_name'      => $group['lastName'][$i] ?? '',
                        'phone'          => $params['paxInfo']['customerPhone'] ?? '',
                        'email'          => $params['paxInfo']['customerEmail'] ?? '',
                        'dob'            => $group['dob'][$i] ?? '',
                        'nationality'    => $group['nationality'][$i] ?? '',
                        'passenger_type' => $type,
                        'destination_from' => $params['destination_from'] ?? '',
                        'destination_to'   => $params['destination_to'] ?? '',
                        'departure_date'   => $params['departure_date'] ?? '',
                        'return_date'      => $params['return_date'] ?? '',
                        'travel_class'     => $params['travel_class'] ?? '',
                        'adults_qty'       => $adult_no,
                        'children_qty'     => $child_no,
                        'infants_qty'      => $infant_no,
                        'session_id'      => $params['flightBookingInfo']['flight_session_id'] ?? '',
                        'fare_source_code' => $params['flightBookingInfo']['fare_source_code'] ?? '',
                        'amount'=>'',
                        'payment_status'=> $params['payment_status'] ?? 'pending',
                        'booking_status'=>'pending',
                        'transaction_id'=>$params['transaction_id'] ?? '',
                        'booking_id'=>null,
                        'fare_type' => $params['fare_type'],
                        'is_refundable' => $params['is_refundable'],
                        "created_at"=>current_time('mysql'),
                        "updated_at "=>current_time('mysql'),
                    ];

                    $insertData[] = $traveler;
                }
            }
        }
    }
// echo "<pre>++++"; print_r(json_encode($insertData)); die;


    $logPath = $logDir . '/flight-logs.log';
    $logMessage = '[' . date('Y-m-d H:i:s') . '] Customer Detail for Flight Booking:: '.json_encode($insertData) . PHP_EOL;
    file_put_contents($logPath, $logMessage, FILE_APPEND | LOCK_EX);

     saveFlightBookingdata($insertData);

        $response = wp_remote_post($travelxFlightApi.'/booking', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
            'timeout' => 50,
        ]);
     //echo "<pre>"; print_r($response); die();
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $response_json = json_decode($body, true);
        $bookingSessionId= $params['flightBookingInfo']['flight_session_id'] ?? '';

        $logPath = $logDir . '/flight-logs.log';
        $logMessage = '[' . date('Y-m-d H:i:s') . '] Flight Booking Response:: '.json_encode($response_json) .'bookingSessionId: '.$bookingSessionId. PHP_EOL;
    file_put_contents($logPath, $logMessage, FILE_APPEND | LOCK_EX);
     updateFlightBookingdata($response_json, $bookingSessionId);
//echo"<pre>"; print_r($response_json);
        return rest_ensure_response($response_json);
    }

function updateFlightBookingData($response_json, $bookingSessionId) {
    global $wpdb;

    

    // Extract status and booking ID from the response
    $status = strtoupper($response_json['BookFlightResponse']['BookFlightResult']['Status'] ?? '');
    $transaction_id = $response_json['BookFlightResponse']['BookFlightResult']['UniqueID'] ?? null;

    // Prepare data to update
    $data = [
        'booking_status' => ($status === 'CONFIRMED') ? 'confirmed' : 'failed',
        'booking_id' => $transaction_id,
        'updated_at' => current_time('mysql')
    ];

    // Prepare WHERE condition
    $where = [
        'session_id' => $bookingSessionId
    ];

    // Update the database
    $updated = $wpdb->update(
        'flight_booking_details',
        $data,
        $where,
        ['%s', '%s', '%s'], // Format for data
        ['%s'] // Format for where
    );

    // Check for errors
    if ($updated === false) {
        // Handle error (e.g., log it, notify admin)
        error_log('Failed to update booking data for session ID: ' . $bookingSessionId);
    }
}


function saveFlightBookingdata  ($data) {
    global $wpdb;

    // Initialize placeholders and values arrays
    $placeholders = [];
    $values = [];

    // Loop through each row of data
    foreach ($data as $row) {
        // Create a placeholder for each value in the row
        $placeholders[] = '(' . implode(',', array_fill(0, count($row), '%s')) . ')';
        // Add the row's values to the values array
        foreach ($row as $value) {
            $values[] = $value;
        }
    }

    // Construct the SQL query
    $query = "INSERT INTO flight_booking_details (
        customer_id, trip_type, title, first_name, last_name, phone, email, dob, nationality, passenger_type,
        destination_from, destination_to, departure_date, return_date, travel_class, adults_qty, children_qty, infants_qty,
        session_id, fare_source_code, amount, payment_status, booking_status, transaction_id, booking_id,fare_type,is_refundable, created_at, updated_at
    ) VALUES " . implode(', ', $placeholders);

    // Execute the query with the prepared values
    $wpdb->query($wpdb->prepare($query, ...$values));
}


    // Flight Trip Details Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/trip-details

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/trip-details', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_trip_details',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_trip_details($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/trip_details', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

        //Flight Ticket Order Request
    //https://travel.nexdew.com/wp-json/myplugin/v1/ticket-order

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/ticket-order', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_ticket_order',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_ticket_order($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/ticket_order', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }
 // Flight Cancel Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/flight-cancel

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/flight-cancel', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_trip_cancel',
            'permission_callback' => '__return_true',
        ]);
    });

     
     function my_custom_flight_trip_cancel($request) {
    global $wpdb;

    $params = $request->get_json_params(); // Get POST data sent by client
    $params['user_id'] = get_option('travelx_user_id');
    $params['user_password'] = get_option('travelx_user_password');
    $params['access'] = get_option('travelx_access');
    $params['ip_address'] = get_option('travelx_user_ip_address');
    $travelxFlightApi = get_option('travelx_flight_api');
    $UniqueID = sanitize_text_field($params['UniqueID']);

    $response = wp_remote_post($travelxFlightApi . '/cancel', [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($params),
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if cancellation was successful
    if (
        isset($data['CancelBookingResponse']['CancelBookingResult']['Success']) &&
        $data['CancelBookingResponse']['CancelBookingResult']['Success'] === "true"
    ) {
        $cancelledBookingID = sanitize_text_field($data['CancelBookingResponse']['CancelBookingResult']['UniqueID']);

        $table = 'flight_booking_details';

        $wpdb->update(
            $table,
            [
                'booking_status' => 'Cancelled',
                'updated_at' => current_time('mysql', 1),
            ],
            [
                'booking_id' => $cancelledBookingID,
            ],
            ['%s', '%s'],
            ['%s']
        );
    }

    return rest_ensure_response($data);
}


    // Flight Booking Notes Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/flight-booking-notes

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/flight-booking-notes', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_booking_notes',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_booking_notes($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/booking_notes', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     

        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

     add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/search-post-ticket-status', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_search_post_ticket_status',
            'permission_callback' => '__return_true',
        ]);
    });

     
    function my_custom_flight_search_post_ticket_status($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/search_post_ticket_status', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }



    //Flight Refund Request
    // https://travel.nexdew.com/wp-json/myplugin/v1/flight-booking-refund

    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/flight-booking-refund', [
            'methods'  => 'POST',
            'callback' => 'my_custom_flight_booking_refund',
            'permission_callback' => 'is_user_logged_in_via_token',
        ]);
    });

     
    function my_custom_flight_booking_refund($request) {
        $params = $request->get_json_params(); // Get POST data sent by client
        $params['user_id'] = get_option('travelx_user_id');
        $params['user_password'] = get_option('travelx_user_password');
        $params['access'] = get_option('travelx_access');
        $params['ip_address'] = get_option('travelx_user_ip_address');
        $travelxFlightApi = get_option('travelx_flight_api');
        $response = wp_remote_post($travelxFlightApi.'/refund', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($params),
        ]);
     
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
        }
     
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
     
        return rest_ensure_response($data);
    }

    //     //Customer Register Api
    function custom_user_register($request) {

        $first_name = sanitize_text_field($request['first_name']);
        $last_name = sanitize_text_field($request['last_name']);
        $username = sanitize_text_field($request['username']);
        $email    = sanitize_email($request['email']);
        $phone    = sanitize_text_field($request['phone']);
        $password = $request['password'];

        if (empty($username) || empty($email) || empty($password) || empty($phone)) {
            return new WP_Error('missing_fields', 'Username, email, phone, and password are required.', ['status' => 400]);
        }

        if (username_exists($username) || email_exists($email)) {
            return new WP_Error('user_exists', 'Username or email already exists.', ['status' => 409]);
        }

        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            return new WP_Error('registration_failed', 'User registration failed.', ['status' => 500]);
        }

        // Save phone to user meta
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'first_name', $first_name);
        update_user_meta($user_id, 'last_name', $last_name);
        update_user_meta($user_id, 'ur_form_id', 214);
        
        // Log in the user and generate JWT
        $user = get_user_by('id', $user_id);

        $issuedAt   = time();
        $expiration = $issuedAt + (DAY_IN_SECONDS * 7);
        $secret_key = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : 'your-secret-key';

        $payload = [
            'iss' => get_bloginfo('url'),
            'iat' => $issuedAt,
            'exp' => $expiration,
            'data' => [
                'user' => [
                    'id' => $user->ID
                ]
            ]
        ];

        // Include JWT class if necessary
        if (!class_exists('Firebase\JWT\JWT')) {
            require_once ABSPATH . 'wp-content/plugins/jwt-authentication-for-wp-rest-api/includes/vendor/firebase/php-jwt/src/JWT.php';
        }
// Retrieve the profile picture ID and get the URL
            $profile_pic_id = get_user_meta($user->ID, 'user_registration_profile_pic_url', true);
            $profile_pic_url = $profile_pic_id ? wp_get_attachment_url($profile_pic_id) :'';
        $token = \Firebase\JWT\JWT::encode($payload, $secret_key, 'HS256');

        return new WP_REST_Response([
            'success' => true,
            'user_id' => $user->ID,
            'username' => $username,
            'first_name'    => $first_name,
            'last_name'    => $last_name,
            'email'    => $email,
            'phone'    => $phone,
             'profile_pic_url' => $profile_pic_url? $profile_pic_url : site_url().'/wp-content/themes/travel/photos/avatar.png',
            'token'    => $token,
            'message'  => 'User registered and logged in successfully.'
        ], 201);
    }


        //Customer Register Api
    add_action('rest_api_init', function () {
        register_rest_route('custom/v1', '/register', [
            'methods'  => 'POST',
            'callback' => 'custom_user_register',
            'permission_callback' => '__return_true', // Consider securing this for production
        ]);
    });

    // function custom_user_register($request) {
    //     $username = sanitize_text_field($request['username']);
    //     $email    = sanitize_email($request['email']);
    //     $phone    = sanitize_text_field($request['phone']);
    //     $password = $request['password'];

    //     if (empty($username) || empty($email) || empty($password) || empty($phone)) {
    //         return new WP_Error('missing_fields', 'Username, email, and password are required.', ['status' => 400]);
    //     }

    //     if (username_exists($username) || email_exists($email)) {
    //         return new WP_Error('user_exists', 'Username or email already exists.', ['status' => 409]);
    //     }

    //     $user_id = wp_create_user($username, $password, $email, $phone);

    //     if (is_wp_error($user_id)) {
    //         return new WP_Error('registration_failed', 'User registration failed.', ['status' => 500]);
    //     }

    //     return new WP_REST_Response([
    //         'success' => true,
    //         'user_id' => $user_id,
    //          'username' => $username,
    //         'email' => $email,
    //         'phone' => $phone,
    //         'message' => 'User registered successfully.'
    //     ], 201);
    // }

       //Customer Login Api
        add_action('rest_api_init', function () {
            register_rest_route('custom/v1', '/login', [
                'methods' => 'POST',
                'callback' => 'custom_user_login',
                'args' => [
                    'username' => ['required' => true],
                    'password' => ['required' => true],
                ],
                'permission_callback' => '__return_true',
            ]);
        });


        function custom_user_login($request) {
            $username = sanitize_text_field($request['username']);
            $password = sanitize_text_field($request['password']);

            if (empty($username) || empty($password)) {
                return new WP_Error('missing_credentials', 'Username and password are required.', ['status' => 400]);
            }

            $user = wp_authenticate($username, $password);

            if (is_wp_error($user)) {
                return new WP_Error('invalid_login', 'Invalid username or password.', ['status' => 401]);
            }

            // JWT Token generation
            $issuedAt = time();
            $expiration = $issuedAt + (DAY_IN_SECONDS * 7); // Token valid for 7 days
            $secret_key = defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : 'your-secret-key';

            $payload = [
                'iss' => get_bloginfo('url'),
                'iat' => $issuedAt,
                'exp' => $expiration,
                'data' => [
                    'user' => [
                        'id' => $user->ID
                    ]
                ]
            ];

            // Ensure you include the JWT class
            if (!class_exists('Firebase\JWT\JWT')) {
                require_once ABSPATH . 'wp-content/plugins/jwt-authentication-for-wp-rest-api/includes/vendor/firebase/php-jwt/src/JWT.php';
            }

            $token = \Firebase\JWT\JWT::encode($payload, $secret_key, 'HS256');

            // Retrieve the profile picture ID and get the URL
            $profile_pic_id = get_user_meta($user->ID, 'user_registration_profile_pic_url', true);
            $profile_pic_url = $profile_pic_id ? wp_get_attachment_url($profile_pic_id) : ''; // Convert ID to URL

            // Send the response
            return new WP_REST_Response([
                'success' => true,
                'user_id' => $user->ID,
                'first_name' => get_user_meta($user->ID, 'first_name', true),
                'last_name' => get_user_meta($user->ID, 'last_name', true),
                'username' => $user->user_login,
                'email' => $user->user_email,
                'phone' => get_user_meta($user->ID, 'phone', true),
                'profile_pic_url' => $profile_pic_url? $profile_pic_url : site_url().'/wp-content/themes/travel/photos/avatar.png', // The URL of the profile picture
                'token' => $token,
                'message' => 'Login successful.'
            ], 200);
        }


    //Customer Password Change
    add_action('rest_api_init', function () {
        register_rest_route('myplugin/v1', '/change-password', [
            'methods'  => 'POST',
            'callback' => 'handle_app_change_password',
            'permission_callback' => 'is_user_logged_in_via_token',
        ]);
    });
     
    function is_user_logged_in_via_token() {
        return is_user_logged_in(); // JWT handles auth, this checks it
    }
     
    function handle_app_change_password($request) {
        $params = $request->get_json_params();
        $current_password = sanitize_text_field($params['current_password']);
        $new_password     = sanitize_text_field($params['new_password']);
     
        $user = wp_get_current_user();
     
        if (!$user || !$user->ID) {
            return new WP_Error('unauthorized', 'Invalid token or user not logged in', ['status' => 403]);
        }
     
        if (empty($current_password) || empty($new_password)) {
            return new WP_Error('missing_fields', 'Current and new passwords are required.', ['status' => 400]);
        }
     
        if (!wp_check_password($current_password, $user->user_pass, $user->ID)) {
            return new WP_Error('incorrect_password', 'Current password is incorrect.', ['status' => 401]);
        }
     
        wp_set_password($new_password, $user->ID);
     
        return rest_ensure_response([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

     //Customer Profile Change
    add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/update-profile', [
        'methods'  => 'POST',
        'callback' => 'custom_update_user_profile',
        'permission_callback' => '__return_true',
        'timeout' => 20,

    ]);
});
function custom_update_user_profile($request) {
    $user_id      = absint($request['user_id']);
    $first_name   = sanitize_text_field($request['first_name']);
    $last_name    = sanitize_text_field($request['last_name']);
    $phone        = sanitize_text_field($request['phone']);
    $base64_image = $request['profile_pic_base64']; // base64 image

    $user = get_userdata($user_id);
    if (!$user) {
        return new WP_Error('invalid_user', 'Invalid or missing user ID', ['status' => 400]);
    }

    $email = $user->user_email;

    $user_data = [
        'ID'         => $user_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ];

    $update_result = wp_update_user($user_data);

    if (is_wp_error($update_result)) {
        return new WP_Error('update_failed', 'Failed to update user', ['status' => 500]);
    }

    // ✅ Save using correct meta key
    update_user_meta($user_id, 'user_registration_phone', $phone);

    $profile_pic_url = '';
    if (!empty($base64_image)) {
        $attachment_id = custom_upload_base64_image($base64_image, $user_id);
        if ($attachment_id && !is_wp_error($attachment_id)) {
            update_user_meta($user_id, 'user_registration_profile_pic_url', $attachment_id);
            $profile_pic_url = wp_get_attachment_url($attachment_id);
        }
    } else {
        $existing_id = get_user_meta($user_id, 'user_registration_profile_pic_url', true);
        $profile_pic_url = $existing_id ? wp_get_attachment_url($existing_id) : '';
    }

    return new WP_REST_Response([
        'success' => true,
        'message' => 'Profile updated successfully.',
        'data'    => [
            'user_id'         => $user_id,
            'first_name'      => $first_name,
            'last_name'       => $last_name,
            'email'           => $email,
            'phone'           => get_user_meta($user_id, 'user_registration_phone', true),
            'profile_pic_url' => $profile_pic_url
        ]
    ], 200);

}


function custom_upload_base64_image($base64_image, $user_id) {
    // Extract file data
    if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
        $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            return new WP_Error('invalid_type', 'Unsupported image type');
        }

        $base64_image = base64_decode($base64_image);
        if ($base64_image === false) {
            return new WP_Error('decode_error', 'Base64 decode failed');
        }
    } else {
        return new WP_Error('invalid_format', 'Invalid base64 image format');
    }

    $filename = 'profile-pic-' . $user_id . '.' . $type;

    // Upload to media library
    $upload_file = wp_upload_bits($filename, null, $base64_image);
    if ($upload_file['error']) {
        return new WP_Error('upload_error', $upload_file['error']);
    }

    // Create attachment
    $file_type = wp_check_filetype($upload_file['file'], null);
    $attachment = [
        'post_mime_type' => $file_type['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attach_id = wp_insert_attachment($attachment, $upload_file['file']);

    // Include image handling
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

//     add_action('rest_api_init', function () {
//         register_rest_route('custom/v1', '/update-profile', [
//             'methods'  => 'POST',
//             'callback' => 'custom_update_user_profile',
//             'permission_callback' => function () {
//                 return is_user_logged_in(); // requires authentication
//             }
//         ]);
//     });

//     function custom_update_user_profile($request) {
//     $user = wp_get_current_user();

//     $first_name = sanitize_text_field($request['first_name']);
//     $last_name  = sanitize_text_field($request['last_name']);
//     $phone      = sanitize_text_field($request['phone']);

//     $user_data = [
//         'ID'         => $user->ID,
//         'first_name' => $first_name,
//         'last_name'  => $last_name,
//     ];

//     // Update user profile fields
//     wp_update_user($user_data);

//     // Update phone as user meta
//     update_user_meta($user->ID, 'phone', $phone);

//     return new WP_REST_Response([
//         'success' => true,
//         'message' => 'Profile updated successfully.',
//         'data' => [
//             'first_name' => $first_name,
//             'last_name'  => $last_name,
//             'phone'      => $phone
//         ]
//     ], 200);
// }

/*for User Hotel Bokking detail Get by APi*/

function get_booking_details_by_api($referenceNum, $supplierConfirmationNum) {

    $payload = [
            'user_id' => get_option('travelx_user_id'),
            'user_password' => get_option('travelx_user_password'),
            'access' => get_option('travelx_access'),
            'ip_address' => get_option('travelx_user_ip_address'),
            'supplierConfirmationNum' => $supplierConfirmationNum,
            'referenceNum' => $referenceNum,
        ];

     $travelxHotelApi = get_option('travelx_hotel_api');
    // API endpoint
    $api_url = $travelxHotelApi.'/bookingDetails';

    $response = wp_remote_post($api_url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($payload),
        'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
        return ['error' => $response->get_error_message()];
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}


/*for User Flight Bokking detail Get by APi*/

function get_flight_booking_details_by_api($UniqueID, ) {

    $payload = [
            'user_id' => get_option('travelx_user_id'),
            'user_password' => get_option('travelx_user_password'),
            'access' => get_option('travelx_access'),
            'ip_address' => get_option('travelx_user_ip_address'),
            'UniqueID' => $UniqueID,
        ];

     $travelxFlightApi = get_option('travelx_flight_api');
    // API endpoint
    $api_url = $travelxFlightApi.'/trip_details';

    $response = wp_remote_post($api_url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($payload),
        'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
        return ['error' => $response->get_error_message()];
    }

    return json_decode(wp_remote_retrieve_body($response), true);
}

add_action('wp_ajax_cancel_hotel_booking', 'handle_cancel_hotel_booking');
add_action('wp_ajax_nopriv_cancel_hotel_booking', 'handle_cancel_hotel_booking');
add_action('wp_ajax_cancel_hotel_booking', 'handle_cancel_hotel_booking');
add_action('wp_ajax_nopriv_cancel_hotel_booking', 'handle_cancel_hotel_booking');

function handle_cancel_hotel_booking() {
    check_ajax_referer('cancel_hotel_booking_nonce', 'cancel_nonce');

    global $wpdb;

    $referenceNum            = sanitize_text_field($_POST['referenceNum'] ?? '');
    $supplierConfirmationNum = sanitize_text_field($_POST['supplierConfirmationNum'] ?? '');
    $hotel_token_id          = sanitize_text_field($_POST['hotel_token_id'] ?? '');
    $travelxHotelApi = get_option('travelx_hotel_api');

    if (empty($referenceNum) || empty($supplierConfirmationNum) || empty($hotel_token_id)) {
        wp_send_json_error('Missing required booking details.');
    }

    // Prepare API payload
    $payload = [
        "user_id" => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access" => get_option('travelx_access'),
        "ip_address" => get_option('travelx_user_ip_address'),
        'supplierConfirmationNum' => $supplierConfirmationNum,
        'referenceNum' => $referenceNum,
    ];

    // Make API call
    $response = wp_remote_post($travelxHotelApi.'/cancel', [
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => json_encode($payload),
        'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error('API call failed: ' . $response->get_error_message());
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    // ✅ Update local database booking_status = '0' (Failed)
    $updated = $wpdb->update(
        'hotel_booking_details',
        ['booking_status' => '0'],
        ['hotel_token_id' => $hotel_token_id],
        ['%d'],
        ['%s']
    );

    if ($updated === false) {
        wp_send_json_error('Booking canceled, but local DB update failed.');
    }

    wp_send_json_success(['api_response' => $body, 'message' => 'Booking canceled successfully.']);
}
function enqueue_cancel_hotel_booking_script() {
    wp_enqueue_script('cancel-hotel-booking-js', get_template_directory_uri() . '/js/cancel-hotel-booking.js', [], null, true);

    // Pass admin-ajax URL and nonce if needed
    wp_localize_script('cancel-hotel-booking-js', 'cancelHotelBookingAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_cancel_hotel_booking_script');



/*=======================flight cancel code ===================*/
    add_action('wp_ajax_cancel_flight_booking', 'handle_cancel_flight_booking');
    add_action('wp_ajax_nopriv_cancel_flight_booking', 'handle_cancel_flight_booking');

    function handle_cancel_flight_booking() {
        check_ajax_referer('cancel_hotel_booking_nonce', 'cancel_nonce');

        global $wpdb;

        $booking_id   = sanitize_text_field($_POST['booking_id'] ?? '');
        $session_id   = sanitize_text_field($_POST['session_id'] ?? '');
        $travelxFlightApi = get_option('travelx_flight_api');

        if (empty($session_id) || empty($booking_id)) {
            wp_send_json_error('Missing flight booking information.');
        }

        // 📦 API payload for flight cancel
        $payload = [
            "user_id" => get_option('travelx_user_id'),
            "user_password" => get_option('travelx_user_password'),
            "access" => get_option('travelx_access'),
            "ip_address" => get_option('travelx_user_ip_address'),
            'UniqueID'      => $booking_id, // Unique ID = booking_id
        ];

        // 🔗 Call the flight cancel API
        $response = wp_remote_post($travelxFlightApi.'/cancel', [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode($payload),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error('API call failed: ' . $response->get_error_message());
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        // ✅ Update local booking_status to "0" (Failed/Cancelled)
        $updated = $wpdb->update(
            'flight_booking_details',
            ['booking_status' => 'Cancelled'],
            ['session_id' => $session_id],
            ['%s'],
            ['%s']
        );

        if ($updated === false) {
            wp_send_json_error('Flight canceled via API, but DB update failed.');
        }

        wp_send_json_success([
            'api_response' => $body,
            'message' => 'Flight canceled successfully.',
        ]);
    }


    function enqueue_cancel_flight_script() {
        wp_enqueue_script('cancel-flight-js', get_template_directory_uri() . '/js/cancel-flight.js', [], null, true);

        wp_localize_script('cancel-flight-js', 'flightCancelAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }
    add_action('wp_enqueue_scripts', 'enqueue_cancel_flight_script');

    /*Order Api for upcoming hotel bookings*/
    add_action('rest_api_init', function () {
        register_rest_route('custom-api/v1', '/upcoming-bookings/(?P<user_id>\d+)', [
            'methods' => 'GET',
            'callback' => 'get_upcoming_hotel_bookings',
            'permission_callback' => '__return_true' // Use authentication in production!
        ]);
    });

    function get_upcoming_hotel_bookings($request) {
        global $wpdb;

        $user_id = intval($request['user_id']);

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

        return rest_ensure_response($results);
    }

    /*Order Api for past hotel bookings*/
    add_action('rest_api_init', function () {
        register_rest_route('custom-api/v1', '/past-bookings/(?P<user_id>\d+)', [
            'methods' => 'GET',
            'callback' => 'get_past_hotel_bookings',
            'permission_callback' => '__return_true' // For testing only — lock this down later
        ]);
    });


    function get_past_hotel_bookings($request) {
        global $wpdb;

        $user_id = intval($request['user_id']);

        $pastorder = $wpdb->get_results(
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

        return rest_ensure_response($pastorder);
    }

    /*Api for Upcoming Flights*/
    add_action('rest_api_init', function () {
        register_rest_route('custom-api/v1', '/upcoming-flights/(?P<user_id>\d+)', [
            'methods' => 'GET',
            'callback' => 'get_upcoming_flight_bookings',
            'permission_callback' => '__return_true'
        ]);
    });
    function get_upcoming_flight_bookings($request) {
        global $wpdb;

        $user_id = intval($request['user_id']);

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

        return rest_ensure_response($upcommingFlightResult);
    }

    /*Api for Past Flights*/
    add_action('rest_api_init', function () {
        register_rest_route('custom-api/v1', '/past-flights/(?P<user_id>\d+)', [
            'methods' => 'GET',
            'callback' => 'get_past_flight_bookings',
            'permission_callback' =>  '__return_true'
        ]);
    });
    function get_past_flight_bookings($request) {
        global $wpdb;

        $user_id = intval($request['user_id']);

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
        );

        return rest_ensure_response($pastFlights);
    }


    add_action('rest_api_init', function () {
        register_rest_route('custom-api/v1', '/booking-list/(?P<user_id>\d+)', [
            'methods' => 'GET',
            'callback' => 'get_all_booking_lists',
            'permission_callback' =>  '__return_true'
        ]);
    });
    function get_all_booking_lists($request) {
        global $wpdb;

        $user_id = intval($request['user_id']);


          $hotelResults = $wpdb->get_results(
    $wpdb->prepare("
        SELECT 
            h.id,
            h.customer_id,
            h.title,
            h.firstName,
            h.lastName,
            h.guest_type,
            h.customer_email,
            h.location,
            h.checkin,
            h.checkout,
            h.rooms,
            h.productid,
            h.hottel_id,
            h.price,
            CASE 
                WHEN h.booking_status = 1 THEN 'confirmed'
                ELSE 'cancelled'
            END AS booking_status,
            h.payment_status,
            h.transaction_id,
            h.hotel_session_id,
            h.hotel_token_id,
            h.rateBasisId,
            h.phone,
            h.specialRequests,
            h.referenceNum,
            h.supplierConfirmationNum,
            h.fare_type,
            h.created_at,
            h.updated_at
        FROM hotel_booking_details h
        INNER JOIN (
            SELECT MAX(id) as latest_id
            FROM hotel_booking_details
            WHERE customer_id = %d
            GROUP BY transaction_id
        ) latest ON h.id = latest.latest_id
        ORDER BY h.id DESC
    ", $user_id),
    ARRAY_A
);

        $flightResults = $wpdb->get_results(
            $wpdb->prepare("
                SELECT * FROM flight_booking_details f
                INNER JOIN (
                    SELECT MAX(id) as latest_id
                    FROM flight_booking_details
                    WHERE customer_id = %d
                    GROUP BY transaction_id
                ) latest ON f.id = latest.latest_id
                ORDER BY f.id DESC
            ", $user_id),
            ARRAY_A
        );

        // Combine both in a single response
        $response = array(
            'hotelbooking'  => $hotelResults,
            'flightbooking' => $flightResults
        );

        return rest_ensure_response($response);
    }


    //Customer Profile Change
    add_action('rest_api_init', function () {
        register_rest_route('custom/v1', '/forgot-password', [
            'methods'  => 'POST',
            'callback' => 'custom_forgot_password',
            'permission_callback' => '__return_true' // allow public access
        ]);
    });

    function custom_forgot_password($request) {
        $user_login = sanitize_text_field($request['user_login']);

        if (empty($user_login)) {
            return new WP_Error('missing_user_login', 'Username or email is required.', ['status' => 400]);
        }

        // Find user by login or email
        $user = get_user_by('email', $user_login);
        if (!$user) {
            $user = get_user_by('login', $user_login);
        }

        if (!$user) {
            return new WP_Error('invalid_user', 'User not found.', ['status' => 404]);
        }

        // Generate reset key
        $reset_key = get_password_reset_key($user);

        if (is_wp_error($reset_key)) {
            return new WP_Error('reset_key_error', 'Could not generate reset key.', ['status' => 500]);
        }

        // Generate reset URL
        $reset_url = site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');

        // Send reset email
        $subject = 'Password Reset Request';
        $message = "Hi " . $user->user_login . ",\n\n";
        $message .= "Click the link below to reset your password:\n\n";
        $message .= $reset_url . "\n\n";
        $message .= "If you didn't request this, please ignore this email.\n\n";

        $sent = wp_mail($user->user_email, $subject, $message);

        if (!$sent) {
            return new WP_Error('email_failed', 'Failed to send reset email.', ['status' => 500]);
        }

        return new WP_REST_Response([
            'success' => true,
            'message' => 'Password reset email sent.'
        ], 200);
    }


    add_action('rest_api_init', function () {
        register_rest_route('flight-settings/v1', '/get', [
            'methods'  => 'GET',
            'callback' => 'travelx_get_settings_api',
           'permission_callback' => '__return_true',
        ]);
    });

    function travelx_get_settings_api() {
        $options = [
            'travelx_user_id'           => get_option('travelx_user_id'),
            'travelx_user_password'     => str_repeat('*', strlen($options['travelx_user_password'])),
            'travelx_access'            => get_option('travelx_access'),
            'travelx_user_ip_address'   => get_option('travelx_user_ip_address'),
            'travelx_required_currency' => get_option('travelx_required_currency'),
            'travelx_hotel_api'         => get_option('travelx_hotel_api'),
            'travelx_flight_api'        => get_option('travelx_flight_api'),
            'stripe_api_key'        => get_option('stripe_api_key'),
            'stripe_api_publish_key'        => get_option('stripe_api_publish_key'),
            'crypto_user_id'        => get_option('crypto_user_id'),
            'crypto_api_key'        => get_option('crypto_api_key'),
            'crypto_auth_url'        => get_option('crypto_auth_url'),
            'crypto_payment_url'        => get_option('crypto_payment_url'),
        ];

        return new WP_REST_Response([
            'success' => true,
            'settings' => $options
        ], 200);
    }


function fetch_most_popular_hotels_listings() {
        $travelxHotelApi = get_option('travelx_hotel_api');

        $api_url = $travelxHotelApi.'/hotel_search';
        $travelx_hotel_ids = get_option('travelx_hotel_ids');
        if ($travelx_hotel_ids) {
        // Convert the string of hotel IDs to an array if it's a comma-separated string
        if (is_string($travelx_hotel_ids)) {
            $travelx_hotel_ids = explode(',', $travelx_hotel_ids);
        }

        // Clean up the array (trim spaces, ensure all values are numeric)
        $travelx_hotel_ids = array_map('trim', $travelx_hotel_ids);
        $travelx_hotel_ids = array_filter($travelx_hotel_ids, 'is_numeric');

        // Ensure the values are unique
        $travelx_hotel_ids = array_unique($travelx_hotel_ids);
    }
//echo "<pre>"; print_r($travelx_hotel_ids);
    // Prepare the body for the API request with dynamic hotelCodes
    $body = [
        "user_id" => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access" => get_option('travelx_access'),
        "ip_address" => get_option('travelx_user_ip_address'),
        "checkin" => date('Y-m-d'),
        "checkout" => date('Y-m-d', strtotime('+4 day')),
        "hotelCodes" => $travelx_hotel_ids, // Dynamically fetched hotel IDs
        "occupancy" => [
            [
                "room_no" => 1,
                "adult" => 2,
                "child" => 0,
                "child_age" => [0]
            ]
        ],
        "requiredCurrency" => get_option('travelx_required_currency')
    ];

    $response = wp_remote_post($api_url, [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($body),
        'timeout' => 20
    ]);

    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message();
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    // Display response as pretty JSON
  // echo '<pre>' . print_r($data, true) . '</pre>';
      return [
         'status' => isset($data['status']) ? $data['status'] : [],
        'itineraries' => isset($data['itineraries']) ? $data['itineraries'] : []
     ];
}

  // Custom Api For Home page 
add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/homepage-data/', [
        'methods'  => 'GET',
        'callback' => 'get_homepage_data_through_api',
        'permission_callback' => '__return_true',
    ]);
});

function get_homepage_data_through_api() {
    $homepageData = [];

    // List of post types to include
    $post_types = ['special_offers', 'special_flight', 'trendingdestinations', 'faq'];

    foreach ($post_types as $post_type) {
        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $post_data = [
                    'id'            => get_the_ID(),
                    'title'         => get_the_title(),
                    'excerpt'       => get_the_excerpt(),
                    'content'       => get_the_content(),
                    'image'         => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'link'          => get_permalink(),
                    'custom_fields' => function_exists('get_fields') ? get_fields(get_the_ID()) : [],
                ];

                // ✅ Add FAQ categories if it's the `faq` post type
                if ($post_type === 'faq') {
                    $terms = get_the_terms(get_the_ID(), 'faq_category');
                    if (!is_wp_error($terms) && !empty($terms)) {
                        $post_data['categories'] = array_map(function($term) {
                            return [
                                'id'   => $term->term_id,
                                'name' => $term->name,
                                'slug' => $term->slug,
                            ];
                        }, $terms);
                    } else {
                        $post_data['categories'] = [];
                    }
                }

                $homepageData[$post_type][] = $post_data;
            }
            wp_reset_postdata();
        } else {
            $homepageData[$post_type] = [];
        }
    }

    // Include hotel listings if available
    if (function_exists('fetch_most_popular_hotels_listings')) {
        $hotelData = fetch_most_popular_hotels_listings();
        $homepageData['popular_hotels'] = $hotelData;
    } else {
        $homepageData['popular_hotels'] = [];
    }
    $homepageData['success']= true;
    return $homepageData;
}

/*For Cms Page Api Start*/
add_action('rest_api_init', function () {
        register_rest_route('custom/v1', '/page/(?P<slug>[a-zA-Z0-9-_]+)', [
            'methods'  => 'GET',
            'callback' => 'get_custom_page_data',
            'permission_callback' => '__return_true',
        ]);
    });

    function get_custom_page_data($data) {
        $slug = sanitize_text_field($data['slug']);

        $page = get_page_by_path($slug, OBJECT, 'page');

        if (!$page) {
            return new WP_Error('no_page', 'Page not found', ['status' => 404]);
        }

        return [
            'title'   => get_the_title($page->ID),
            'content' => apply_filters('the_content', $page->post_content),
        ];
    }
/*For Cms Page Api End*/

function getTravelDashboardData() {
    global $wpdb;
    
    // Fetch hotel booking data
    $hotelResults = $wpdb->get_results(
       $wpdb->prepare("
            SELECT * 
            FROM hotel_booking_details h
            INNER JOIN (
                SELECT MAX(id) as latest_id
                FROM hotel_booking_details
                GROUP BY transaction_id
            ) latest ON h.id = latest.latest_id
            ORDER BY h.id DESC
        "),
        ARRAY_A
    );

    // Fetch flight booking data
    $flightResults = $wpdb->get_results(
       $wpdb->prepare("
            SELECT * 
            FROM flight_booking_details f
            INNER JOIN (
                SELECT MAX(id) as latest_id
                FROM flight_booking_details
                GROUP BY transaction_id
            ) latest ON f.id = latest.latest_id
            ORDER BY f.id DESC
        "),
        ARRAY_A
    );

    $currentDate = date('Y-m-d');
    $previousDate = date('Y-m-d', strtotime('-30 days')); // Replace X with your 

    $flightWithHotelResults = $wpdb->get_results(
         $wpdb->prepare("
            SELECT * FROM (
                (SELECT 
                    h.id,
                    h.transaction_id,
                    h.created_at,
                    h.location AS booking_info,
                    'hotel' AS booking_type
                 FROM hotel_booking_details h
                 INNER JOIN (
                     SELECT MAX(id) AS latest_id
                     FROM hotel_booking_details
                     WHERE created_at BETWEEN %s AND %s
                     GROUP BY transaction_id
                 ) latest_h ON h.id = latest_h.latest_id
                 WHERE h.created_at BETWEEN %s AND %s
                )
                UNION ALL
                (SELECT 
                    f.id,
                    f.transaction_id,
                    f.created_at,
                    f.destination_to AS booking_info,
                    'flight' AS booking_type
                 FROM flight_booking_details f
                 INNER JOIN (
                     SELECT MAX(id) AS latest_id
                     FROM flight_booking_details
                     WHERE created_at BETWEEN %s AND %s
                     GROUP BY transaction_id
                 ) latest_f ON f.id = latest_f.latest_id
                 WHERE f.created_at BETWEEN %s AND %s
                )
            ) AS combined_results
            WHERE combined_results.created_at BETWEEN %s AND %s
            ORDER BY combined_results.id DESC
        ",
        $oneMonthAgo, $currentDate, $previousDate, $currentDate,

        $oneMonthAgo, $currentDate, $previousDate, $currentDate,
        // outer final filter
        $previousDate, $currentDate
    ),
        ARRAY_A
    );
    // Calculate total price for hotel bookings
    $hotelTotalPrice = 0;
    foreach ($hotelResults as $hotel) {
        $hotelTotalPrice += $hotel['price'];
    }

    // Calculate total price for flight bookings
    $flightTotalPrice = 0;
    foreach ($flightResults as $flight) {
        $flightTotalPrice += $flight['amount'];
    }

    $totalRevenue = $hotelTotalPrice + $flightTotalPrice;

    // Prepare the response with dynamic values
    $response = array(
        'hotelbooking' => $hotelResults,
        'flightbooking' => $flightResults,
        'flightwithbooking' => $flightWithHotelResults,
        'price_currency' => get_option('travelx_required_currency'),
        'hotelTotalPrice' => $hotelTotalPrice,
        'flightTotalPrice' => $flightTotalPrice,
        'totalRevenue' => $totalRevenue,
    );

    return $response;
}
   
add_action('admin_menu', 'register_travel_dashboard_menu');

function register_travel_dashboard_menu() {
   add_menu_page(
    'Travel Dashboard',
    'Travel Dashboard',
    'manage_options',
    'travel-dashboard',
    'travel_dashboard_page_callback', // 👈 this must match the function name
    'dashicons-palmtree',
    6
);

}

function travel_dashboard_page_callback() {
    $template_path = get_template_directory() . '/admin/travel-dashboard-template.php';

    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo '<div class="notice notice-error"><p>Template not found.</p></div>';
    }
}

/*For wishlist code*/
function enqueue_wishlist_scripts() {
    wp_enqueue_script('wishlist-script', get_template_directory_uri() . '/js/wishlist.js', [], null, true);
    
    wp_localize_script('wishlist-script', 'wishlist_ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wishlist_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_wishlist_scripts');

function is_item_in_wishlist($customer_id, $item_id, $type , $name) {
    global $wpdb;
    $table = 'travel_wishlist';

    if ($type === 'hotel') {
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE customer_id = %d AND hotel_id = %d", $customer_id, $item_id));
    } else {
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE customer_id = %d AND flight_id = %d", $customer_id, $item_id));
    }
}

add_action('wp_ajax_add_to_wishlist', 'handle_add_to_wishlist');
add_action('wp_ajax_remove_from_wishlist', 'handle_remove_from_wishlist');


function handle_add_to_wishlist() {
    check_ajax_referer('wishlist_nonce');

    global $wpdb;
    $table = 'travel_wishlist';

    $type = sanitize_text_field($_POST['type']);
    $item_id = intval($_POST['item_id']);
    $customer_id = intval($_POST['customer_id']);
    $customer_email = sanitize_email($_POST['customer_email']);
    $hotel_name = sanitize_text_field($_POST['hotel_name']);

    // Check if the item is already in the wishlist
    if ($type === 'hotel') {
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE customer_id = %d AND hotel_id = %d", $customer_id, $item_id));
    } else {
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE customer_id = %d AND flight_id = %d", $customer_id, $item_id));
    }

    // If the item already exists in the wishlist, return error
    if ($exists > 0) {
        wp_send_json_error('Already in wishlist');
    }

    // Otherwise, add to the wishlist
    $data = [
        'type' => $type,
        'customer_id' => $customer_id,
        'customer_email' => $customer_email,
        'hotel_name' => $hotel_name,
        'location' => '', // Add location if needed
        'created_at' => current_time('mysql')
    ];

    if ($type === 'hotel') {
        $data['hotel_id'] = $item_id;
    } else {
        $data['flight_id'] = $item_id;
    }

    $wpdb->insert($table, $data);

    wp_send_json_success('Added to wishlist');
}


function handle_remove_from_wishlist() {
    check_ajax_referer('wishlist_nonce');

    global $wpdb;
    $table = 'travel_wishlist';

    $type = sanitize_text_field($_POST['type']);
    $item_id = intval($_POST['item_id']);
    $customer_id = intval($_POST['customer_id']);

    if ($type === 'hotel') {
        $result = $wpdb->delete($table, ['customer_id' => $customer_id, 'hotel_id' => $item_id]);
    } else {
        $result = $wpdb->delete($table, ['customer_id' => $customer_id, 'flight_id' => $item_id]);
    }

    // If the item was removed, return success; otherwise, return an error
    if ($result) {
        wp_send_json_success('Removed from wishlist');
    } else {
        wp_send_json_error('Item not found or could not be removed');
    }
}
function fetch_Whislist_hotels_listings($hotelid) {

    $travelxHotelApi = get_option('travelx_hotel_api');

    $api_url = $travelxHotelApi.'/hotel_search';
    if (is_string($hotelid)) {
            $hotelCodes = explode(',', $hotelid);
        } else {
            $hotelCodes = array_map('strval', $hotelid);
        }

    // Prepare the body for the API request with dynamic hotelCodes
    $body = [
        "user_id" => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access" => get_option('travelx_access'),
        "ip_address" => get_option('travelx_user_ip_address'),
        "checkin" => date('Y-m-d'),
        "checkout" => date('Y-m-d', strtotime('+1 day')),
        "hotelCodes" => $hotelCodes, // Dynamically fetched hotel IDs
        "occupancy" => [
            [
                "room_no" => 1,
                "adult" => 2,
                "child" => 0,
                "child_age" => [0]
            ]
        ],
        "requiredCurrency" => get_option('travelx_required_currency')
    ];
 //echo json_encode($body);
    
    $response = wp_remote_post($api_url, [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($body),
        'timeout' => 20
    ]);

    if (is_wp_error($response)) {
        return 'Error: ' . $response->get_error_message();
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    // Display response as pretty JSON
  // echo '<pre>' . print_r($data, true) . '</pre>';
      return [
         'status' => isset($data['status']) ? $data['status'] : [],
        'itineraries' => isset($data['itineraries']) ? $data['itineraries'] : []
     ];
}

add_action('wp_ajax_remove_admin_wishlist_item', 'remove_admin_wishlist_item_callback');

function remove_admin_wishlist_item_callback() {
    global $wpdb;

    $item_id = intval($_POST['item_id']);
    $type = sanitize_text_field($_POST['type']);
    $name = sanitize_text_field($_POST['name']);
    $customer_id = intval($_POST['customer_id']);

    $table = 'travel_wishlist';

    $deleted = $wpdb->delete(
        $table,
        [
            'customer_id' => $customer_id,
            'type' => $type,
            'hotel_name' => $name
        ],
        [
            '%d', '%s', '%s'
        ]
    );

    if ($deleted !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }

    wp_die();
}
function send_custom_hotel_booking_email($user_email, $user_name, $bookingReference, $transactionId, $checkInDate, $checkOutDate, $supplierConfirmationNum) {
    $to = $user_email;
    $subject = 'Travel Hotel Booking Confirmation';
    
    // Construct the email content with dynamic values
    $message = '
    <html>
    <body>
        <div class="container registration-form" style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background: #ffffff; border: 1px solid #ddd;">
            <div class="registration-template">
                <div class="logo-register-section" style="text-align: center; margin-bottom: 20px;">
                    <img style="max-width: 150px;" src="https://travel.nexdew.com/wp-content/uploads/2025/03/logo-e1743083450840-257x300.png" alt="Travel Logo" />
                </div>
                <p style="font-size: 16px; color: #555;">Hi <strong>' . $user_name . '</strong>,</p>
                <p style="font-size: 16px; color: #555;">Welcome to <strong>Travel.NexDew</strong>! Your hotel has been booked successfully. Below are your booking details:</p>
                
                <table class="details-table" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <th style="text-align: left; padding: 8px; background: #f5f5f5; border: 1px solid #ddd;">Booking Reference</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $bookingReference . '</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px; background: #f5f5f5; border: 1px solid #ddd;">Transaction ID</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $transactionId . '</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px; background: #f5f5f5; border: 1px solid #ddd;">Check-In</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $checkInDate . '</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px; background: #f5f5f5; border: 1px solid #ddd;">Check-Out</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $checkOutDate . '</td>
                        </tr>
                        <tr>
                            <th style="text-align: left; padding: 8px; background: #f5f5f5; border: 1px solid #ddd;">Supplier Confirmation Num</th>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $supplierConfirmationNum . '</td>
                        </tr>
                    </tbody>
                </table>
                
                <p style="font-size: 16px; color: #555; margin-top: 20px;">If you did not register or need help, please contact us at <strong>support@travel.com</strong>.</p>

                <div class="footer" style="margin-top: 30px; text-align: center; font-size: 14px; color: #888;">
                    support@travel.com | <a style="color: #1a73e8;" href="https://travel.nexdew.com/">https://travel.nexdew.com/</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    ';
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $message, $headers);
}

function send_custom_flight_booking_email($user_email, $user_name, $booking_id) {
    $to = $user_email;
    $subject = 'Travel Flight Booking';

    $message = '
    <html>
    <body>
        <div class="container registration-form" style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; background: #ffffff; border: 1px solid #ddd;">
        <div class="registration-template">
        <div class="logo-register-section" style="text-align: center; margin-bottom: 20px;">
            <img style="max-width: 150px;" src="https://travel.nexdew.com/wp-content/uploads/2025/03/logo-e1743083450840-257x300.png" alt="Travel Logo" />
        </div>
        <p style="font-size: 16px; color: #555;">Hi <strong>{{user_name}}</strong>,</p>
        <p style="font-size: 16px; color: #555;">Welcome to <strong>Travel.NexDew</strong>! Your Flight has been Booked successfully. Below are details for travel Flight booking:</p>

        <table class="details-table" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tbody>
        <tr>
        <th style="text-align: left; padding: 8px; background: #f5f5f5; border: 1px solid #ddd;">Booking ID</th>
        <td style="padding: 8px; border: 1px solid #ddd;">{{booking_id}}</td>
        </tr>
        </tbody>
        </table>
        <p style="font-size: 16px; color: #555; margin-top: 20px;">If you did not register or need help, please contact us at <strong>support@travel.com</strong>.</p>

        <div class="footer" style="margin-top: 30px; text-align: center; font-size: 14px; color: #888;">
        support@travel.com | <a style="color: #1a73e8;" href="https://travel.nexdew.com/">https://travel.nexdew.com/</a>
        </div>
        </div>
        </div>
    </body>
    </html>
    ';

    // Replace placeholders with actual values
    $message = str_replace(
        array('{{user_name}}', '{{booking_id}}'),
        array(esc_html($user_name), esc_html($booking_id)),
        $message
    );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $message, $headers);
}


// add_action('rest_api_init', function () {
//     register_rest_route('custom-api/v1', '/airport-suggestions', [
//         'methods'  => 'GET',
//         'callback' => 'rest_get_airport_suggestions',
//         'permission_callback' => '__return_true',
//         'args' => [
//             'term' => [
//                 'required' => true,
//                 'sanitize_callback' => 'sanitize_text_field'
//             ]
//         ]
//     ]);
// });
add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/airport-suggestions', [
        'methods'  => ['GET', 'POST'],
        'callback' => 'rest_get_airport_suggestions',
        'permission_callback' => '__return_true',
        'args' => [
            'term' => [
                'required' => false, // Make it optional since you're manually handling fallback
                'sanitize_callback' => 'sanitize_text_field'
            ]
        ]
    ]);
});

//Airport list api
function rest_get_airport_suggestions(WP_REST_Request $request) {
    global $wpdb;

    // Try to get 'term' from query or body
    $term = sanitize_text_field($request->get_param('term'));

    // If not found in params, try to get it from raw body JSON
    if (empty($term)) {
        $body = $request->get_body();
        $data = json_decode($body, true);
        if (isset($data['term'])) {
            $term = sanitize_text_field($data['term']);
        }
    }

    // Don't search if less than 2 characters
    if (strlen($term) < 2) {
        return new WP_REST_Response([], 200);
    }

    // Query DB
    $results = $wpdb->get_results(
        $wpdb->prepare("
            SELECT airport_name, airport_code, city, country, latitude, longitude
            FROM airport_list
            WHERE airport_name LIKE %s OR city LIKE %s OR airport_code LIKE %s
            LIMIT 10
        ",
            '%' . $wpdb->esc_like($term) . '%',
            '%' . $wpdb->esc_like($term) . '%',
            '%' . $wpdb->esc_like($term) . '%'
        ),
        ARRAY_A
    );

    // Format response
    $suggestions = array_map(function ($airport) {
        return [
            'label' => sprintf("%s, %s, %s, %s",
                $airport['airport_name'],
                $airport['airport_code'],
                $airport['city'],
                $airport['country']
            ),
            'value' => $airport['airport_code'],
            'city' => $airport['city'],
            'country' => $airport['country'],
            'latitude' => $airport['latitude'],
            'longitude' => $airport['longitude']
        ];
    }, $results);

    return new WP_REST_Response($suggestions, 200);
}



/*wishlist api*/
add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/wishlist/add', [
        'methods'  => WP_REST_Server::CREATABLE, // Better than 'POST'
        'callback' => 'api_add_to_wishlist',
        'permission_callback' => '__return_true',
    ]);
});
add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/wishlist/remove', [
        'methods'  => 'POST',
        'callback' => 'api_remove_from_wishlist',
        'permission_callback' => '__return_true',
    ]);
});


function api_add_to_wishlist($request) {
    global $wpdb;
    $table = 'travel_wishlist';

    $params = $request->get_json_params();

    $type = sanitize_text_field($params['type']);
    $item_id = intval($params['hotel_id']);
    $customer_id = intval($params['customer_id']);
    $customer_email = sanitize_email($params['customer_email']);
    $hotel_name = sanitize_text_field($params['hotel_name']);

    // Check for existing wishlist item
    $exists = is_item_in_wishlist($customer_id, $item_id, $type, $hotel_name);
    if ($exists) {
        return new WP_REST_Response(['message' => 'Already in wishlist'], 200);
    }

    $data = [
        'type' => $type,
        'customer_id' => $customer_id,
        'customer_email' => $customer_email,
        'hotel_name' => $hotel_name,
        'location' => '', // You can expand this
        'created_at' => current_time('mysql')
    ];

    if ($type === 'hotel') {
        $data['hotel_id'] = $item_id;
    } else {
        $data['flight_id'] = $item_id;
    }

    $wpdb->insert($table, $data);

    return new WP_REST_Response(['message' => 'Added to wishlist'], 200);
}

function api_remove_from_wishlist($request) {
    global $wpdb;
    $table = 'travel_wishlist';

    $params = $request->get_json_params();

    $type = sanitize_text_field($params['type']);
    $item_id = intval($params['hotel_id']);
    $customer_id = intval($params['customer_id']);

    if ($type === 'hotel') {
        $wpdb->delete($table, ['customer_id' => $customer_id, 'hotel_id' => $item_id]);
    } else {
        $wpdb->delete($table, ['customer_id' => $customer_id, 'flight_id' => $item_id]);
    }

    return new WP_REST_Response(['message' => 'Removed from wishlist'], 200);
}
function api_get_wishlist_by_customer($request) {
    global $wpdb;
    $table = 'travel_wishlist';

    $customer_id = intval($request['customer_id']);

    if (!$customer_id) {
        return new WP_REST_Response(['message' => 'Invalid customer ID'], 400);
    }

    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table WHERE customer_id = %d", $customer_id),
        ARRAY_A
    );

    return new WP_REST_Response([
        'message' => 'Wishlist fetched',
        'data'    => $results
    ], 200);
}

add_action('rest_api_init', function () {
    register_rest_route('custom-api/v1', '/wishlist/list/(?P<customer_id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'api_get_wishlist_by_customer',
        'permission_callback' => '__return_true',
    ]);
});

function register_my_menus() {
  register_nav_menus(
    array(
      'primary' => __('Primary Menu'),
      'footer' => __('Footer Menu')
    )
  );
}
add_action('init', 'register_my_menus');


/*=================================For Car Rental Code Start==============================================*/
// Handle car destination suggestion request
add_action('wp_ajax_get_car_rental_suggestions', 'get_car_rental_suggestions');
add_action('wp_ajax_nopriv_get_car_rental_suggestions', 'get_car_rental_suggestions');

function get_car_rental_suggestions() {
    global $wpdb;

    $term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';

    if (strlen($term) < 2) {
        wp_send_json([]);
        wp_die();
    }

    $like = '%' . $wpdb->esc_like($term) . '%';

    $results = $wpdb->get_results(
        $wpdb->prepare("
            SELECT id, location_name, location_code, city, country_code
            FROM car_destination
            WHERE location_name LIKE %s OR city LIKE %s OR location_code LIKE %s
            ORDER BY location_name
            LIMIT 10
        ", $like, $like, $like),
        ARRAY_A
    );

    $suggestions = [];
    foreach ($results as $row) {
        $label = sprintf('%s, %s, %s', $row['location_name'], $row['city'], $row['country_code']);
        $suggestions[] = [
            'label' => $label,
            'value' => $label,
            'id' => $row['id'],
        ];
    }

    wp_send_json($suggestions);
    wp_die();
}


function enqueue_car_rental_autocomplete_script() {
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_script(
        'car-rental-autocomplete',
        get_template_directory_uri() . '/js/car-rental-autocomplete.js',
        ['jquery', 'jquery-ui-autocomplete'],
        null,
        true
    );

    wp_localize_script('car-rental-autocomplete', 'airportSearch', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_car_rental_autocomplete_script');


function getsearchCarRentalList($carPickupLocation, $carDropoffLocation, $pickupTime, $dropoffTime, $pickupDate, $dropoffDate, $selectedRatings = [], $sortBy = '') {
    // Prepare API payload
    $payload = [
        "user_id"       => get_option('travelx_user_id'),
        "user_password" => get_option('travelx_user_password'),
        "access"        => get_option('travelx_access'),
        "ip_address"    => get_option('travelx_user_ip_address'),
        'pickup_id'     => $carPickupLocation,
        'dropoff_id'    => $carDropoffLocation,
        'pickup_date'   => $pickupDate,
        'pickup_time'   => $pickupTime,
        'dropoff_date'  => $dropoffDate,
        'dropoff_time'  => $dropoffTime,
        'driver_age'    => "25",
        'country_res'   => "IN",
        'currency'      => get_option('travelx_required_currency'),
        'ratings'       => $selectedRatings, // Send selected ratings as an array
        'sort_by'       => $sortBy, // Sorting criteria (e.g., price-low-high, rating-high-low)
    ];

    // Send API request
    $response = wp_remote_post('https://travelnext.works/api/carsv3-test/search', [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($payload),
        'timeout' => 15,
    ]);

    // Handle request errors
    if (is_wp_error($response)) {
        error_log('TravelNext API Error: ' . $response->get_error_message());
        return new WP_Error('api_error', 'Unable to reach TravelNext API', [
            'status' => 500,
            'details' => $response->get_error_message()
        ]);
    }

    // Extract body and decode
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Validate decoded response
    if (json_last_error() !== JSON_ERROR_NONE || empty($data) || !is_array($data)) {
        error_log('TravelNext API returned invalid JSON or empty data.');
        return new WP_Error('api_error', 'Invalid response from TravelNext API', ['status' => 502]);
    }

    // Optionally sort and filter the data if not handled by the API
    if ($sortBy) {
        usort($data['data'], function($a, $b) use ($sortBy) {
            switch ($sortBy) {
                case 'price-low-high':
                    return $a['fees']['rateTotalAmount'] - $b['fees']['rateTotalAmount'];
                case 'price-high-low':
                    return $b['fees']['rateTotalAmount'] - $a['fees']['rateTotalAmount'];
                case 'rating-low-high':
                    return round($a['vendor']['reviewsOverall']) - round($b['vendor']['reviewsOverall']);
                case 'rating-high-low':
                    return round($b['vendor']['reviewsOverall']) - round($a['vendor']['reviewsOverall']);
                default:
                    return 0; // No sorting
            }
        });
    }

    if (!empty($selectedRatings)) {
        $data['data'] = array_filter($data['data'], function($car) use ($selectedRatings) {
            $rating = round($car['vendor']['reviewsOverall']);
            return in_array($rating, $selectedRatings);
        });
    }

    // Return the filtered and sorted response
    return $data;
}

function getCarRentalDetail($session_id, $reference_id){

    $payload = [
   
        'session_id' => $session_id,
        'reference_id'=> $reference_id,
    ];

     $response = wp_remote_post('https://travelnext.works/api/carsv3-test/rental_condition_details', [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($payload),
    ]);

     if (is_wp_error($response)) {
        return new WP_Error('api_error', 'Unable to reach travelnext API', ['status' => 500]);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

// echo "<pre>"; print_r($data); die;

 return !empty($data) ? $data : ['error' => 'Invalid API response'];


}

/*for car api*/
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/car-rental-search', [
        'methods'  => 'POST',
        'callback' => 'custom_car_rental_search',
        'permission_callback' => '__return_true', // Allow public access; secure this if needed
    ]);
});

function custom_car_rental_search($request) {
    $params = $request->get_json_params();

    $payload = [
        "user_id"       => "bookatravel_testAPI",
        "user_password" => "bookatravelTest@2025",
        "ip_address"    => "106.219.165.128",
        "access"        => "Test",
        "pickup_id"     => $params['pickup_id'] ?? '',
        "dropoff_id"    => $params['dropoff_id'] ?? '',
        "pickup_date"   => $params['pickup_date'] ?? '',
        "pickup_time"   => $params['pickup_time'] ?? '',
        "dropoff_date"  => $params['dropoff_date'] ?? '',
        "dropoff_time"  => $params['dropoff_time'] ?? '',
        "driver_age"    => $params['driver_age'] ?? '25',
        "country_res"   => $params['country_res'] ?? 'IN',
        "currency"      => $params['currency'] ?? 'USD',
    ];

    $response = wp_remote_post('https://travelnext.works/api/carsv3-test/search', [
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => wp_json_encode($payload),
        'timeout' => 20,
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', $response->get_error_message(), ['status' => 500]);
    }

    $body = wp_remote_retrieve_body($response);
    $code = wp_remote_retrieve_response_code($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
        return new WP_Error('invalid_response', 'Invalid response from TravelNext API', [
            'status' => $code,
            'raw' => $body,
        ]);
    }

    return rest_ensure_response($data);
}

/*==============================For Car Rental Code End=====================================*/
function getCityNameByAirPortCode($airportCode) {
    global $wpdb;

    // Sanitize the input
    //$airportCode = strtoupper(trim($airportCode));
    $airportCode = strtoupper(trim($airportCode ?? ''));

    // Replace with your actual table name if it uses a prefix
    $table_name = 'airport_list';

    // Query the database
    $city = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT city FROM {$table_name} WHERE airport_code = %s LIMIT 1",
            $airportCode
        )
    );

    return $city ?: 'Unknown City';
}
function getAirPortNameByAirPortCode($airportCode) {
    global $wpdb;

    // Sanitize the input
    $airportCode = strtoupper(trim($airportCode));

    // Replace with your actual table name if it uses a prefix
    $table_name = 'airport_list';

    // Query the database
    $airportName = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT airport_name FROM {$table_name} WHERE airport_code = %s LIMIT 1",
            $airportCode
        )
    );

    return $airportName ?: 'Unknown Airport Name';
}

function validateFlightFareMethod($sessionId, $fareSourceCode) {
    $url = 'https://travelnext.works/api/aeroVE5/revalidate';

    $body = [
        'session_id' => $sessionId,
        'fare_source_code' => $fareSourceCode,
    ];

    $response = wp_remote_post($url, [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'body' => wp_json_encode($body),
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        return ['error' => $response->get_error_message()];
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return [
        'status' => $code,
        'response' => $data,
    ];
}

?>
