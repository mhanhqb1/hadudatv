<?php

namespace App\Http\Controllers;

use App\Models\Celeb;
use App\Models\Genre;
use App\Models\Movie;
use App\Http\Requests\MovieRequest;
use App\Models\CelebMovie;
use App\Models\GenreMovie;
use App\Models\Review;
use App\Models\User;
use Database\Factories\UserFactory;

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
        ini_set('max_execution_time', 180);
        $data = [];
        $omdbApiKey = env('OMDB_API_KEY');
        $url = "https://www.omdbapi.com/?i=$imdbId&plot=full&apikey=$omdbApiKey";
        $data = Movie::apiCall($url);
        if (!empty($data)) {
            $movie = Movie::updateOrCreate([
                'imdb_id' => $imdbId
            ],[
                'title' => $data['Title'],
                'slug' => Movie::createSlug($data['Title']),
                'synopsis' => $data['Plot'],
                'year' => $data['Year'],
                'poster' => $data['Poster'],
                'duration' => intval($data['Runtime']),
                'imdb_id' => $imdbId,
                'language' => $data['Language'],
                'country' => $data['Country'],
                'imdb_rating' => !empty($data['imdbRating']) && $data['imdbRating'] != 'N/A' ? $data['imdbRating'] : null
            ]);

            // Get category
            if (!empty($data['Genre'])) {
                $genres = explode(', ', $data['Genre']);
                foreach ($genres as $g) {
                    $genre = Genre::where('name', $g)->first();
                    if (empty($genre)) {
                        $genre = Genre::create([
                            'name' => $g,
                            'slug' => Movie::createSlug($g)
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

            // Get review
            $rapidApiKey = env('RAPID_API_KEY');
            $rapidApiHost = 'imdb8.p.rapidapi.com';
            $apiHeader = [
                'X-RapidAPI-Host: '.$rapidApiHost,
                'X-RapidAPI-Key: '.$rapidApiKey
            ];
            $url = 'https://'.$rapidApiHost."/title/get-user-reviews?tconst=$imdbId";
            $data = Movie::apiCall($url, $apiHeader);
            if (!empty($data['reviews'])) {
                $userFactory = new UserFactory();
                foreach($data['reviews'] as $rv) {
                    if (!empty($rv['authorRating'])) {
                        $imdbUserId = $rv['author']['userId'];
                        $user = User::where('imdb_id', $imdbUserId)->first();
                        if (empty($user)) {
                            $userData = $userFactory->definition();
                            $userData['is_admin'] = 0;
                            $userData['imdb_id'] = $imdbUserId;
                            $user = User::create($userData);
                        }
                        Review::updateOrCreate([
                            'user_id' => $user->id,
                            'movie_id' => $movie->id
                        ], [
                            'user_id' => $user->id,
                            'movie_id' => $movie->id,
                            'title' => $rv['reviewTitle'],
                            'rating' => $rv['authorRating'],
                            'content' => $rv['reviewText'],
                        ]);
                    }
                }
            }

            // Get top cast
            $url = 'https://'.$rapidApiHost."/title/get-top-cast?tconst=$imdbId";
            $data = Movie::apiCall($url, $apiHeader);
            if (!empty($data)) {
                foreach($data as $char) {
                    $charId = str_replace("/name/", '', $char);
                    $charId = str_replace("/", '', $charId);
                    $charUrl = 'https://'.$rapidApiHost."/title/get-charname-list?id=$charId&tconst=$imdbId";
                    $charRes = Movie::apiCall($charUrl, $apiHeader);
                    if (!empty($charRes[$charId])) {
                        $celeb = Celeb::where('imdb_id', $charId)->first();
                        if (empty($celeb)) {
                            $celeb = Celeb::create([
                                'imdb_id' => $charId,
                                'name' => $charRes[$charId]['name']['name'],
                                'photo' => $charRes[$charId]['name']['image']['url']
                            ]);
                        }
                        CelebMovie::updateOrCreate([
                            'celeb_id' => $celeb->id,
                            'movie_id' => $movie->id,
                        ], [
                            'celeb_id' => $celeb->id,
                            'movie_id' => $movie->id,
                            'character_name' => $charRes[$charId]['charname'][0]['characters'][0]
                        ]);
                    }
                }
            }
            return redirect()->route('movies.show', ['slug' => $movie->slug, 'id' => $movie->id]);
        }
        print_r($data);
        die();
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

    public function show($slug, $id)
    {
        $movie = Movie::find($id);
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

        return redirect()->route('movies.show', ['slug' => $movie->slug, 'id' => $movie->id])
            ->with(['message' => 'Success! Movie has been updated.']);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('movies.index')
            ->with(['message' => 'Success! Movie has been deleted.']);
    }
}
