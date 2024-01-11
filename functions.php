<?php

add_theme_support('post-thumbnails');


add_filter('show_admin_bar', '__return_false');


function register_news_post_type()
{
    $labels = array(
        'name' => 'News',
        'singular_name' => 'News',
        'menu_name' => 'News',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New News',
        'edit_item' => 'Edit News',
        'new_item' => 'New News',
        'view_item' => 'View News',
        'view_items' => 'View News',
        'search_items' => 'Search News',
        'not_found' => 'No News found',
        'not_found_in_trash' => 'No News found in Trash',
        'parent_item_colon' => 'Parent News:',
        'all_items' => 'All News',
        'archives' => 'News Archives',
        'attributes' => 'News Attributes',
        'insert_into_item' => 'Insert into News',
        'uploaded_to_this_item' => 'Uploaded to this News',
        'featured_image' => 'Featured Image',
        'set_featured_image' => 'Set featured image',
        'remove_featured_image' => 'Remove featured image',
        'use_featured_image' => 'Use as featured image',
        'menu_icon' => 'dashicons-welcome-write-blog',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'news'),
        'query_var' => true,
        'can_export' => true,
    );

    register_post_type('news', $args);
}

add_action('init', 'register_news_post_type');

function register_news_taxonomy()
{
    $labels = array(
        'name' => 'News Categories',
        'singular_name' => 'News Category',
        'search_items' => 'Search News Categories',
        'all_items' => 'All News Categories',
        'parent_item' => 'Parent News Category',
        'parent_item_colon' => 'Parent News Category:',
        'edit_item' => 'Edit News Category',
        'update_item' => 'Update News Category',
        'add_new_item' => 'Add New News Category',
        'new_item_name' => 'New News Category Name',
        'menu_name' => 'News Categories',
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news-category'),
    );

    register_taxonomy('news_category', array('news'), $args);
}

add_action('init', 'register_news_taxonomy');


function enqueue_reset_css()
{
    wp_enqueue_style('reset-css', 'https://cdn.jsdelivr.net/npm/reset-css@5.0.2/reset.min.css', array('news-styles'), '5.0.2');
}

add_action('wp_enqueue_scripts', 'enqueue_reset_css');

function enqueue_news_styles()
{
    $theme_style_path = get_template_directory_uri() . '/css/app.css';
    wp_enqueue_style('news-styles', $theme_style_path, array(), '1.0', 'all');
}

add_action('wp_enqueue_scripts', 'enqueue_news_styles');

function enqueue_news_scripts()
{
    $theme_script_path = get_template_directory_uri() . '/js/app.js';
    wp_enqueue_script('news-scripts', $theme_script_path, array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_news_scripts');

function modify_main_query_for_news($query)
{
    if ($query->is_main_query() && !is_admin() && is_front_page()) {
        $query->set('post_type', array('news'));
        $query->set('posts_per_page', 5);
    }
}

add_action('pre_get_posts', 'modify_main_query_for_news');


function news_filter_function()
{
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $selected_categories = isset($_GET['news_category']) ? $_GET['news_category'] : array();

    $args = array(
        'post_type' => 'news',
        'posts_per_page' => 5,
        'paged' => $paged,
    );

    if (!empty($selected_categories)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'news_category',
                'field' => 'id',
                'terms' => $selected_categories,
                'operator' => 'IN',
            ),
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            include 'components/news-card.php';
        endwhile;
        include 'components/pagination.php';
        wp_reset_postdata();
    else :
        echo 'No posts found';
    endif;

    die();
}


add_action('wp_ajax_news_filter', 'news_filter_function');
add_action('wp_ajax_nopriv_news_filter', 'news_filter_function');


