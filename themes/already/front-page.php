<?php get_header() ?>

<main class="main">
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-content__text">
                    <h1 class="hero-content__text-title title">Explore a <span>World</span> of Cinematic Wonders</h1>
                    <div class="hero-content__text-text">Our database not only includes blockbusters but also independent films, documentary features, and works from talented directors worldwide.</div>
                    <div class="hero-content__text-buttons">
                        <button class="button--main">REGISTER NOW</button>
                        <a class="button--secondary" href="#">About us</a>
                    </div>
                </div>
                <div class="hero-content__image"><img src="<?= get_template_directory_uri(); ?>/assets/images/img-hero.png" alt=""></div>
            </div>
        </div>
    </section>

    <section class="filter">
        <div class="container">
            <h2 class="filter-title title">Discover a <span>Universe</span> of Cinematic Marvels</h2>

            <div class="filter-wrapper">
                <div class="filter-sidebar">
                    <div class="filter-sidebar__title">FILTER:</div>
                    <form id="filter-form">                        
                        <div class="filter-form__selects">
                            <div class="filter-form__selects-date">
                                <label for="genre">Genre:</label>
                                <div class="select-wrapper">
                                    <select name="genre" id="genre">
                                        <option value="">All</option>
                                        <?php
                                        $genres = get_terms(array(
                                            'taxonomy' => 'category',
                                            'hide_empty' => true,
                                        ));

                                        if ($genres && !is_wp_error($genres)) {
                                            foreach ($genres as $genre) {
                                                echo '<option value="' . esc_attr($genre->slug) . '">' . esc_html($genre->name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="filter-form__selects-date">                              
                                <label for="release_year_from">Date from:</label>
                                <div class="select-wrapper">
                                    <select name="release_year_from" id="release_year_from">
                                        <option value="">Year</option>
                                        <?php
                                        $years = get_year_range();
                                        foreach ($years as $year) {
                                            echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>                              
                            
                                <label for="release_year_to">to</label>
                                <div class="select-wrapper">
                                    <select name="release_year_to" id="release_year_to">
                                        <option value="">Year</option>
                                        <?php
                                        foreach ($years as $year) {
                                            echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>                 
                        <button class="filter-form__button button--main" type="submit">APPLY</button>
                    </form>
                </div>

                <div class="filter-movies">
                    <div class="filter-movies__rating">
                        <div class="filter-movies__rating-text">Sort by:</div>
                        <button id="sort-rating" data-order="desc">
                            Rating
                            <div class="arrow"></div>
                        </button>
                    </div>
                    <div id="movie-list"></div>
                    <button class="button--main" id="load-more" style="display: none;">Load more</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer() ?>