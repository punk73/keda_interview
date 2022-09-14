<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class MessageController extends Controller
{
    //
    public function index(Request $request){
        // show message with auth id as sender_id, and latest message to recipient_id
        $sender = Auth::user();

        $conversations = $sender->conversations();

        return [
            'success' => true,
            'message' => 'conversations history',
            'sender' => $sender,
            'data' => $conversations
        ];
    }

    public function show($sender_id) {
        // return $recipient_id;
        $recipient = Auth::user();

        $messages = Message::where('sender_id', $sender_id)
            ->where('recipient_id', $recipient->id)
            ->orderBy('id', 'desc')
            ->get();

        return [
            'success' => true,
            'message' => "fetch from {$sender_id}",
            'sender'  => User::findOrFail($sender_id), 
            'data' => $messages
        ];
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
