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
        $html = view('movies.export', [
            'css' => file_get_contents(public_path('build/assets/app.css')),
            'movies' => Movie::orderBy('position', 'ASC')->get()->groupBy('tier'),
            'tiers' => collect(config('app.tiers'))
        ])->render();
        Storage::put('movies.html', $html);
        $this->info('Page created successfully');
    }
}
