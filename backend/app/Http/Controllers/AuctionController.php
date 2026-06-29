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

        $auctions = Auction::with('user')
            ->latest()
            ->get();


        return response()->json([

            'message'=>'Daftar lelang berhasil dimuat',

            'data'=>$auctions

        ]);

    }




    public function show($id)
    {

        $auction = Auction::with([

            'user',
            'winner',
            'bids.user'

        ])
        ->findOrFail($id);



        return response()->json([

            'data'=>$auction

        ]);

    }





public function store(Request $request)
{

    $request->validate([

        'title'=>'required',

        'description'=>'nullable',

        'price'=>'required|numeric',

        'category'=>'required',

        'image'=>'nullable|image|max:2048'

    ]);



    $imageUrl = null;



    if($request->hasFile('image')){


        $path = $request
        ->file('image')
        ->store('auctions','public');


        $imageUrl =
        asset('storage/'.$path);


    }



    $auction = Auction::create([


        'user_id'=>auth()->id(),


        'title'=>$request->title,


        'description'=>$request->description,


        'price'=>$request->price,


        'current_price'=>$request->price,


        'category'=>$request->category,


        'image_url'=>$imageUrl,


        'status'=>'active'


    ]);




    return response()->json([

        'message'=>'Lelang berhasil',

        'data'=>$auction

    ]);

}







    public function close($id)
    {


        $auction = Auction::findOrFail($id);



        $highestBid = Bid::where(
            'auction_id',
            $id
        )
        ->orderBy(
            'amount',
            'desc'
        )
        ->first();




        if(!$highestBid){


            return response()->json([

                'message'=>'Tidak ada bid'

            ],422);


        }




        $auction->winner_id =
            $highestBid->user_id;


        $auction->status =
            'closed';



        $auction->save();




        return response()->json([

            'message'=>'Lelang ditutup'

        ]);

    }




    public function myAuctions()
    {


        $auctions = Auction::where(

            'user_id',

            Auth::id()

        )
        ->latest()
        ->get();



        return response()->json([

            'data'=>$auctions

        ]);

    }



}