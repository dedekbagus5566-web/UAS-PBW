<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, $id)
    {
        $user = auth()->user();

        // cari auction
        $auction = Auction::findOrFail($id);


        // cek apakah pemilik auction melakukan bid sendiri
        if ($auction->user_id == $user->id) {

            return response()->json([
                'message' => 'Anda tidak bisa melakukan bid pada auction sendiri'
            ], 403);

        }


        // cek status auction
        if ($auction->status !== 'active') {

            return response()->json([
                'message' => 'Auction sudah tidak aktif'
            ], 400);

        }

        $highestBid = Bid::where('auction_id', $id)
    ->orderBy('amount', 'desc')
    ->first();

$auction->winner_id = $highestBid->user_id;
$auction->status = 'closed';
$auction->save();

        // validasi input
        $request->validate([

            'amount' => [
                'required',
                'numeric',
                'min:' . ($auction->current_price + 1)
            ]

        ]);



        // simpan bid
        $bid = Bid::create([

            'auction_id' => $auction->id,

            'user_id' => $user->id,

            'amount' => $request->amount

        ]);



        // update harga auction

        $auction->update([

            'current_price' => $request->amount

        ]);



        return response()->json([

            'message' => 'Bid berhasil',

            'data' => $bid

        ],201);

    }
    public function index($id)
{
    $bids = Bid::where('auction_id', $id)
        ->with('user')
        ->orderBy('amount', 'desc')
        ->get();


    return response()->json([
        'data' => $bids
    ]);
}
}