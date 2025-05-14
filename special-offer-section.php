<section class="container my-2 special-offer-section">
    <div class="custom-tabs">
        <div class="custom-tab-sections mb-3">
            <div class="special-offer-flex-section">
                <h2 class="special-offer-tittles">Special Offers</h2>
                <button class="custom-tab-btn active" data-tab="home-page-hotels-tab">
                    <p class="speical-hotels">Hotels</p>
                </button>

                <button class="custom-tab-btn" data-tab="airoplane-section-home-page-tab">
                    <p class="flights-special">Flights</p>
                </button>
            </div>
            <div class="special-offer-see-all-section">
                <a id="view-all-link" href="<?php echo site_url('/index.php/special-offer-view-all/?type=hotel'); ?>">See all</a>
            </div>
        </div>
    </div>

    <!-- Hotel Tab Content -->
    <div id="home-page-hotels-tab" class="custom-tab-content hotel-tab active">
        <div class="row">
            <?php
            $query = new WP_Query(array(
                'post_type'      => 'special_offers',
                'posts_per_page' => 3, 
            ));

            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <p><?php the_content(); ?></p>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="special-paragraph-text">No special offers available.</p>';
            endif;
            ?>
        </div>
    </div>

    <!-- Flights Tab Content -->
    <div id="airoplane-section-home-page-tab" class="custom-tab-content d-none">
        <div class="row">
            <?php
            $query = new WP_Query(array(
                'post_type'      => 'special_flight',
                'posts_per_page' => 3, 
            ));

            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
            ?>
                <div class="col-md-4">
                    <div class="card">
                        <p><?php the_content(); ?></p>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="special-paragraph-text">No special offers available.</p>';
            endif;
            ?>
        </div>
    </div>
</section>

