<?php

namespace Neondigital\Changelog\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Neondigital\Changelog\Facades\Changelog;

class ReleaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers a release and sends mailers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $latestRelease = Changelog::getLatestRelease();
        $manifest = Storage::json('changelog.manifest.json');

        if ($manifest && ($manifest['filename'] ?? null) == $latestRelease->filename) {
            $this->info('No new release to notify');
            return;
        }

        Storage::put(
            'changelog.manifest.json',
                json_encode(['filename' => $latestRelease->filename])
        );

        Changelog::release($latestRelease);


        dd($manifest);
    }
}
