<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        auth()->user()->update($request->only('name', 'email', 'password'));

        if ($request->input('password')) {
            auth()->user()->update([
                'password' => password_hash($request->input('password'), PASSWORD_DEFAULT)
            ]);
        }

        return redirect()->route('profile')->with('message', 'Profile saved successfully');
    }

}
