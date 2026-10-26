<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $params = $request->all();
        User::create($params);

        session()->flash('success', 'Пользователя ' . $request->title . ' добавлен');
        return redirect()->route('admin-users.index');
    }

    public function edit(User $admin_user)
    {
        return view('admin.users.form', compact('admin_user'));
    }

    public function update(UserRequest $request, User $admin_user)
    {
        $params = $request->all();

        $admin_user->update($params);
        session()->flash('success', 'Данные пользователя ' . $admin_user->title . ' обновлены');
        return redirect()->route('admin-users.index');
    }


    public function destroy(User $admin_user)
    {
        $admin_user->delete();
        session()->flash('success', 'Пользователь ' . $admin_user->title . ' удален из системы');
        return redirect()->route('admin-users.index');
    }

}
