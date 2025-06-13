<div class="footer-bg">
    <img src="<?php echo get_template_directory_uri(); ?>/photos/bg.svg" alt="" style="width: 100%; height: 100%;">
</div>

<footer class="footer">
    <div class="container footer-home-page-section">
        <div class="footer-section-paragraph">
            <p class="text-start accept-credit">
                <?php echo get_theme_mod('footer_payment_text', 'We accept Credit Card, Debit Card and Cryptocurrency payments.'); ?>
            </p>
            <div class="text-end mb-4 combo-section-footer">
                <img src="<?php echo get_template_directory_uri(); ?>/photos/visa.png" alt="Visa">
                <img src="<?php echo get_template_directory_uri(); ?>/photos/mastercard.png" alt="MasterCard">
                <img src="<?php echo get_template_directory_uri(); ?>/photos/Americanexpress.png" alt="American Express">
                <img src="<?php echo get_template_directory_uri(); ?>/photos/pay.png" alt="Crypto Pay">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 col-lg-5">
                <div class="footer-logo">
                    <?php 
                    if (has_custom_logo()) {
                        the_custom_logo(); 
                    } else { ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/photos/logo.png" alt="<?php bloginfo('name'); ?>" height="40">
                    <?php } ?>
                    <h5 class="booking-travel"><?php bloginfo('name'); ?></h5>
                </div>
                <p class="footer-text-main">
                    <?php echo get_theme_mod('footer_about_text', 'Lorem ipsum dolor sit amet. The graphic and typographic operators know this well.'); ?>
                </p>
                <div class="social-icons">
                    <a href="<?php echo get_theme_mod('footer_facebook_link', get_option('site_facebook_link')); ?>"><i class="fab fa-facebook"></i></a>
                    <a href="<?php echo get_theme_mod('footer_twitter_link', get_option('site_twiter_link')); ?>"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo get_theme_mod('footer_linkedin_link', get_option('site_linkdin_link')); ?>"><i class="fab fa-linkedin"></i></a>
                    <a href="<?php echo get_theme_mod('footer_instagram_link', get_option('site_insta_link')); ?>"><i class="fab fa-instagram"></i></a>
                    <a href="<?php echo get_theme_mod('footer_youtube_link', get_option('site_youtube_link')); ?>"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="col-4 col-md-4 col-lg-2  quick-view-section-home-page-sections">
                <h5>Company</h5>
                <a href="/about-us" class="footer-quick-view-section mb-2">About Us</a><br>
                <a href="" class="footer-quick-view-section mb-2">Contact Us</a><br>
                <a href="/travel-guide" class="footer-quick-view-section mb-2">Travel Guides</a><br>
                <a href="/data-policy" class="footer-quick-view-section mb-2">Data Policy</a><br>
                <a href="/cookies-policy" class="footer-quick-view-section mb-2">Cookie Policy</a><br>
                <a href="#" class="footer-quick-view-section mb-2">Legal</a>
            </div>

            <div class="col-4 col-md-4 col-lg-2  ">
                <h5>Support</h5>
                <a href="#" class="footer-quick-view-section mb-2">Get in Touch</a><br>
                <a href="#" class="footer-quick-view-section mb-2">Help Center</a><br>
                <a href="#" class="footer-quick-view-section mb-2">Live Chat</a><br>
                <a href="#" class="footer-quick-view-section mb-2">How It Works</a>
            </div>

            <div class="col-md-4 col-lg-2">
                <h5 class="newsletter-page-tittle">Newsletter</h5>
                <p class="news-letter-text-suscribe">Subscribe to the free newsletter and stay up to date</p>

                <form method="post" action="">
                    <div class="input-group newsletter"
                        style="border-radius: 25px; overflow: hidden; background: white; padding: 5px;">
                        <input type="email" name="newsletter_email" class="form-control border-0"
                            placeholder="Your email address" aria-label="Email Address"
                            style="border-radius: 25px; padding-left: 15px; padding-right: 10px; height: 40px;" required>
                        <button type="submit" class="btn btn-primary border-0" name="submit_newsletter">Send</button>
                    </div>
                </form>

                <?php
                // Handle form submission
                if (isset($_POST['submit_newsletter'])) {
                    $email = sanitize_email($_POST['newsletter_email']);
                    
                    if (!empty($email) && is_email($email)) {
                        echo '<p class="text-success mt-2">Thanks for subscribing!</p>';
                        // Here, you could save to DB or send an email, etc.
                    } else {
                        echo '<p class="text-danger mt-2">Please enter a valid email address.</p>';
                    }
                }
                ?>
            </div>

            <div class="useful-links">
                <h6 class="useful-links-text-home-page">Useful Links</h6>
                <p class="useful-paragraph-links-home">
                    Accommodations | Countries | Regions | Cities | Landmarks | Airports | Hotel Chains | Hotel Themes |
                    Flights From Countries | Flights From Regions | Flights From Cities |
                    Flights From Airports | Flights to Countries | Flights to Regions | Flights to Cities | Flights to
                    Airports |
                    Flights To Countries by Airlines | Flights To Cities by Airlines
                </p>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'container' => false,
                    'menu_class' => 'footer-nav',
                    'fallback_cb' => false
                ));
                ?>
            </div>

            <hr>
            <div class="text-center copyright-book-travel">
                © <?php echo date("Y"); ?> <?php bloginfo('name'); ?>. All rights reserved.
            </div>
        </div>
</footer>
<script>
jQuery(document).ready(function ($) {
    // Toggle Between Login & Registration
    $('#showRegister').click(function (e) {
        e.preventDefault();
        $('#loginFormContainer').addClass('d-none');
        $('#registerFormContainer').removeClass('d-none');
    });

    $('#showLogin').click(function (e) {
        e.preventDefault();
        $('#registerFormContainer').addClass('d-none');
        $('#loginFormContainer').removeClass('d-none');
    });

    // AJAX Registration
    $('#registerForm').submit(function (e) {
        e.preventDefault();

        var firstName = $('#firstName').val();
        var lastName = $('#lastName').val();
        var email = $('#registerEmail').val();
        var password = $('#registerPassword').val();

        $.ajax({
            type: 'POST',
            url: ajax_auth_object.ajax_url,
            data: {
                action: 'custom_ajax_register',
                security: ajax_auth_object.register_nonce,
                first_name: firstName,
                last_name: lastName,
                email: email,
                password: password
            },
            beforeSend: function () {
                $('#registerForm button').text('Registering...').attr('disabled', true);
            },
            success: function (response) {
                var data = JSON.parse(response);
                alert(data.message);
                if (data.status) {
                    $('#showLogin').click(); // Switch to Login Form after success
                }
            },
            complete: function () {
                $('#registerForm button').text('Register').attr('disabled', false);
            }
        });
    });

    // AJAX Login
    $('#loginForm').submit(function (e) {
        e.preventDefault();

        var email = $('#loginEmail').val();
        var password = $('#loginPassword').val();

        $.ajax({
            type: 'POST',
            url: ajax_auth_object.ajax_url,
            data: {
                action: 'custom_ajax_login',
                security: ajax_auth_object.nonce,
                email: email,
                password: password
            },
            beforeSend: function () {
                $('#loginForm button').text('Logging in...').attr('disabled', true);
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.status) {
                    location.reload(); // Reload page if login is successful
                } else {
                    alert(data.message);
                }
            },
            complete: function () {
                $('#loginForm button').text('Login').attr('disabled', false);
            }
        });
    });
});

/* footer script */

document.addEventListener("DOMContentLoaded", function () {
    // Initialize Swiper for each hotel image slider
    document.querySelectorAll(".hotel-image-slider").forEach((slider, index) => {
        new Swiper(slider, {
            slidesPerView: 3,
            spaceBetween: 10,
            navigation: {
                nextEl: slider.closest(".hotel-card").querySelector(".swiper-button-next"),
                prevEl: slider.closest(".hotel-card").querySelector(".swiper-button-prev"),
            },
            breakpoints: {
                768: { slidesPerView: 4 },
            }
        });
    });

    // Add event listeners to each hotel-card's image slider
    document.querySelectorAll(".hotel-card").forEach((hotelCard) => {
        let swiperWrapper = hotelCard.querySelector(".hotel-image-slider .swiper-wrapper");

        if (swiperWrapper) {
            swiperWrapper.addEventListener("click", function (event) {
                let clickedImg = event.target.closest(".hotel-images");

                if (clickedImg) {
                    // Find the main image inside the same hotel card
                    let mainImage = hotelCard.querySelector(".hotel-image");

                    if (mainImage) {
                        mainImage.src = clickedImg.src;
                    }

                    // Remove active class from all thumbnails in this specific hotel card
                    hotelCard.querySelectorAll(".hotel-images").forEach(img => img.classList.remove("active-thumbnail"));

                    // Add active class to the selected thumbnail
                    clickedImg.classList.add("active-thumbnail");
                }
            });
        }
    });
});

/* flight list js */

document.addEventListener('DOMContentLoaded', function () {
    // Price Range Slider
    const priceRange = document.getElementById('priceRange');
    if (priceRange) {
        priceRange.addEventListener('input', function () {
            const value = this.value;
            // Update price display if needed
        });
    }

    // Initialize any Bootstrap components
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
// Add this to the existing JavaScript file, inside the DOMContentLoaded event listener
// Sort buttons functionality
const sortButtons = document.querySelectorAll('.sort-section .btn-group .btn');
sortButtons.forEach(button => {
    button.addEventListener('click', function () {
        // Remove active class from all buttons
        sortButtons.forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        });

        // Add active class to clicked button
        this.classList.add('active');
        this.classList.add('btn-primary');
        this.classList.remove('btn-outline-primary');

        // Here you would add logic to actually sort the flights
        // based on the selected sorting option
    });
});




document.addEventListener("DOMContentLoaded", function () {
    const sortButtons = document.querySelectorAll('.sort-section .btn-group .btn');
    const flightsContainer = document.getElementById('flights-container');

    sortButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all buttons
            sortButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            // Add active class to clicked button
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-primary');

            // Determine sorting type
            const sortBy = this.classList.contains('lowest-fares') ? 'price' : 'duration';

            // Get all flights and convert to array
            let flights = Array.from(document.querySelectorAll('.flight-card'));

            // Sort flights based on selected criteria
            flights.sort((a, b) => {
                let valA = parseInt(a.getAttribute(`data-${sortBy}`));
                let valB = parseInt(b.getAttribute(`data-${sortBy}`));
                return valA - valB;
            });

            // Reorder flights in the DOM
            flights.forEach(flight => flightsContainer.appendChild(flight));
        });
    });
});
function swapLocations() {
    const fromInput = document.getElementById('from');
    const toInput = document.getElementById('to');

    const temp = fromInput.value;
    fromInput.value = toInput.value;
    toInput.value = temp;
}

// Function to handle the modify search button
function modifySearch() {
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const departure = document.getElementById('departure').value;
    const returnDate = document.getElementById('return').value;
    const travelers = document.getElementById('travelers').value;
    const tripType = document.getElementById('round-trip').checked ? 'Round trip' : 'One way';
}


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    
document.addEventListener("DOMContentLoaded", function () {
    // Special offer tab selection
    function showCustomTab(event, tabId) {
        // Remove active class from all buttons
        document.querySelectorAll(".custom-tab-btn").forEach((btn) => {
            btn.classList.remove("active");
        });

        // Hide all tab contents
        document.querySelectorAll(".custom-tab-content").forEach((tab) => {
            tab.classList.remove("active");
            tab.classList.add("d-none");
        });

        // Show the selected tab
        document.getElementById(tabId).classList.remove("d-none");
        document.getElementById(tabId).classList.add("active");

        // Add active class to the clicked button
        event.currentTarget.classList.add("active");

        // Dynamically update "See All" link
        const viewAllLink = document.getElementById("view-all-link");
        if (tabId === "home-page-hotels-tab") {
            viewAllLink.href = "<?php echo site_url('/special-offer-view-all/?type=hotel'); ?>";
        } else if (tabId === "airoplane-section-home-page-tab") {
            viewAllLink.href = "<?php echo site_url('/special-offer-view-all/?type=flight'); ?>";
        }
        
    }

    // Attach event listeners to tab buttons
    document.querySelectorAll(".custom-tab-btn").forEach((btn) => {
        btn.addEventListener("click", function (event) {
            const tabId = this.getAttribute("data-tab");
            showCustomTab(event, tabId);
        });
    });

    // Initialize the correct "See All" link on page load
    // document.getElementById("view-all-link").href = "<?php echo get_post_type_archive_link('special_offers'); ?>";
});
</script>

<?php wp_footer(); ?>
