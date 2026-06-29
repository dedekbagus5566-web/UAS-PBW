<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{


    // =========================
    // CREATE BID
    // =========================

    public function store(Request $request)
    {


        $request->validate([

            'auction_id' => 'required|exists:auctions,id',

            'amount' => 'required|numeric'

        ]);





        // cek login

        if(!auth()->check()){

            return response()->json([

                'message'=>'Silahkan login terlebih dahulu'

            ],401);

        }






        $auction = Auction::find($request->auction_id);





        if(!$auction){


            return response()->json([

                'message'=>'Auction tidak ditemukan'

            ],404);


        }







        // cek status lelang


        if($auction->status !== 'active'){


            return response()->json([

                'message'=>'Lelang sudah ditutup'

            ],400);


        }








        // cek nominal bid


        if($request->amount <= $auction->current_price){



            return response()->json([

                'message'=>
                'Bid harus lebih tinggi dari harga sekarang'

            ],400);



        }








        // simpan bid


        $bid = Bid::create([


            'auction_id'=>$auction->id,


            'user_id'=>auth()->id(),


            'amount'=>$request->amount



        ]);








        // update harga tertinggi


        $auction->update([


            'current_price'=>$request->amount



        ]);









        return response()->json([


            'message'=>'Bid berhasil',


            'data'=>$bid



        ]);



    }








    // =========================
    // GET BID HISTORY
    // =========================


    public function index($id)
    {



        $bids = Bid::where(

            'auction_id',

            $id

        )

        ->with('user')

        ->orderBy('amount','desc')

        ->get();







        return response()->json([


            'data'=>$bids



        ]);



    }
public function myBids()
{

    $bids = Bid::where(
        'user_id',
        auth()->id()
    )
    ->with('auction')
    ->latest()
    ->get();



    return response()->json([

        'data'=>$bids

    ]);

}
}