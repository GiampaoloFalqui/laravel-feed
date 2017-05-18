<?php

namespace Spatie\Feed\Http;

use Illuminate\Http\Request;
use Spatie\Feed\Feed;
use Illuminate\Routing\Controller;

class FeedController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function feed()
    {
        $configuration = $this->getFeedConfiguration();

        // Overwrite the relative feed url with the request's absolute url
        $configuration['url'] = $this->request->fullUrl();

        return (new Feed($configuration))->getFeedResponse();
    }

    protected function getFeedConfiguration(): array
    {
        $feeds = config('laravel-feed.feeds');

        $feedIndex = (int) str_replace('spatieLaravelFeed', '', $this->currentRouteName());

        return $feeds[$feedIndex] ?? abort(404);
    }

    private function currentRouteName()
    {
        // Laravel 5.x
        try {

            return app('router')->currentRouteName();

        // Lumen 5.x
        } catch (\Exception $e) {

            foreach (app()->getRoutes() as $route) {
                if (strpos($route['uri'], $this->request->path()) !== false) {
                    return $route['action']['as'];
                }
            }

        }
    }
}