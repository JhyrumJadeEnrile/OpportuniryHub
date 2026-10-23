<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $listings = Listing::with('user:id,name,member_type')
            ->available()
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('sell.index', [
            'listings'   => $listings,
            'categories' => Listing::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        return view('sell.create', [
            'categories' => Listing::CATEGORIES,
            'conditions' => Listing::CONDITIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'category'    => ['required', 'in:'.implode(',', array_keys(Listing::CATEGORIES))],
            'price'       => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'condition'   => ['nullable', 'in:'.implode(',', array_keys(Listing::CONDITIONS))],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('listings', 'public');
        }
        unset($data['image']);

        $request->user()->listings()->create($data);

        return redirect()->route('sell.index')->with('status', 'Your item is now on the sell wall.');
    }

    public function markSold(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($listing->user_id === $request->user()->id, 403);

        $listing->update(['status' => 'sold']);

        return back()->with('status', 'Marked as sold.');
    }

    public function destroy(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($listing->user_id === $request->user()->id, 403);

        if ($listing->image_path) {
            Storage::disk('public')->delete($listing->image_path);
        }
        $listing->delete();

        return redirect()->route('sell.index')->with('status', 'Listing removed.');
    }
}
