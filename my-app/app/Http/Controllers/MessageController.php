<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;

class MessageController extends Controller
{
    public function createMessage(Request $request){
        $user = $request->user();

        $newMessage=Message::create([
            "message"=>$request->message,
            "user_id"=>$user->id,
            "cluster_id"=>$request->cluster_id
        ]);
    }

    public function getMessages(Request $request){
        $clusterId=$request->id;
        $messages=Message::select("user_id","message","created_at")->whereCluster_id($clusterId)->get();


        return $messages;



    }


    public function deleteMessages(Request $request){
        $id=$request->message_id;
        Message::find($id)-delete();
    }
}
