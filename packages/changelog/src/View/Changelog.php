<?php

namespace Neondigital\Changelog\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Changelog extends Component
{
    public function render(): View
    {
        return view('changelog::changelog', [
            'releases' => \Neondigital\Changelog\Facades\Changelog::getReleases(),
        ]);
    }
}
