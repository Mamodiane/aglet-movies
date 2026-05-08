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
            <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary">
                Movies
            </a>

            <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                Contact
            </a>

            @auth
                <a href="{{ route('favorites.index') }}" class="btn btn-outline-danger">
                    My Favourites
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-dark">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="container py-5">

    <h1 class="mb-4 text-center">Popular Movies</h1>

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

                            <form action="{{ route('favorites.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
                                <input type="hidden" name="title" value="{{ $movie['title'] ?? 'Untitled Movie' }}">
                                <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                                <input type="hidden" name="release_date" value="{{ $movie['release_date'] ?? '' }}">

                                @if(in_array($movie['id'], $favoriteMovieIds))

                                    <button
                                        type="button"
                                        class="btn btn-success w-100"
                                        disabled
                                    >
                                        Already Added
                                    </button>

                                @else

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger w-100"
                                    >
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

</div>

</body>
</html>