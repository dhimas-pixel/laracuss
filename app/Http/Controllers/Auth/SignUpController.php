<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SignUpController extends Controller
{
    public function show()
    {
        return view('pages.auth.sign-up');
    }

    public function signUp(SignUpRequest $request)
    {
        // Get form request
        // tambahkan password dengan mehtod bcrypt / jadikan password hash
        // tambahkan pict dummy sesuai username
        // create user berdasarkan request yang sudah validasi & proses
        // jika create sukse maka loginkan user
        // redirect list discussion
        // jika gagal maka return 500

        $validated = $request->validated();

        $validated['password'] = bcrypt($validated['password']);
        $validated['picture'] = config('app.avatar_generator_url') . $validated['username'];

        $create = User::create($validated);

        if ($create) {
            Auth::login($create);
            return redirect()->route('discussions.index');
        }

        return abort(500);
    }
}
