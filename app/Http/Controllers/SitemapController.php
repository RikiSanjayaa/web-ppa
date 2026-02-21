<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\NewsPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $articles = NewsPost::published()->orderBy('published_at', 'desc')->get();
        $documents = Document::published()->orderBy('published_at', 'desc')->get();

        return response()->view('public.sitemap', [
            'articles' => $articles,
            'documents' => $documents,
        ])->header('Content-Type', 'text/xml');
    }
}
