<div class="news__item">
    <a class="news__item-link" href="<?php the_permalink(); ?>">
        <span class="nav__item-title"><?php the_title(); ?></span>
        <?php
        $terms = get_the_terms(get_the_ID(), 'news_category');
        if ($terms && !is_wp_error($terms)) {
            echo '<ul class="news__terms">';
            foreach ($terms as $term) {
                echo '<li>' . esc_html($term->name) . '</li>';
            }
            echo '</ul>';
        }
        ?>
    </a>
</div>
