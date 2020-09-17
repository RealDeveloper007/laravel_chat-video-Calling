<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Chat;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Users = User::where('id', '<>', Auth::User()->id)->get()->toArray();

        $UserChats = array();
        $i = 0;
        foreach ($Users as $AllUserChats) {
            $UserChats[$i]                    = $AllUserChats;
            $UserChats[$i]['messages']        = $this->TwoUserMessages($AllUserChats['id']);
            $i++;
        }

        return view('chat.index', ['users' => $Users, 'userchats' => $UserChats]);
    }

    // Find the  messages between two users
    private function TwoUserMessages($UserId)
    {
        $SessionUser = Auth::User()->id;
        $ChatMessages = Chat::where(function ($q) use ($UserId, $SessionUser) {
            $q->Where('from_id', $UserId)
                ->orWhere('to_id', $SessionUser)
                ->Where('from_id', $SessionUser)
                ->orWhere('to_id', $UserId);
        })->get();

        $AllMessges = array();
        $i = 0;
        foreach ($ChatMessages as $Chats) {
            $AllMessges[$i]['id']             =  $Chats->id;
            $AllMessges[$i]['from']           =  $Chats->fromdetails->name;
            $AllMessges[$i]['from_id']        =  $Chats->from_id;
            $AllMessges[$i]['from_image']     =  $Chats->fromdetails->profile_img;
            $AllMessges[$i]['to']             =  $Chats->todetails->name;
            $AllMessges[$i]['to_id']          =  $Chats->to_id;
            $AllMessges[$i]['to_image']       =  $Chats->todetails->profile_img;
            $AllMessges[$i]['body']           =  $Chats->body;
            $AllMessges[$i]['date']           =  date('d,M', strtotime($Chats->date));
            $AllMessges[$i]['time']           =  $Chats->time;

            $i++;
        }

        return $AllMessges;
    }
}
