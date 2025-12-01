<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreatePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the tier list into an HTML page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $movies = Movie::orderBy('position', 'ASC')->get();
        $html = view('movies.export', [
            'css' => file_get_contents(public_path('build/assets/app.css')),
            'movies' => $movies->sortBy('created_at', SORT_NATURAL)->reverse(),
            'movies_by_tiers' => $movies->groupBy('tier'),
            'years' => Movie::years(),
            'current_year' => now()->year,
            'tiers' => collect(config('app.tiers')),
            'web' => true,
        ])->render();
        Storage::put('movies.html', $html);
        $this->info('Page created successfully');
    }
}
