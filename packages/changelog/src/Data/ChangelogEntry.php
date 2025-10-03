<?php

namespace Neondigital\Changelog\Data;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ChangelogEntry extends Data
{
    public function __construct(
        public string $filename,
        public Carbon $date,
        public string $title,
        /** @var Collection<ChangelogEntryChange> */
        public Collection $changes,
        public ?string $body = null,
    ) {}
}
