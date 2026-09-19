<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Document;
use App\Models\News;
use App\Models\Slider;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        $latestNews = News::whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $courses = Course::orderBy('name')->take(4)->get();

        $latestDocuments = Document::public()
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('sliders', 'latestNews', 'courses', 'latestDocuments'));
    }
}
