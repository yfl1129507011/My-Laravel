<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UserController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth', [
        // 未登录用户 默认将会被重定向到 /login 登录页面
        'except' => ['show', 'signup', 'store', 'index', 'confirmEmail']
      ]);

      $this->middleware('guest', [
        'only' => ['signup']
      ]);
    }

    public function index()
    {
      // $users = User::all();
      // 分页
      $pageNum = 10;
      $users = User::paginate($pageNum);
      return view('user.index', compact('users'));
    }

    //删除用户
    public function destroy(User $user)
    {
      $this->authorize('destroy', $user);
      $user->delete();
      session()->flash('success', '成功删除用户！');
      return back();
    }

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

      // Auth::login($user);
      // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
      // return redirect()->route('user.show', [$user]);
      $this->sendEmailConfirm($user);
      session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
      return redirect('/');
      // return redirect()->route('user.show', [$user->id]);
    }

    // 发送确认邮件
    protected function sendEmailConfirm($user)
    {
      $view = 'emails.confirm';
      $data = compact('user');
      $from = 'fenlon-yfl@qq.com';
      $name = 'fenlon';
      $to = $user->email;
      $subject = "感谢注册 Fenlon 应用！请确认你的邮箱。";

      Mail::send($view, $data, function($message) use ($from, $name, $to, $subject){
        $message->from($from, $name)->to($to)->subject($subject);
      });
    }

    // 邮件确认并登陆
    public function confirmEmail($token)
    {
      $user = User::where('activation_token', $token)->firstOrFail();

      $user->activated = true;
      $user->activation_token = null;
      $user->save();

      Auth::login($user);
      session()->flash('success', '恭喜你，激活成功！');
      return redirect()->route('user.show',[$user]);
    }


    public function show(User $user)
    {
      return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
      //授权策略定义  只能编辑自己的信息
      $this->authorize('update', $user);
      return view('user.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
      $this->validate($request,[
        'name' => 'required|max:50',
        'password' => 'nullable|confirmed|min:6'
      ]);
      //授权策略定义
      $this->authorize('update', $user);

      $data = [];
      $data['name'] = $request->name;
      if($request->password){
        $data['password'] = bcrypt($request->password);
      }
      $user->update($data);

      session()->flash('success', '修改成功');
      return redirect()->route('user.show', $user->id);
    }

}
