<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function signup()
    {
      return view('user.signup');
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required|max:50',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|confirmed|min:6'
      ]);

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
      return redirect()->route('user.show', [$user]);
      // return redirect()->route('user.show', [$user->id]);
    }

    public function signin()
    {
      return view('user.signin');
    }

    public function signout()
    {
      return view('user.signout');
    }

    public function show(User $user)
    {
      return view('user.show', compact('user'));
    }

}
