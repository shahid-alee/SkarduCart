<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function users()
    {
        $users = User::paginate(10);

        return view('admin.user.users', compact('users'));
    }

    public function create()
    {

        return view('admin.user.adduser');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.user.users')
            ->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.adduser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.user.users')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()
            ->route('admin.user.users')
            ->with('success', 'User deleted successfully.');
    }


  public function profile()
{
    $user = Auth::user(); 

    return view('user.profile', compact('user'));
}

public function orders()
{
    $user = Auth::user();

    $orders = Order::where('user_id', $user->id)
                    ->latest()
                    ->get();

    return view('user.orders', compact('orders'));
}

public function changePassword()
{
    $user = Auth::user();

    return view('user.change-password', compact('user'));
}
}
