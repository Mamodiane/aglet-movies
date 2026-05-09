<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    protected TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $query = $request->get('query');

        // Prevent invalid pagination values
        if ($page < 1) {
            $page = 1;
        }

        // Limit movie browsing to first 5 pages
        if ($page > 5) {
            $page = 5;
        }

        // Use TMDB search endpoint when search query is provided
        if ($query) {
            $moviesData = $this->tmdbService->searchMovies($query);
        } else {
            $moviesData = $this->tmdbService->getPopularMovies($page);
        }

        // Limit displayed movies to 9 per page
        $movies = array_slice($moviesData['results'] ?? [], 0, 9);

        $favoriteMovieIds = [];

        // Retrieve authenticated user's favourite movie IDs
        if (Auth::check()) {
            $favoriteMovieIds = Favorite::where('user_id', Auth::id())
                ->pluck('movie_id')
                ->toArray();
        }

        return view('movies.index', [
            'movies' => $movies,
            'page' => $page,
            'query' => $query,
            'error' => $moviesData['error'] ?? null,
            'favoriteMovieIds' => $favoriteMovieIds,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        if (!$query) {
            return response()->json([]);
        }

        $movies = $this->tmdbService->searchMovies($query);

        return response()->json(
            collect($movies['results'] ?? [])

                // Limit autocomplete suggestions for cleaner UX
                ->take(5)

                ->map(function ($movie) {
                    return [
                        'id' => $movie['id'],
                        'title' => $movie['title'] ?? 'Untitled Movie',
                        'release_date' => $movie['release_date'] ?? 'Unknown',
                    ];
                })
                ->values()
        );
    }

    // Return movie details as JSON for the Bootstrap modal popup
    public function details($id)
    {
        $movie = $this->tmdbService->getMovieDetails((int) $id);

        return response()->json($movie);
    }
}