<?php

namespace App\Http\Controllers;

use App\Models\Celeb;
use App\Models\Genre;
use App\Models\Movie;
use App\Http\Requests\MovieRequest;
use App\Models\GenreMovie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('id', 'DESC')
            ->paginate(20);

        foreach ($movies as $movie) {
            $movie->average_rating = round($movie->reviews()->average('rating'), 1);
        }

        return view('movies.index', [
            'movies' => $movies,
        ]);
    }

    public function create()
    {
        $genres = Genre::all();
        $celebs = Celeb::orderBy('name', 'ASC')->get();

        return view('movies.create', [
            'genres' => $genres,
            'celebs' => $celebs
        ]);
    }

    public function imdbCreate()
    {
        $data = [];
        if (!empty($_GET['s'])) {
            $omdbApiKey = env('OMDB_API_KEY');
            $url = "https://www.omdbapi.com/?s={$_GET['s']}&apikey=$omdbApiKey";
            $result = Movie::apiCall($url);
            $data = !empty($result['Search']) ? $result['Search'] : [];
        }
        return view('movies.imdb_create', [
            'data' => $data
        ]);
    }
    public function imdbStore($imdbId)
    {
        $data = [];
        $omdbApiKey = env('OMDB_API_KEY');
        $url = "https://www.omdbapi.com/?i=$imdbId&plot=full&apikey=$omdbApiKey";
        $data = Movie::apiCall($url);
        if (!empty($data)) {
            $movie = Movie::updateOrCreate([
                'imdb_id' => $imdbId
            ],[
                'title' => $data['Title'],
                'synopsis' => $data['Plot'],
                'year' => $data['Year'],
                'poster' => $data['Poster'],
                'duration' => intval($data['Runtime']),
                'imdb_id' => $imdbId,
                'language' => $data['Language'],
                'country' => $data['Country'],
                'imdb_rating' => !empty($data['imdbRating']) && $data['imdbRating'] != 'N/A' ? $data['imdbRating'] : null
            ]);

            if (!empty($data['Genre'])) {
                $genres = explode(', ', $data['Genre']);
                foreach ($genres as $g) {
                    $genre = Genre::where('name', $g)->first();
                    if (empty($genre)) {
                        $genre = Genre::create([
                            'name' => $g
                        ]);
                    }
                    GenreMovie::updateOrCreate([
                        'genre_id' => $genre->id,
                        'movie_id' => $movie->id
                    ],[
                        'genre_id' => $genre->id,
                        'movie_id' => $movie->id
                    ]);
                }
            }
        }
        print_r($data); die();
    }

    public function store(MovieRequest $request)
    {
        $movie = Movie::create([
            'title' => $request->input('title'),
            'synopsis' => $request->input('synopsis'),
            'year' => $request->input('year'),
            'poster' => $request->input('poster'),
            'banner' => $request->input('banner'),
            'trailer' => $request->input('trailer'),
            'duration' => $request->input('duration'),
        ]);

        $genres = $request->input('genres');
        $movie->genres()->sync($genres);

        $results = array_combine($request->input('celebs'), $request->input('characters'));
        $casts = collect($results)
            ->map(function ($result) {
                return ['character_name' => $result];
            });
        $movie->celebs()->sync($casts);

        return redirect()->route('movies.index')
            ->with(['message' => 'Success! Movie has been added.']);
    }

    public function show(Movie $movie)
    {
        $movie->with([
            'genres',
            'celebs'
        ])->get();

        $movie->average_rating = round($movie->reviews()->average('rating'), 1);

        $reviews = $movie->reviews()->paginate(3);

        return view('movies.show', [
            'movie' => $movie,
            'rating' => $movie->average_rating,
            'reviews' => $reviews
        ]);
    }

    public function edit(Movie $movie)
    {
        $genres = Genre::all();
        $celebs = Celeb::orderBy('name', 'ASC')->get();

        return view('movies.edit', [
            'movie' => $movie,
            'genres' => $genres,
            'celebs' => $celebs
        ]);
    }

    public function update(MovieRequest $request, Movie $movie)
    {
        $movie->update([
            'title' => $request->input('title'),
            'synopsis' => $request->input('synopsis'),
            'year' => $request->input('year'),
            'poster' => $request->input('poster'),
            'banner' => $request->input('banner'),
            'trailer' => $request->input('trailer'),
            'duration' => $request->input('duration'),
        ]);

        $genres = $request->input('genres');
        $movie->genres()->sync($genres);

        $results = array_combine($request->input('celebs'), $request->input('characters'));
        $casts = collect($results)
            ->map(function ($result) {
                return ['character_name' => $result];
            });
        $movie->celebs()->sync($casts);

        return redirect()->route('movies.show', $movie)
            ->with(['message' => 'Success! Movie has been updated.']);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('movies.index')
            ->with(['message' => 'Success! Movie has been deleted.']);
    }
}
