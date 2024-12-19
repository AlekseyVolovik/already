<?php
function get_year_range() {
    global $wpdb;

    $years = $wpdb->get_col("
        SELECT DISTINCT meta_value 
        FROM {$wpdb->postmeta}
        WHERE meta_key = 'movie_year'
        ORDER BY meta_value ASC
    ");

    return $years;
}

add_action('wp_ajax_filter_movies', 'filter_movies_callback');
add_action('wp_ajax_nopriv_filter_movies', 'filter_movies_callback');

function filter_movies_callback() {
    $genre = isset($_GET['genre']) ? sanitize_text_field($_GET['genre']) : '';
    $release_year_from = isset($_GET['release_year_from']) ? intval($_GET['release_year_from']) : '';
    $release_year_to = isset($_GET['release_year_to']) ? intval($_GET['release_year_to']) : '';
    $rating_order = isset($_GET['rating_order']) ? sanitize_text_field($_GET['rating_order']) : 'desc';

    $args = array(
        'post_type' => 'movies',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'meta_value_num',
        'order' => $rating_order,
        'meta_key' => 'movie_rating',
        'meta_query' => array('relation' => 'AND'),
    );

    if (!empty($genre)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $genre,
                'operator' => 'IN',
            ),
        );
    }

    if ($release_year_from) {
        $args['meta_query'][] = array(
            'key'     => 'movie_year',
            'value'   => $release_year_from,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        );
    }

    if ($release_year_to) {
        $args['meta_query'][] = array(
            'key'     => 'movie_year',
            'value'   => $release_year_to,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        );
    }

    $query = new WP_Query($args);

    $movies = '';
    $total = 0;

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $title = get_the_title();
            $rating = get_field('movie_rating');
            $genre = get_the_terms($post_id, 'category');
    
            $movie_link = get_permalink($post_id);
            $thumbnail_url = get_the_post_thumbnail_url($post_id);
    
            $movies .= '
            <div class="movie-item">

                <div class="movie-item__image">
                    <div class="movie-item__raiting"><div class="movie-item__raiting-content">' . esc_html($rating) . '<div class="star"></div></div></div>
                    <img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($title) . '">
                </div>
                <div class="movie-item__text">
                    <div class="movie-item__name">' . esc_html($title) . '</div>              
                    <a class="movie-item__link" href="' . esc_url($movie_link) . '">Read more</a>
                </div>
            </div>';
            $total++;
        }
    } else {
        $movies = 'No movies found';
    }    

    wp_reset_postdata();

    wp_send_json(array(
        'html' => $movies,
        'total' => $total,
    ));
}
