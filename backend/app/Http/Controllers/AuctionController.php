<?php

namespace App\Http\Controllers;
use App\Models\Auction;
use App\Models\Bid;
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


    public function close($id)
{
    $auction = Auction::findOrFail($id);

    if ($auction->status === 'closed') {
        return response()->json([
            'message' => 'Lelang sudah ditutup'
        ], 400);
    }

    $highestBid = Bid::where('auction_id', $id)
        ->orderBy('amount', 'desc')
        ->first();

    if (!$highestBid) {
        return response()->json([
            'message' => 'Tidak ada bid'
        ], 422);
    }

    $auction->winner_id = $highestBid->user_id;
    $auction->status = 'closed';
    $auction->save();

    return response()->json([
        'message' => 'Lelang berhasil ditutup',
        'winner_id' => $auction->winner_id
    ]);
}

public function closeAuction($id)
{
    $auction = Auction::findOrFail($id);

    // ambil bid tertinggi
    $highestBid = Bid::where('auction_id', $id)
        ->orderBy('amount', 'desc')
        ->first();

    if (!$highestBid) {
        return response()->json([
            'message' => 'Tidak ada bid, lelang tidak bisa ditutup'
        ], 422);
    }

    // set winner
    $auction->winner_id = $highestBid->user_id;
    $auction->status = 'closed';
    $auction->save();

    return response()->json([
        'message' => 'Lelang berhasil ditutup',
        'winner' => $auction->winner_id
    ]);
}

 public function show($id)
{
    $auction = Auction::with([
        'user',
        'winner',
        'bids.user'
    ])->findOrFail($id);

    return response()->json([
        'data' => $auction
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
