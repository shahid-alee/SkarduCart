<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Rules\MatchOldPassword;

class ProfileController extends Controller
{
    public function profile()
    {
        $profile = Auth::user();
        return view('profile.user-profile', compact('profile'));
    }

    public function profileUpdate(Request $request, $id)
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

        return redirect()->route('admin-profile')->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        return view('profile.changepassword');
    }

    public function changPasswordStore(Request $request)
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

        return redirect()->route('admin-profile')->with('success', 'Password Successfully Changed');
    }
}