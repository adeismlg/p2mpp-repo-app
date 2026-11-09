<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\StudyProgramAccreditation;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function show(Page $page): View
    {
        abort_unless($page->is_published, 404);

        if ($page->slug === 'akreditasi-program-studi') {
            $accreditations = StudyProgramAccreditation::query()
                ->latestCurrentlyValid()
                ->orderBy('study_level')
                ->orderBy('name')
                ->get();

            return view('pages.study-program-accreditations', compact('page', 'accreditations'));
        }

        return view('pages.show', compact('page'));
    }
}
