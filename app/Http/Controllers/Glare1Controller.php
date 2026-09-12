<?php

namespace App\Http\Controllers;

use App\Models\Glare1Landing;
use App\Support\LocaleUrl;
use App\Support\SeoBuilder;
use Illuminate\Http\Response;
use Illuminate\View\View;

class Glare1Controller extends Controller
{
    public function __invoke(string $locale): View|Response
    {
        $glare = Glare1Landing::current();

        if (! $glare->is_published) {
            abort(404);
        }

        return view('glare1', [
            'glare' => $glare,
            'hideSiteHeader' => true,
            'bodyClass' => 'lp-home',
            'langSwitchUrls' => [
                'en' => LocaleUrl::glare1('en'),
                'ar' => LocaleUrl::glare1('ar'),
            ],
            'seo' => SeoBuilder::forGlare1($locale, $glare),
        ]);
    }
}
