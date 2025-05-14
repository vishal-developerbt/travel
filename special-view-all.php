<?php
/*
Template Name: Special Offers View All
*/
get_header();

$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'hotel'; // Default to 'hotel'

// Determine post type based on parameter
$post_type = ($type === 'flight') ? 'special_flight' : 'special_offers';

// Query to fetch data
$query = new WP_Query(array(
    'post_type'      => $post_type,
    'posts_per_page' => -1, // Show all posts
));

?>

<section class="container my-2 special-offer-section">
    <h2 class="special-offer-tittles"><?php echo ucfirst($type); ?> Offers</h2>

    <div class="row">
        <?php
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
</section>

<?php get_footer(); ?>
