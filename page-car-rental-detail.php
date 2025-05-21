
<?php 
/**
 * Template Name: Car Rental Details Page
 * Description: A custom page template for special layouts.
 */

get_header(); ?>
<?php
    $session_id = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';
    $reference_id = isset($_GET['reference_id']) ? sanitize_text_field($_GET['reference_id']) : '';

   $carDetail = getCarRentalDetail($session_id, $reference_id);
    $vehicleCharge = $carDetail['data']['vehicleCharge'];
    $pricedCoverages = $carDetail['data']['pricedCoverages'];
    $pricedEquipments = $carDetail['data']['pricedEquipments'];
   $rentalConditions = $carDetail['data']['rentalConditions'];
  //$Cancellation = $rentalConditions['Cancellation and no-show policy'];

  // echo "<pre>+++"; print_r($Cancellation); die;
?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="container car-rental-container">
        <div class="mt-4  car-rental-wrapper car-main-section">
            <!-- Header Section -->
             <div class="car-details-section-main-content">

       
            <div class="car-card-header car-header-section">
                <div class="d-flex gap-4 align-items-center car-header-flex">
                    <div class="car-header-info">
                        <h3 class="marine-tour-text car-tour-title">Kias Picanto</h3>
                        <p class="location-text car-location-text">
                            <span class="efieel-tower-text car-location-main">15th arr. - Eiffel Tower</span>
                            <span class="michale-station-text car-location-sub">
                                <i class="fa-solid fa-chevron-right car-icon-chevron"></i> 6 minutes walk to Charles Michels Station
                            </span>
                        </p>
                    </div>
                    <div class="star-icons car-star-icons">
                        <i class="fa-solid fa-star car-star-icon"></i>
                        <i class="fa-solid fa-star car-star-icon"></i>
                        <i class="fa-solid fa-star car-star-icon"></i>
                        <i class="fa-solid fa-star car-star-icon"></i>
                        <i class="fa-solid fa-star-half-alt car-star-half-icon"></i>
                    </div>
                </div>
                <div class="rating-section car-rating-section">
                    <span class="text-muted excellent-text car-rating-text">
                        Excellent <br>
                        <span class="review-car-review-count">2920 reviews</span>
                    </span>
                    <div class="rating-section-container car-rating-badge-wrapper">
                        <span class="rating-badge car-rating-badge">4.5</span>
                    </div>
                </div>
            </div>

            <!-- Car Slider and Info Section -->
            <div class="car-description-sections-grid">
                <!-- Main Slider -->
                <div class="swiper-container main-slider car-swiper-main-slider">
                    <div class="swiper-wrapper car-swiper-wrapper">
                        <div class="swiper-slide car-swiper-slide">
                            <img src="https://mda.spinny.com/sp-file-system/public/2024-08-29/aee34525c9ca4676bdec4489e3924520/raw/file.JPG?q=85" class="car-main-img" alt="Car Image">
                        </div>
                        <div class="swiper-slide car-swiper-slide">
                            <img src="https://mda.spinny.com/sp-file-system/public/2024-08-29/2a7d0c48c8d447db951b815eadcad482/raw/file.JPG?q=85&w=900&dpr=1.3" class="car-main-img" alt="Car Image">
                        </div>
                        <div class="swiper-slide car-swiper-slide">
                            <img src="https://mda.spinny.com/sp-file-system/public/2024-08-29/3ec4f896f8c845158a443ffec64581b7/raw/file.JPG?q=85&w=900&dpr=1.3" class="car-main-img" alt="Car Image">
                        </div>
                    </div>
                    <!-- Navigation Buttons -->
                    <div class="swiper-button-prev car-swiper-prev"></div>
                    <div class="swiper-button-next car-swiper-next"></div>
                </div>

                <!-- Thumbnails -->
                <div class="car-thumbnail-section mt-2 hotel-thumbnails">
                    <img src="https://mda.spinny.com/sp-file-system/public/2024-08-29/aee34525c9ca4676bdec4489e3924520/raw/file.JPG?q=85" class="car-thumbnail" data-slide="0" alt="Thumbnail">
                    <img src="https://mda.spinny.com/sp-file-system/public/2024-08-29/2a7d0c48c8d447db951b815eadcad482/raw/file.JPG?q=85&w=900&dpr=1.3" class="car-thumbnail" data-slide="1" alt="Thumbnail">
                    <img src="https://mda.spinny.com/sp-file-system/public/2024-08-29/3ec4f896f8c845158a443ffec64581b7/raw/file.JPG?q=85&w=900&dpr=1.3" class="car-thumbnail" data-slide="2" alt="Thumbnail">
                </div>

                <!-- Hotel Info (Pricing) -->
                <div class="hotel-info">
                    <h5 class="car-title mb-0">Standard Queen Room with Balcony</h5>
                    <p class="guest-text-detail-page">2 x Guest | 1 x Room</p>
                    <div class="mt-2 per-night-section">
                        <div>
                            <p class="mb-1 per-night-text">Per Day</p>
                        </div>
                        <span class="price-section">$114</span>
                        <span class="old-price ms-2">$200</span>
                        <p class="text-muted taxes-fees-text">+ $200 taxes & fees</p>
                    </div>
                    <div class="d-flex gap-2 button-section-home">
                        <button class="book-now-button-detail-page car-book-now-button">
                            <p class="book-now-button-text car-book-now-text">Book Now</p>
                        </button>
                    </div>
                </div>
            </div>
      </div>

<div class="car-details">
        <div class="car-section">
            <h4>vehicle Charge</h4>
            <ul>
                  <?php foreach ($vehicleCharge as $sectionTitle => $item): ?>
                    <li>
                        <strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?><br>
                        <strong>Type:</strong> <?php echo htmlspecialchars($item['type']); ?><br>
                        <strong>Amount:</strong> <?php echo htmlspecialchars($item['amount']); ?> <?php echo htmlspecialchars($item['currencyCode']); ?><br>
                        <strong>Included In Rate:</strong> <?php echo $item['includedInRate'] ? 'Yes' : 'No'; ?><br>
                        <strong>Tax Inclusive:</strong> <?php echo $item['taxInclusive'] ? 'Yes' : 'No'; ?>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
<!-- pricedCoverages -->
              <h4>pricedCoverages</h4>
            <ul>
                  <?php foreach ($pricedCoverages as $item): ?>
                    <li>
                        <strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?><br>
                        <strong>Type:</strong> <?php echo htmlspecialchars($item['type']); ?><br>
                        <strong>Amount:</strong> <?php echo htmlspecialchars($item['amount']); ?> <?php echo htmlspecialchars($item['currencyCode']); ?><br>
                        <strong>Included In Rate:</strong> <?php echo $item['includedInRate'] ? 'Yes' : 'No'; ?><br>
                        <strong>Tax Inclusive:</strong> <?php echo $item['taxInclusive'] ? 'Yes' : 'No'; ?>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>

            <!-- pricedEquipments -->
              <h4>pricedEquipments</h4>
            <ul>
                  <?php foreach ($pricedEquipments as $item): ?>
                    <li>
                        <strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?><br>
                        <strong>Type:</strong> <?php echo htmlspecialchars($item['type']); ?><br>
                        <strong>Amount:</strong> <?php echo htmlspecialchars($item['amount']); ?> <?php echo htmlspecialchars($item['currencyCode']); ?><br>
                        <strong>Included In Rate:</strong> <?php echo $item['includedInRate'] ? 'Yes' : 'No'; ?><br>
                        <strong>Tax Inclusive:</strong> <?php echo $item['taxInclusive'] ? 'Yes' : 'No'; ?>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
        </div>
</div>
            <!-- Car Details Section -->
       <section class=" car-detail-paragraph">
                <h4 class="fw-bold car-detail-paragraph-page">
                    Toyota Yaris Sedan (or Similar)
                    
                </h4>
                <p class="text-muted location-text">Dubai International Airport Terminal 1</p>

                <!-- Car Description -->
                <div class="mt-3">
                    <h5 class="fw-bold  car-description-text">Car Description</h5>
                    <p class="description-text">
                        The Toyota Yaris Sedan is a compact and fuel-efficient vehicle, perfect for city driving and short trips.
                        It offers a comfortable interior with modern amenities to ensure a smooth and enjoyable ride.
                    </p>
                    <p class="description-text">
                        Equipped with automatic transmission and air conditioning, this car is designed to provide both convenience and safety.
                        Ideal for business or leisure travelers seeking reliability and style.
                    </p>
                    <p class="description-text">
                        With ample trunk space and seating for up to five passengers, the Toyota Yaris Sedan delivers practicality without compromising on performance.
                    </p>
                </div>

                <!-- Car Included in rate-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Included in rate</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions['Included in rate'] as $includedInRate ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $includedInRate ?></li>
                    <?php }?>
                </ul>

                    <!-- Car Driver's age-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Driver's age</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Driver's age"] as $driverData ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $driverData ?></li>
                    <?php }?>
                </ul>


                    <!-- Car Driving licence-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Driving licence</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Driving licence"] as $driverLicence ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $driverLicence ?></li>
                    <?php }?>
                </ul>

                    <!-- Car Deposit and Payment Card Information-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Deposit and Payment Card Information</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Deposit and Payment Card Information"] as $depostPayment ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $depostPayment ?></li>
                    <?php }?>
                </ul>

                    <!-- Car Insurance coverage-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Insurance coverage</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Insurance coverage"] as $insuranceCoverage ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $insuranceCoverage ?></li>
                    <?php }?>
                </ul>


            <!-- Car Breakdown assistance-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Breakdown assistance</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Breakdown assistance"] as $breakdownAssistance ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $breakdownAssistance ?></li>
                    <?php }?>
                </ul>

                <!-- Car Fuel policy-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Fuel policy</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Fuel policy"] as $fuelPolicy ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $fuelPolicy ?></li>
                    <?php }?>
                </ul>

                <!-- Car Mileage policy-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Mileage policy</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Mileage policy"] as $mileagePolicy ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $mileagePolicy ?></li>
                    <?php }?>
                </ul>

                <!-- Car Special equipment/optional extras-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Special equipment/optional extras</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Special equipment/optional extras"] as $specialEquipment ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $specialEquipment ?></li>
                    <?php }?>
                </ul>

                <!-- Car Conditions of optional extras-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Conditions of optional extras</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Conditions of optional extras"] as $conditionsOptionalExtra ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $conditionsOptionalExtra ?></li>
                    <?php }?>
                </ul>

                <!-- Car Additional driver-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Additional driver</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Additional driver"] as $additionalDriver ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $additionalDriver ?></li>
                    <?php }?>
                </ul>

                <!-- Car Travel Restrictions-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Travel Restrictions</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Travel Restrictions"] as $travelRestrictions ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $travelRestrictions ?></li>
                    <?php }?>
                </ul>

                <!-- Car Tax rate-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Tax rate</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Tax rate"] as $taxRate ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $taxRate ?></li>
                    <?php }?>
                </ul>

                <!-- Car Fees-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Fees</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Fees"] as $fees ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $fees ?></li>
                    <?php }?>
                </ul>

                <!-- Car Form(s) of ID-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Form(s) of ID</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Form(s) of ID"] as $formsId ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $formsId ?></li>
                    <?php }?>
                </ul>

                <!-- Car Car types-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Car types</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Car types"] as $carTypes ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $carTypes ?></li>
                    <?php }?>
                </ul>

                <!-- Car Voucher-->
                <h5 class="fw-bold car-detail-paragraph-page mt-4 mb-2">Voucher</h5>
                <ul class="description-text list-unstyled">
                    <?php foreach($rentalConditions["Voucher"] as $voucher ){ 
                         ?>
                    <li class="car car-highlight-fuel"><i class="fa-solid fa-gas-pump me-2 text-primary"></i><?php echo $voucher ?></li>
                    <?php }?>
                </ul>
            </section>

            <!-- Map Section -->
            <section class="map-section">
                <div class="mt-4">
                    <div class="map-container">
                        <h4 class="map-title">Location</h4>
                        <iframe class="map-frame"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1994.055741947496!2d103.85450928327456!3d1.2864929113343078!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sus!4v1617827204872!5m2!1sen!2sus"
                            allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
            </section>

            <!-- Rental Conditions Section -->
            <section class="rental-conditions-section mt-4">
                <div class="booking-rules-container bg-white p-4 md:p-6 rounded-xl shadow-md">
                    <h4 class="booking-rules-title text-xl font-semibold mb-4 text-gray-800">Rental Conditions</h4>
                    <ul class="booking-rules-list list-disc pl-5 space-y-2 text-gray-700">
                    <?php foreach($rentalConditions['Cancellation and no-show policy'] as $Cancellation ){ 

                         ?>
                        <li class="booking-rule-item"><?php echo $Cancellation ;?></li>
                       <?php }?>
                    </ul>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".main-slider", {
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });

    document.querySelectorAll(".car-thumbnail").forEach((thumbnail) => {
        thumbnail.addEventListener("click", function () {
            const index = parseInt(this.getAttribute("data-slide"));
            swiper.slideToLoop(index);
        });
    });
});
</script>
<?php get_footer(); ?>
   