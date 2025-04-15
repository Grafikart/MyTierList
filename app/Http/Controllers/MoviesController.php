<?php

namespace App\Http\Controllers;

use App\Domain\Simkl\SimklAPI;
use App\Models\Movie;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MoviesController extends Controller
{
    public function index()
    {
        return view('movies.index', [
            'movies' => Movie::orderBy('position', 'ASC')->get(),
            'tiers' => collect(config('app.tiers')),
        ]);
    }

    public function export()
    {
        return view('movies.export', [
            'movies' => Movie::orderBy('position', 'ASC')->get()->groupBy('tier'),
            'years' => Movie::years(),
            'current_year' => now()->year,
            'tiers' => collect(config('app.tiers')),
            'web' => true,
        ]);
    }

    public function sync(Request $request, SimklAPI $api)
    {
        $code = $request->query->get('code');

        if (! $code) {
            return redirect(
                $api->start(route('sync'))
            );
        }

        $api->token($code);
        $lastMovie = Movie::orderBy('created_at', 'DESC')->first();
        $date = $lastMovie->created_at->subDay() ?? now()->startOfYear()->subDay();

        $movies = $api->watchList('movies', $date);
        foreach ($movies as $movie) {
            Movie::firstOrCreate([
                'imdb_id' => $movie['movie']['ids']['imdb'],
            ], [
                'title' => $movie['movie']['title'],
                'poster' => $movie['movie']['poster'],
                'created_at' => $movie['last_watched_at'],
            ]);
        }

        $shows = $api->watchList('shows', $date);
        foreach ($shows as $show) {
            Movie::firstOrCreate([
                'imdb_id' => $show['show']['ids']['imdb'],
            ], [
                'title' => $show['show']['title'],
                'poster' => $show['show']['poster'],
                'created_at' => $show['last_watched_at'],
            ]);
        }

        return to_route('home');
    }

    /**
     * Update the tier of a movie
     */
    public function tier(Movie $movie, string $tier)
    {
        $movie->tier = $tier;
        $movie->save();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Move a movie
     */
    public function move(Request $request)
    {
        $positions = $request->json('positions');
        foreach ($positions as $position) {
            Movie::where('id', $position['id'])->update([
                'position' => $position['position'],
            ]);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
