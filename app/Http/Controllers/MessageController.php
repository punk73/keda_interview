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
            'us' => $sender,
            'data' => $conversations
        ];
    }

    public function show($sender_id) {
        // return $recipient_id;
        $recipient = Auth::user();
        $only = ['email', 'id', 'user_type_id'];
        try {
            //code...
            $them = User::findOrFail($sender_id)
                ->only($only);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => "user with id $sender_id not found"
            ], 404);
        }

        $messages = Message::where(function($q) use($recipient, $sender_id) {
                // ambil chat yang kita kirim 
                $q->where('recipient_id', $recipient->id)
                ->Where('sender_id', $sender_id );
            })
            ->orWhere(function ($q) use ($recipient, $sender_id) {
                // ambil chat yg kita terima
                $q->where('recipient_id', $sender_id)
                    ->Where('sender_id', $recipient->id );
            })
            ->orderBy('id', 'desc')
            ->get();

        return [
            'success' => true,
            'message' => "fetch from {$sender_id}",
            'us'    => $recipient->only($only),
            'them'  => $them, 
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
        $message->conversation_id = $this->getConversationId($senderId, $request->recipient_id);
        $message->save();

        return [
            'success' => true,
            'message' => 'message sent!',
            'data' => $message
        ];
    }

    public function getConversationId($a, $b){
        if($a <= $b) {
            return $a . "-" . $b;
        }

        return $b ."-". $b;
    }
}
