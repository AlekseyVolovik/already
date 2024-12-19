<?php
/**
 * Plugin Name: Movie Filter
 * Description: Фильтрация, сортировка и подгрузка фильмов с AJAX.
 * Version: 1.0
 * Author: Aleksey
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/filter-movies.php';

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('movie-filter-css', plugin_dir_url(__FILE__) . 'assets/css/movie-filter.css');
    wp_enqueue_script('movie-filter-js', plugin_dir_url(__FILE__) . 'assets/js/movie-filter.js', array('jquery'), null, true);

    wp_localize_script('movie-filter-js', 'movieFilterData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ));
});