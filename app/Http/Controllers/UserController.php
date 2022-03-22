<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // add friend
    public function addFriend(Request $request)
    {
        $from = Auth::user()->id;
        $to = User::where('username',$request['username'])->first()->id;
        if (Friend::where('user_id_1',$from)->exists()) {
            if (Friend::where('user_id_2',$to)->exists()) {
                Friend::where('user_id_1',$from)->where('user_id_2',$to)->delete();
                return null;
            } else {
                Friend::create([
                    'user_id_1' => $from,
                    'user_id_2' => $to,
                    'status' => false
                ]);
                return true;
            }
        } else {
            Friend::create([
                'user_id_1' => $from,
                'user_id_2' => $to,
                'status' => false
            ]);
            return true;
        }
    }

    // friend acc
    public function friendAcc(Request $request)
    {
        $from = User::where('username',$request['username'])->first()->id;
        Friend::where('user_id_2',Auth::user()->id)
        ->where('user_id_1',$from)
        ->update(['status' => true]);
        return null;
    }

    // friend rjc
    public function friendRjc(Request $request)
    {
        $from = User::where('username',$request['username'])->first()->id;
        Friend::where('user_id_2',Auth::user()->id)
        ->where('user_id_1',$from)
        ->delete();
        return null;
    }

    // update user info
    public function update(Request $request)
    {
        $rules = [
            'image' => 'image|file|max:5120',
            'name' => 'required|max:255',
            'bio' => 'max:1000'
        ];

        $validatedData = $request->validate($rules);

        if($request->file('image')) {
            if($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $validatedData['image'] = $request->file('image')->store('img-user');
        }

        User::where('id',Auth::user()->id)->update($validatedData);

        return redirect('/dashboard-'.Auth::user()->username)->with('edited', 'Akun berhasil disimpan');
    }
}
