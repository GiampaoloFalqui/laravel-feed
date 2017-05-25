<?php

namespace Spatie\Feed;

use Illuminate\View\View;
use Spatie\Feed\Helpers\Path;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class LumenFeedServiceProvider extends LaravelFeedServiceProvider
{
    protected function loadsViews()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => $this->resourcePath('views/vendor/laravel-feed'),
        ], 'views');
    }

    protected function registerRouteMacro()
    {
        // dd(config('laravel-feed.feeds'));

        foreach (config('laravel-feed.feeds') as $index => $feedConfiguration) {
            $this->app->get(
                Path::merge('', $feedConfiguration['url']),
                ['as' => "spatieLaravelFeed{$index}", 'uses' => '\Spatie\Feed\Http\FeedController@feed']
            );
        }
    }

    private function resourcePath($path = '')
    {
        return $this->app->basePath() . '/resources' . ($path ? '/' . $path : $path);
    }
}