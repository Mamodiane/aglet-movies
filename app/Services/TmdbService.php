<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('TMDB_BASE_URL'), '/');
        $this->apiKey = env('TMDB_API_KEY');
    }

    // Fetch popular movies from TMDB API
    public function getPopularMovies(int $page = 1): array
    {
        $response = Http::withoutVerifying()
            ->timeout(10)
            ->get($this->baseUrl . '/movie/popular', [
                'api_key' => $this->apiKey,
                'page' => $page,
            ]);

        // Return fallback response if TMDB request fails
        if ($response->failed()) {
            return [
                'results' => [],
                'page' => $page,
                'total_pages' => 0,
                'error' => 'Unable to fetch movies from TMDB.',
            ];
        }

        return $response->json();
    }

    // Search movies from TMDB API using movie title
    public function searchMovies(string $query): array
    {
        $response = Http::withoutVerifying()
            ->timeout(10)
            ->get($this->baseUrl . '/search/movie', [
                'api_key' => $this->apiKey,
                'query' => $query,
            ]);

        if ($response->failed()) {
            return ['results' => []];
        }

        return $response->json();
    }

    // Fetch detailed movie information from TMDB API
    public function getMovieDetails(int $movieId): array
    {
        $response = Http::withoutVerifying()
            ->timeout(10)
            ->get($this->baseUrl . "/movie/{$movieId}", [
                'api_key' => $this->apiKey,
            ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json();
    }
}