<?php

namespace App\Http\Controllers;

use App\Models\Instructors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $instructors = Instructors::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                             ->orWhere('keahlian', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('pages.instructors.index', compact('instructors', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.instructors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:instructors,email',
            'telepon'  => 'required|string|max:20',
            'keahlian' => 'required|string|max:255',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh instruktur lain.'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('instructors', 'public');
        }

        Instructors::create($data);

        return redirect()->route('instructors.index')->with('success', 'Data instruktur berhasil ditambahkan!');
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
        $instructor = Instructors::findOrFail($id);
        return view('pages.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $instructor = Instructors::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:instructors,email,' . $id,
            'telepon'  => 'required|string|max:20',
            'keahlian' => 'required|string|max:255',
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($instructor->foto) {
                Storage::disk('public')->delete($instructor->foto);
            }
            $data['foto'] = $request->file('foto')->store('instructors', 'public');
        }

        $instructor->update($data);

        return redirect()->route('instructors.index')->with('success', 'Data instruktur berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instructor = Instructors::findOrFail($id);

        // Pengaman agar data event pelatihan tidak error/patah relasi
        if ($instructor->events()->exists()) {
            return redirect()->route('instructors.index')->with('error', 'Instruktur gagal dihapus karena masih memegang beberapa kelas pelatihan!');
        }

        if ($instructor->foto) {
            Storage::disk('public')->delete($instructor->foto);
        }

        $instructor->delete();

        return redirect()->route('instructors.index')->with('success', 'Data instruktur berhasil dihapus!');
    }
}
