<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aglet Movies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('movies.index') }}">
            Aglet Movies
        </a>

        <div class="d-flex gap-2">
            <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary">Movies</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-secondary">Contact</a>

            @auth
                <a href="{{ route('favorites.index') }}" class="btn btn-outline-danger">
                    My Favourites
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-dark">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container py-5">

    <h1 class="mb-4 text-center">
        {{ $query ? 'Search Results' : 'Popular Movies' }}
    </h1>

    <form action="{{ route('movies.index') }}" method="GET" class="mb-4 position-relative">
        <input
            type="text"
            id="movie-search"
            name="query"
            value="{{ $query ?? '' }}"
            class="form-control"
            placeholder="Search for movies..."
            autocomplete="off"
        >

        <div
            id="search-results"
            class="list-group position-absolute w-100"
            style="z-index: 1000;"
        ></div>
    </form>

    @if(!empty($query))
        <div class="mb-4 text-center">
            <a href="{{ route('movies.index') }}" class="btn btn-secondary">
                Back to Popular Movies
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if($error)
        <div class="alert alert-danger text-center">
            {{ $error }}
        </div>
    @endif

    @if(empty($movies))
        <p class="text-center">No movies found.</p>
    @else
        <div class="row">
            @foreach($movies as $movie)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">

                        @if(!empty($movie['poster_path']))
                            <img
                                src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                class="card-img-top"
                                alt="{{ $movie['title'] ?? 'Movie poster' }}"
                            >
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $movie['title'] ?? 'Untitled Movie' }}
                            </h5>

                            <p class="card-text">
                                Release Date: {{ $movie['release_date'] ?? 'Unknown' }}
                            </p>

                            <button
                                type="button"
                                class="btn btn-outline-secondary w-100 mb-2 view-details-btn"
                                data-movie-id="{{ $movie['id'] }}"
                            >
                                View Details
                            </button>

                            <form action="{{ route('favorites.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                                <input type="hidden" name="title" value="{{ $movie['title'] ?? 'Untitled Movie' }}">
                                <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                                <input type="hidden" name="release_date" value="{{ $movie['release_date'] ?? '' }}">

                                @if(in_array($movie['id'], $favoriteMovieIds))
                                    <button type="button" class="btn btn-success w-100" disabled>
                                        Already Added
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        Add to Favourites
                                    </button>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(empty($query))
        <div class="d-flex justify-content-center mt-4">
            @if($page > 1)
                <a href="{{ route('movies.index', ['page' => $page - 1]) }}" class="btn btn-primary me-2">
                    Previous
                </a>
            @endif

            @if($page < 5)
                <a href="{{ route('movies.index', ['page' => $page + 1]) }}" class="btn btn-primary">
                    Next
                </a>
            @endif
        </div>
    @endif

</div>

<div class="modal fade" id="movieDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="movieModalTitle" class="modal-title">Movie Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Overview:</strong></p>
                <p id="movieModalOverview"></p>

                <p><strong>Release Date:</strong> <span id="movieModalRelease"></span></p>
                <p><strong>Rating:</strong> <span id="movieModalRating"></span></p>
                <p><strong>Runtime:</strong> <span id="movieModalRuntime"></span></p>
                <p><strong>Language:</strong> <span id="movieModalLanguage"></span></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const searchInput = document.getElementById('movie-search');
    const resultsBox = document.getElementById('search-results');

    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);

        const query = this.value.trim();

        if (query.length < 2) {
            resultsBox.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`{{ route('movies.search') }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(movies => {
                    resultsBox.innerHTML = '';

                    if (movies.length === 0) {
                        resultsBox.innerHTML = `
                            <div class="list-group-item">
                                No movies found
                            </div>
                        `;
                        return;
                    }

                    movies.forEach(movie => {
                        resultsBox.innerHTML += `
                            <a
                                href="{{ route('movies.index') }}?query=${encodeURIComponent(movie.title)}"
                                class="list-group-item list-group-item-action"
                            >
                                <strong>${movie.title}</strong>
                                <br>
                                <small>Release Date: ${movie.release_date}</small>
                            </a>
                        `;
                    });
                });
        }, 400);
    });

    document.addEventListener('click', function (event) {
        if (!searchInput.contains(event.target) && !resultsBox.contains(event.target)) {
            resultsBox.innerHTML = '';
        }
    });

    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function () {
            const movieId = this.dataset.movieId;

            fetch(`/movies/${movieId}/details`)
                .then(response => response.json())
                .then(movie => {
                    document.getElementById('movieModalTitle').innerText = movie.title ?? 'Movie Details';
                    document.getElementById('movieModalOverview').innerText = movie.overview ?? 'No overview available.';
                    document.getElementById('movieModalRelease').innerText = movie.release_date ?? 'Unknown';
                    document.getElementById('movieModalRating').innerText = movie.vote_average ?? 'N/A';
                    document.getElementById('movieModalRuntime').innerText = movie.runtime ? `${movie.runtime} minutes` : 'N/A';
                    document.getElementById('movieModalLanguage').innerText = movie.original_language ?? 'N/A';

                    const modal = new bootstrap.Modal(document.getElementById('movieDetailsModal'));
                    modal.show();
                });
        });
    });
</script>

</body>
</html>