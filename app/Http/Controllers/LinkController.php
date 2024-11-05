<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller
{
    //https://magecomp.com/blog/create-url-shortener-laravel/?srsltid=AfmBOoqqq6-qFPX9TWAMxiQsZIYwyeMhsP3V9dRmNiShrJUjMmKSgYYm

    public function show($shortUrl): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $originalUrl = Link::getOriginalUrl($shortUrl);

        if (!$originalUrl) {
            abort(404);
        }

        return redirect($originalUrl);
    }
}
