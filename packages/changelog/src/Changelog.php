<?php

namespace Neondigital\Changelog;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Neondigital\Changelog\Data\ChangelogEntry;
use Neondigital\Changelog\Mail\ReleaseMailer;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Finder\SplFileInfo;

class Changelog
{
    protected array $notifyEmails = [];

    public function getLatestRelease(): mixed
    {
        return $this->getReleases()->first();
    }

    /**
     * @return Collection<ChangelogEntry>
     */
    public function getReleases(): Collection
    {
        $files = File::allFiles(
            config('changelog.path')
        );

        $entries = collect();

        foreach ($files as $file) {
            $entries->push(
                ChangelogEntry::from([
                    'filename' => $file->getFilename(),
                    'date' => $this->getEntryDate($file),
                    ...$this->parseContents($file->getContents())
                ])
            );
        }

        return $entries->sortByDesc(
            fn ($entry) => $entry->date
        );
    }

    private function getEntryDate(SplFileInfo $file): Carbon
    {
        $filename = $file->getFilename();

        // Grab just the timestamp part
        preg_match('/^(\d{4}_\d{2}_\d{2}_\d{6})/', $filename, $matches);

        if ($matches) {
            $dateString = $matches[1];
            return now()->createFromFormat('Y_m_d_His', $dateString);
        }

        // Fallback to the modified time
        return now()->parse($file->getATime());
    }

    public function parseContents(string $contents): array
    {
        $contents = YamlFrontMatter::parse($contents);

        $matter = $contents->matter();

        return [
            'title' => $matter['title'],
            'changes' => collect(
                $matter['changes'] ?? []
            ),
            'body' => $contents->body() ? app(MarkdownRenderer::class)->toHtml($contents->body()) : null,
        ];
    }

    public function release(ChangelogEntry $release): void
    {
        $mailer = (new ReleaseMailer($release));

        $queue = config('changelog.queue');

        if ($queue) {
            $mailer->onQueue($queue);
            Mail::to($this->notifyEmails)->queue($mailer);
            return;
        }

        Mail::to($this->notifyEmails)->send($mailer);

    }

    public function notifyOnRelease(\Closure $callback): void
    {
        $this->notifyEmails = call_user_func($callback);
    }
}
