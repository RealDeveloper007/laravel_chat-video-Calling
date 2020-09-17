<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;

class ChatController extends Controller
{
    // Send Message Function
    public function SendMessage(Request $request)
    {
        $ChatModel = new Chat();
        $ChatModel->to_id = $request->to_id;
        $ChatModel->from_id = \Auth::User()->id;
        $ChatModel->body = $request->body;
        $ChatModel->date = date('Y-m-d');
        $ChatModel->time = date('H:i:s');
        $ChatModel->save();

        $data['time'] = date('H:i:s');
        $data['date'] = $this->get_day_name(date('Y-m-d'));
        return response()->json($data);
    }

    // Get New Message Function
    public function GetNewMessage(Request $request)
    {
        if ($request->method() === 'GET') 
        {
        
             $yes = 'y';
             $message = Chat::where(['to_id'=>\Auth::User()->id,'is_new'=>$yes])->get();
             if($message)
             {
                            $detail = array();
                            $i = 0;
                            foreach($message as $AllMessages)
                            {
                                // Update Message Status
                                $ChatModel = Chat::find($AllMessages->id);
                                $ChatModel->is_new = 'n';
                                $ChatModel->save();

                                $detail[$i]['id']                   = $AllMessages->id;
                                $detail[$i]['profile_img']          = $AllMessages->fromdetails->profile_img;
                                $detail[$i]['from_user_info']       = $AllMessages->fromdetails->name;
                                $detail[$i]['from_id']              = $AllMessages->from_id;
                                $detail[$i]['body']                 = $AllMessages->body;
                                $detail[$i]['date']                 = $this->get_day_name($AllMessages->date);
                                $detail[$i]['time']                 = $AllMessages->time;

                                $i++;
                            }
                
                            return response()->json($detail);
                                exit();
             }
        }
    }

    // Get Date Format
    private function get_day_name($getdate) 
    {

            $Today = date('Y-m-d');
        
            $Yesterday = date('Y-m-d', strtotime('-1 day', strtotime($Today)));
            
            if($getdate == $Today) 
            {
              $date = 'Today';
            } else if($getdate == $Yesterday) {
              $date = 'Yesterday';
            } else {
                $date = date('d-M-Y', strtotime($getdate));
            }                                                 
            return $date;
    }
}
