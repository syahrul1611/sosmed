<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $from = Auth::user()->id;
        $to = User::where('username',$request['username'])->first()->id;
        $msg_no = collect([$from,$to])->sort()->implode('-');
        $validatedData = $request->validate([
            'message' => 'required'
        ]);
        $validatedData['from'] = $from;
        $validatedData['to'] = $to;
        $validatedData['msg_no'] = $msg_no;
        Chat::create($validatedData);
        return null;
    }

    public function get(Request $request)
    {
        $id1 = Auth::user()->id;
        $id2 = User::where('username',$request['username'])->first()->id;
        $msg_no = collect([$id1,$id2])->sort()->implode('-');
        $messages = Chat::where('msg_no', $msg_no)->latest()->get();
        $data = array();
        for ($i=0; $i < count($messages); $i++) { 
            $data[$i] = [
                'from' => $messages[$i]['from'],
                'to' => $messages[$i]['to'],
                'message' => $messages[$i]['message'],
                'date' => $messages[$i]['created_at']->getTimestampMs()
            ];
        }
        return $data;
    }
}
