<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend(HtmlSanitizerConfig::class, function (HtmlSanitizerConfig $config): HtmlSanitizerConfig {
            return $config
                ->allowElement('iframe', [
                    'src',
                    'width',
                    'height',
                    'title',
                    'allow',
                    'allowfullscreen',
                    'frameborder',
                    'loading',
                    'referrerpolicy',
                    'class',
                    'style',
                ])
                ->allowElement('video', [
                    'src',
                    'controls',
                    'playsinline',
                    'preload',
                    'poster',
                    'width',
                    'height',
                    'class',
                    'style',
                    'title',
                ])
                ->allowElement('source', ['src', 'type'])
                ->allowElement('figure', ['class', 'style'])
                ->allowAttribute('data-config', allowedElements: '*');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['partials.header', 'partials.footer'], function ($view): void {
            $view->with(
                'navCategories',
                Category::query()->active()->orderBy('sort')->get(),
            );
        });
    }
}
