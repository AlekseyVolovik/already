document.addEventListener("DOMContentLoaded", function () {
    const ajaxUrl = movieFilterData.ajaxUrl;
    const postsPerPage = 2;
    let totalMovies = 0;
    let visibleMovies = 2;
    let allMoviesHtml = "";

    function loadAllMovies(params = {}) {
        const query = new URLSearchParams({
            action: "filter_movies",
            ...params,
        });

        fetch(`${ajaxUrl}?${query}`)
            .then((response) => response.json())
            .then((data) => {
                const movieList = document.getElementById("movie-list");
                const loadMoreButton = document.getElementById("load-more");

                if (!movieList) {
                    console.log("movie-list element not found on the page.");
                    return;
                }

                allMoviesHtml = data.html;
                totalMovies = data.total;
                visibleMovies = postsPerPage;

                if (totalMovies === 0) {
                    movieList.innerHTML = '<p class="no-movies">No movies found</p>';
                    if (loadMoreButton) loadMoreButton.style.display = "none";
                } else {
                    updateVisibleMovies();

                    if (totalMovies <= postsPerPage) {
                        if (loadMoreButton) loadMoreButton.style.display = "none";
                    } else {
                        if (loadMoreButton) loadMoreButton.style.display = "block";
                    }
                }
            })
            .catch((error) => console.log("Ошибка загрузки:", error));
    }

    function updateVisibleMovies() {
        const movieList = document.getElementById("movie-list");
        if (!movieList) {
            console.log("movie-list element not found on the page.");
            return;
        }

        const parser = new DOMParser();
        const moviesDoc = parser.parseFromString(allMoviesHtml, "text/html");
        const movies = moviesDoc.querySelectorAll(".movie-item");

        const visibleHtml = Array.from(movies)
            .slice(0, visibleMovies)
            .map((movie) => movie.outerHTML)
            .join("");

        movieList.innerHTML = visibleHtml;

        const loadMoreButton = document.getElementById("load-more");
        if (visibleMovies >= totalMovies && loadMoreButton) {
            loadMoreButton.style.display = "none";
        }
    }

    const filterForm = document.getElementById("filter-form");
    if (filterForm) {
        filterForm.addEventListener("submit", function (event) {
            event.preventDefault();
            visibleMovies = postsPerPage;

            const formData = new FormData(this);
            const filters = {};

            formData.forEach((value, key) => {
                if (value) filters[key] = value;
            });

            loadAllMovies(filters);
        });
    }

    const sortButton = document.getElementById("sort-rating");
    if (sortButton) {
        sortButton.addEventListener("click", function () {
            const button = this;
            const currentOrder = button.getAttribute("data-order");
            const newOrder = currentOrder === "desc" ? "asc" : "desc";

            button.setAttribute("data-order", newOrder);
            visibleMovies = postsPerPage;

            const filters = {};
            if (filterForm) {
                const formData = new FormData(filterForm);
                formData.forEach((value, key) => {
                    if (value) filters[key] = value;
                });
            }

            filters.rating_order = newOrder;
            loadAllMovies(filters);
        });
    }

    const loadMoreButton = document.getElementById("load-more");
    if (loadMoreButton) {
        loadMoreButton.addEventListener("click", function () {
            visibleMovies += postsPerPage;
            updateVisibleMovies();
        });
    }

    loadAllMovies();
});
