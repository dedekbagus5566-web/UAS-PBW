<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;


class DashboardController extends Controller
{


public function index(Request $request)
{


$user = User::find(1);


$myAuctions = Auction::where(
'user_id',
$user->id
)
->latest()
->get();



$myBids = Bid::where(
'user_id',
$user->id
)
->with('auction')
->latest()
->get();



return response()->json([


'user'=>$user,


'totalAuction'=>$myAuctions->count(),


'totalBid'=>$myBids->count(),


'auctions'=>$myAuctions,


'bids'=>$myBids


]);


}



}