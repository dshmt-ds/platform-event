<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Events;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = $request->input('category');

        $categories = Categories::withCount('events')->get();

        $events = Events::with(['category', 'instructor'])
            ->where('status', 'published')
            ->when($selectedCategory, function ($query, $selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('events', 'categories', 'selectedCategory'));
    }
}
