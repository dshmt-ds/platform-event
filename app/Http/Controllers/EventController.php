<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Events;
use App\Models\Instructors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $events = Events::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('lokasi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('pages.events.index', compact('events', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        $instructors = Instructors::all();

        return view('pages.events.create', compact('categories', 'instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'instructor_id' => 'required',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tanggal'       => 'required|date|after_or_equal:today',
            'jam'           => 'required',
            'lokasi'        => 'required|string|max:255',
            'kuota'         => 'required|integer|min:1',
            'harga'         => 'required|numeric|min:0',
            'poster'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'        => 'required|in:draft,published,cancelled',
        ]);

        $data = $request->all();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Events::create($data);

        return redirect()->route('events.index')->with('success', 'Event pelatihan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Events::findOrFail($id);
        $categories = Categories::all();
        $instructors = User::all();

        return view('pages.events.edit', compact('event', 'categories', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Events::findOrFail($id);

        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'instructor_id' => 'required',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'lokasi'        => 'required|string|max:255',
            'kuota'         => 'required|integer|min:1',
            'harga'         => 'required|numeric|min:0',
            'poster'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'        => 'required|in:draft,published,cancelled',
        ]);

        $data = $request->all();

        if ($request->hasFile('poster')) {
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Event pelatihan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Events::findOrFail($id);

        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event pelatihan berhasil dihapus!');

    }
}
