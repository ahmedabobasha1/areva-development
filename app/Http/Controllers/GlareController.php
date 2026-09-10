<?php

namespace App\Http\Controllers;

use App\Models\GlareLanding;
use App\Support\LocaleUrl;
use App\Support\SeoBuilder;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GlareController extends Controller
{
    public function __invoke(string $locale): View|Response
    {
        $glare = GlareLanding::current();

        if (! $glare->is_published) {
            abort(404);
        }

        return view('glare', [
            'glare' => $glare,
            'hideSiteHeader' => true,
            'bodyClass' => 'lp-home',
            'langSwitchUrls' => [
                'en' => LocaleUrl::glare('en'),
                'ar' => LocaleUrl::glare('ar'),
            ],
            'seo' => SeoBuilder::forGlare($locale, $glare),
        ]);
    }
}
