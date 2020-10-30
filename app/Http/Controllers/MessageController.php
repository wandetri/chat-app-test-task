<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PrivateMessageEvent;
use App\Models\UserMessage;

class MessageController extends Controller
{
    public function conversation($userId){
        $id=Auth::id();
        $users = User::where('id','!=',$id)->get();
        $friendInfo = User::findOrFail($userId);
        $myInfo = User::find($id);
        $messages = Message::latest()->take(10)->with('user_message')->whereHas('user_message', function($q) use ($userId,$id){
            $q->where(function($query) use ($userId,$id){
                $query->where('sender_id',$userId)
                    ->where('receiver_id',$id);
            })->orWhere(function($query) use ($userId,$id){
                $query->where('sender_id',$id)
                    ->where('receiver_id',$userId);
            });
        })->get()->sortBy('created_at');
        $this->data['users'] = $users;
        $this->data['friendInfo'] = $friendInfo;
        $this->data['myInfo'] = $myInfo;
        $this->data['userId'] = $userId;
        $this->data['messages'] = $messages;

        return view('message.conversation', $this->data);

    }

    public function getMessagesConversation($userId, $lastDate){
        $id=Auth::id();
        $lasDates='2020-10-30 05:49:53';
        $data = Message::latest()->take(10)->with('user_message')->whereHas('user_message', function($q) use ($userId,$id){
            $q->where(function($query) use ($userId,$id){
                $query->where('sender_id',$userId)
                    ->where('receiver_id',$id);
            })->orWhere(function($query) use ($userId,$id){
                $query->where('sender_id',$id)
                    ->where('receiver_id',$userId);
            });
        })->where('created_at','<','2020-10-30 05:49:53')->get()->sortBy('created_at');

        return response()->json([
            'data'=>$data,
            'success'=>true,
            'message' =>'Message Received'
        ]);
    }

    public function sendMessage(Request $request){
        $request->validate([
            'message'=>'required',
            'receiver_id'=>'required'
        ]);
        $sender_id = Auth::id();
        $receiver_id = $request->receiver_id;

        $message = new Message();
        $message->message=$request->message;
        if($message->save()){
            try{
                $message->users()->attach($sender_id,['receiver_id'=>$receiver_id]);
                $sender = User::where('id','=',$sender_id)->first();
  
                $data =[];
                $data['sender_id'] = $sender_id;
                $data['sender_name'] = $sender->name;
                $data['receiver_id'] = $receiver_id;
                $data['message'] = $message->message;
                $data['created_at'] = $message->created_at;
                $data['message_id'] = $message->id;

                event(new PrivateMessageEvent($data));

                return response()->json([
                    'data'=>$data,
                    'success'=>true,
                    'message' =>'Message sent'
                ]);
            }catch(\Exception $e){
                $message->delete();
                // dd($e);
            }
        }
    }
}
