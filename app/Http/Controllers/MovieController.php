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

        if ($page < 1) {
            $page = 1;
        }

        if ($page > 5) {
            $page = 5;
        }

        if ($query) {
            $moviesData = $this->tmdbService->searchMovies($query);
        } else {
            $moviesData = $this->tmdbService->getPopularMovies($page);
        }

        $movies = array_slice($moviesData['results'] ?? [], 0, 9);

        $favoriteMovieIds = [];

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

    public function details($id)
    {
        $movie = $this->tmdbService->getMovieDetails((int) $id);

        return response()->json($movie);
    }
}