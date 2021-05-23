<?php

namespace App\Http\Controllers;

use App\Models\Celeb;
use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $movies = Movie::with(['genres'])
            ->take(5)
            ->get()
            ->map(function ($movie) {
                $movie->average_rating = round($movie->reviews()->average('rating'),1);
                return $movie;
            })
            ->sortByDesc('average_rating');

        $random = $movies->random();

        $celebs = Celeb::take(5)
            ->get();

        return view('home', [
            'movies'=> $movies,
            'celebs' => $celebs,
            'random' => $random
        ]);
    }
}
