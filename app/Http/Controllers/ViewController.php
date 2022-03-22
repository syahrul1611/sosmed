<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function index()
    {
        $friendsList = Friend::where('user_id_1',Auth::user()->id)->where('status',true)->get('user_id_2')->toArray();
        for ($i=0; $i < count($friendsList); $i++) { 
            $data[$i] = $friendsList[$i];
        }
        $data[count($friendsList)+1] = ['user_id_2' => Auth::user()->id];
        return view('home',[
            'posts' => Post::whereIn('user_id',$data)->get()
        ]);
    }

    public function explore()
    {
        if (request('keyword')) {
            $friendsList = Friend::where('user_id_1',Auth::user()->id)->get('user_id_2')->toArray();
            for ($i=0; $i < count($friendsList); $i++) { 
                $data[$i] = $friendsList[$i]['user_id_2'];
            }
            $data[count($friendsList)+1] = Auth::user()->id;
            $users = User::search(request('keyword'))->get()->except($data);
        } else { $users = null; }
        return view('explore',[
            'posts' => Post::latest()->get(),
            'users' => $users
        ]);
    }

    public function list()
    {
        if (request('keyword')) {
            $search = User::search(request('keyword'))->get();
            $list = array();
            for ($i=0; $i < count($search); $i++) { 
                $list[$i] = $search[$i]['id'];
            }
            if (!$list) {
                $data = null;
            } else {
                $data = Friend::where('user_id_1',Auth::user()->id)->where('status',true)->whereIn('user_id_2',$list)->get();
            }
        } else {
            $data = Friend::where('user_id_1',Auth::user()->id)->where('status',true)->latest()->get();
            // dd($friendsList);
        }
        return view('list',[
            'friends' => $data
        ]);
    }

    public function chat(User $user)
    {
        $msg_no = collect([Auth::user()->id,$user->id])->sort()->implode('-');
        return view('chat',[
            'to' => $user,
            'chats' => Chat::where('msg_no', $msg_no)->latest()->get()
        ]);
    }

    public function dashboard(User $user)
    {
        return view('dashboard',[
            'user' => $user
        ]);
    }
}
