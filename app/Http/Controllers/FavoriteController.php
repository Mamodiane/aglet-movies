<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'poster_path' => 'nullable|string',
            'release_date' => 'nullable|date',
        ]);

        Favorite::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'movie_id' => $validated['movie_id'],
            ],
            [
                'title' => $validated['title'],
                'poster_path' => $validated['poster_path'] ?? null,
                'release_date' => $validated['release_date'] ?? null,
            ]
        );

        return back()->with('success', 'Movie added to favourites.');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== Auth::id()) {
            abort(403);
        }

        $favorite->delete();

        return back()->with('success', 'Movie removed from favourites.');
    }
}