<section class="container why-choose-section">
   
    <h2 class="mb-4 why-choose-us-home-page">Why Choose Us</h2>
    <!-- 1) Use align-items-stretch on the row -->
    <div class="row g-4 align-items-stretch">
        <?php
        $query = new WP_Query(array(
            'post_type'      => 'why_choose_us',
            'posts_per_page' => -1, // Show all items
            'orderby'        => 'date',
            'order'          => 'ASC',
        ));

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $icon_url = get_field('icon_url'); // Get the icon image URL
        ?>
                <div class="col-md-3 d-flex">
                    <div class="why-choose-card p-4 text-center flex-fill">
                        <?php if ($icon_url) : ?>
                            <img src="<?php echo esc_url($icon_url); ?>" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <h5><?php //the_title(); ?></h5>
                        <p class="why-choose-us-paragraph-text-home-page"><?php the_content(); ?></p>
                    </div>
                </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No reasons available.</p>';
        endif;
        ?>
    </div>
</section>
