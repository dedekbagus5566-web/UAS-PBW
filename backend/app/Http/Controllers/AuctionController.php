<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with('user')->latest()->get();

        return response()->json([
            'message' => 'Daftar lelang berhasil dimuat',
            'data' => $auctions,
        ]);
    }

    public function show(Auction $auction)
    {
        $auction->load('user');

        return response()->json([
            'message' => 'Detail lelang berhasil dimuat',
            'data' => $auction,
        ]);
    }

    public function myAuctions(Request $request)
    {
        $auctions = Auction::where('user_id', Auth::id())
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Daftar lelang Anda berhasil dimuat',
            'data' => $auctions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'category' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url'],
        ]);

        $auction = Auction::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'category' => $data['category'],
            'image_url' => $data['image_url'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Lelang berhasil dibuat',
            'data' => $auction,
        ], 201);
    }
}
