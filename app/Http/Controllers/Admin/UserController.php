<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $request->string('role')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        session()->flash('success', 'Пользователь '.$request->string('name').' добавлен');

        return redirect()->route('admin-users.index');
    }

    public function edit(User $admin_user)
    {
        return view('admin.users.form', compact('admin_user'));
    }

    public function update(UserRequest $request, User $admin_user)
    {
        $role = $request->string('role')->toString();

        if ($this->isLastAdmin($admin_user) && $role !== 'admin') {
            return back()
                ->withErrors(['role' => 'Нельзя снять роль с последнего администратора'])
                ->withInput();
        }

        $data = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password')->toString());
        }

        $admin_user->update($data);
        session()->flash('success', 'Данные пользователя '.$admin_user->name.' обновлены');

        return redirect()->route('admin-users.index');
    }

    public function destroy(User $admin_user)
    {
        if ($admin_user->is(auth()->user())) {
            return back()->with('warning', 'Нельзя удалить свою учетную запись');
        }

        if ($this->isLastAdmin($admin_user)) {
            return back()->with('warning', 'Нельзя удалить последнего администратора');
        }

        $name = $admin_user->name;
        $admin_user->delete();
        session()->flash('success', 'Пользователь '.$name.' удален из системы');

        return redirect()->route('admin-users.index');
    }

    private function isLastAdmin(User $user): bool
    {
        return $user->role === 'admin' && User::where('role', 'admin')->count() === 1;
    }
}
