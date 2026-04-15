<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulletinController extends Controller
{
    public function index()
    {
        $bulletins = Bulletin::orderBy('published_date', 'desc')->paginate(10);
        return view('bulletins.index', compact('bulletins'));
    }

    public function download(Bulletin $bulletin)
    {
        $ext = pathinfo($bulletin->file_path, PATHINFO_EXTENSION);
        return Storage::disk('public')->download($bulletin->file_path, $bulletin->title . '.' . $ext);
    }

    // Admin Methods
    public function adminIndex()
    {
        $bulletins = Bulletin::orderBy('published_date', 'desc')->paginate(20);
        return view('admin.bulletins.index', compact('bulletins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,jpg,jpeg,png,webp|max:10240', // 10MB limit
            'published_date' => 'required|date',
        ]);

        $path = $request->file('file')->store('bulletins', 'public');

        Bulletin::create([
            'title' => $request->title,
            'file_path' => $path,
            'published_date' => $request->published_date,
        ]);

        return back()->with('success', 'Bulletin uploaded successfully.');
    }

    public function destroy(Bulletin $bulletin)
    {
        Storage::disk('public')->delete($bulletin->file_path);
        $bulletin->delete();
        return back()->with('success', 'Bulletin deleted successfully.');
    }
}
