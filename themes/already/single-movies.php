<?php
get_header();


if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <article>
            <div class="container">
                <div class="movie-post">
                    <h1 class="movie-title"><?php the_title(); ?></h1>
                    
                    <div class="movie-poster">
                        <?php if (has_post_thumbnail()) {
                            the_post_thumbnail('large');
                        } ?>
                    </div>
                    
                    <p class="movie-release-date">
                        <strong>Release date: </strong>
                        <?php
                        $release_date = get_field('movie_release_date'); 

                        if ($release_date) {
                            echo date_i18n('j F Y', strtotime($release_date));
                        }
                        ?>
                    </p>
                    
                    <p class="movie-rating">
                        <strong>Rating: </strong>
                        <?php
                        $rating = get_field('movie_rating'); 
                        echo esc_html($rating);
                        ?>
                    </p>
                    
                    <p class="movie-year">
                        <strong>Release year: </strong>
                        <?php
                        $year = get_field('movie_year'); 
                        echo esc_html($year);
                        ?>
                    </p>
                    
                    <p class="movie-genres">
                        <strong>Genres: </strong>
                        <?php
                        $terms = get_the_terms(get_the_ID(), 'category');
                        if ($terms && !is_wp_error($terms)) {
                            $genres = array();
                            foreach ($terms as $term) {
                                $genres[] = $term->name;
                            }
                            echo implode(', ', $genres);
                        }
                        ?>
                    </p>

                    <div class="movie-description">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile;
else :
    echo '<p>Not found</p>';
endif;

get_footer();
?>
