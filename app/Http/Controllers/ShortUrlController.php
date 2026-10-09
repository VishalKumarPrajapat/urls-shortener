<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    /* List URLs */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) { 
            $shortUrls = ShortUrl::orderByDesc('id')->get();
        } elseif ($user->isAdmin()) { 
            $shortUrls = ShortUrl::where('company_id', $user->company_id)
                ->orderByDesc('id')
                ->get();
        } elseif ($user->isMember()) { 
            $shortUrls = ShortUrl::where('user_id', $user->id)
                ->orderByDesc('id')
                ->get();
        } else {
            abort(403);
        }
        return view('short_urls.index', compact('shortUrls', 'user'));
    }

    /* Show create form */
    public function create(Request $request)
    {
        $user = $request->user(); 
        if ($user->isSuperAdmin()) {
            abort(403, 'SuperAdmin cannot create short urls.');
        }
        return view('short_urls.create');
    }

    /* Store short url */
    public function store(Request $request)
    {
        $user = $request->user(); 
        if ($user->isSuperAdmin()) {
            abort(403, 'SuperAdmin cannot create short urls.');
        }

        $data = $request->validate([
            'original_url' => 'required|url',
        ]);

        ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $data['original_url'],
            'short_code' => Str::random(8),
        ]);

        return redirect()->route('short-urls.index')
            ->with('status', 'Short URL created.');
    }
}