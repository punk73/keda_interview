<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //
    public function index(Request $request){
        // show all messages
    }
    
    public function send(Request $request){
        // send to specific another user_id
        $request->validate([
            'recipient_id' => 'required|integer',
            'content' => 'required'
        ]);

        $senderId = Auth::user()->id;

        $msg = [
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
            'sender_id' => $senderId
        ];

        $message = new Message($msg);
        $message->save();

        return [
            'success' => true,
            'message' => 'message sent!',
            'data' => $message
        ];
    }
}
