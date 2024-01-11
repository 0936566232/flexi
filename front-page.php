<?php get_header(); ?>
<section class="section__news">
    <div class="container">
        <div class="news-title">
            <h1>News</h1>
        </div>
        <div class="news__filter">
            <div class="filter-form">
                <form
                        id="news-filter-form"
                        class="news-filter-form"
                        action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                        method="GET"
                        data-page="1">

                    <?php
                    $terms = get_terms(array(
                        'taxonomy' => 'news_category',
                        'hide_empty' => false,
                    ));

                    if (!empty($terms)) {
                        foreach ($terms as $term) {
                            echo '<label>';
                            echo '<input type="checkbox" name="news_category[]" value="' . esc_attr($term->term_id) . '"> ' . esc_html($term->name);
                            echo '</label>';
                        }
                    }
                    ?>
                    <input type="submit" value="Filter">
                    <a href="#" id="reset-filter-all">Reset</a>
                    <input type="hidden" name="action" value="news_filter">
                </form>

            </div>

        </div>
        <div class="news__items">
            <?php
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;

            $args = array(
                'post_type' => 'news',
                'posts_per_page' => 5,
                'paged' => $paged,
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    include 'components/news-card.php';
                endwhile;
                ?>
                <?php include 'components/pagination.php' ?>
                <?php
                wp_reset_postdata();
            else :
                echo 'No posts found static';
            endif;
            ?>
        </div>

    </div>
</section>
<?php get_footer(); ?>
