<section class="container faq-section frequently-asked-question-section">
    <h2 class="mb-4 frequently-aske-question-home-page">Frequently Asked Questions</h2>

    <?php 
    $terms = get_terms(array(
        'taxonomy'   => 'faq_category',
        'hide_empty' => true,
    ));
    ?>

    <div class="faq-tabs mb-3">
        <?php foreach ($terms as $index => $term): ?>
            <button class="<?php echo $index === 0 ? 'active' : ''; ?>" 
                    onclick="showTab('<?php echo esc_attr($term->slug); ?>', event)">
                <?php echo esc_html($term->name); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($terms as $index => $term): ?>
        <div id="<?php echo esc_attr($term->slug); ?>" 
             class="faq-content <?php echo $index === 0 ? '' : 'd-none'; ?>">
             
            <div class="accordion" id="<?php echo esc_attr($term->slug); ?>Faq">
                <?php
                $faq_query = new WP_Query(array(
                    'post_type'      => 'faq',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'faq_category',
                            'field'    => 'slug',
                            'terms'    => $term->slug,
                        ),
                    ),
                    'posts_per_page' => -1,
                ));

                if ($faq_query->have_posts()):
                    while ($faq_query->have_posts()):
                        $faq_query->the_post();
                        ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo $index === 0 ? '' : 'collapsed'; ?>" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq-<?php the_ID(); ?>" 
                                        aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>" 
                                        aria-controls="faq-<?php the_ID(); ?>">
                                    <strong><?php the_title(); ?></strong>
                                </button>
                            </h2>
                            <div id="faq-<?php the_ID(); ?>" 
                                 class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" 
                                 data-bs-parent="#<?php echo esc_attr($term->slug); ?>Faq">
                                <div class="accordion-body">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                else:
                    echo '<p>No FAQs available for this category.</p>';
                endif;
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<script>
    function showTab(tabId, event) {
        // Hide all FAQ contents
        document.querySelectorAll('.faq-content').forEach((tab) => {
            tab.classList.add('d-none');
        });

        // Remove 'active' class from all buttons
        document.querySelectorAll('.faq-tabs button').forEach((btn) => {
            btn.classList.remove('active');
        });

        // Show the selected tab
        document.getElementById(tabId).classList.remove('d-none');

        // Highlight the clicked button
        event.target.classList.add('active');
    }
</script>

<!-- Add Bootstrap JS before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
