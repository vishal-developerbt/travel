<?php
/*
Template Name: Trending View All Page
*/
get_header(); ?>

<section class="container destination-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Trending Destinations</h2>
    </div>
    <div class="row g-3">
        <?php
        $query = new WP_Query(array(
            'post_type'      => 'Trending Destinations',
            'posts_per_page' => 8, // Change this number to show more or less destinations
        ));

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
            //$tour_count = get_field('tour_count'); // Fetch ACF tour count
            $tour_count = 100;
            if (!$tour_count) {
                $tour_count = "100+"; // Default value if not set
            }
        ?>
        <div class="col-md-3">
            <div class="destination-card">
                <?php
                if (true) {
                ?>
                   <p><?php the_content(); ?></p>
                <?php
                } else {
                    echo '<img src="' . get_template_directory_uri() . '/photos/Tokyo.png" alt="Default Image">';
                }
                ?>
                <a href="<?php echo esc_url(
                    get_site_url() . '/booking-lists/?location=' . urlencode(get_the_title()) 
                    . '&checkin=' . urlencode(date('Y-m-d')) 
                    . '&checkout=' . urlencode(date('Y-m-d', strtotime('+4 day')))
                    . '&rooms=' . urlencode('1-2-0')
                ); ?>">
                <div class="destination-overlay"></div></a>
                <div class="destination-text">
                   <div class="destination-tittle-text-home-page"><?php the_title(); ?></div> 
                    <div class="tour-count-section-hoe-pages">
                    <?php echo $tour_count; ?> Tours</div> 
                </div>
            </div>
        </div>
        <?php
            endwhile;
            wp_reset_postdata();
            else :
                echo '<p>No destinations available.</p>';
            endif;
        ?>
    </div>
</section>
<?php get_footer(); ?>