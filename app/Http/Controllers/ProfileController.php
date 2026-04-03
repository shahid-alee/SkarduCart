<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Rules\MatchOldPassword;
use App\Models\Order;

class ProfileController extends Controller
{
    public function adminprofile()
    {
        $profile = Auth::user();
        return view('profile.admin-profile', compact('profile'));
    }

    public function adminprofileUpdate(Request $request, $id)
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

    public function adminchangePassword()
    {
        return view('profile.changepassword');
    }

    public function adminchangePasswordStore(Request $request)
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

public function orderHistory()
{
    $orders = Order::with(['items', 'tracking'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

    return view('profile.orderHistory', compact('orders'));
}
}
