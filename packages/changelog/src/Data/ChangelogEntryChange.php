<?php

namespace Neondigital\Changelog\Data;

use Spatie\LaravelData\Data;

class ChangelogEntryChange extends Data
{
    public function __construct(
        public string $type,
        public string $title,
        public ?string $body,
    ) {}
}
