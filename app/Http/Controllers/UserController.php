<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Rules\MatchOldPassword;

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


    public function orders()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.ordershistory', compact('orders'));
    }


    public function profile()
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }


    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);


        if ($request->hasFile('profile_image')) {


            if ($user->profile_image && File::exists(public_path('images/users/' . $user->profile_image))) {
                File::delete(public_path('images/users/' . $user->profile_image));
            }


            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/users'), $filename);

            $user->profile_image = $filename;
        }

        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        $user = Auth::user();

        return view('user.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/'
            ],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(Auth::id())->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
