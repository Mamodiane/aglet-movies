<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favourites</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Favourites</h1>

        <a href="{{ route('movies.index') }}" class="btn btn-secondary">
            Back to Movies
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($favorites->isEmpty())
        <p class="text-center">No favourite movies added yet.</p>
    @else
        <div class="row">
            @foreach($favorites as $favorite)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">

                        @if($favorite->poster_path)
                            <img
                                src="https://image.tmdb.org/t/p/w500{{ $favorite->poster_path }}"
                                class="card-img-top"
                                alt="{{ $favorite->title }}"
                            >
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $favorite->title }}
                            </h5>

                            <p class="card-text">
                                Release Date: {{ $favorite->release_date ?? 'Unknown' }}
                            </p>

                            <form action="{{ route('favorites.destroy', $favorite) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger w-100">
                                    Remove
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</body>
</html>