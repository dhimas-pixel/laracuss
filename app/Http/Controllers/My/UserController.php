<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Answer;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(string $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return abort(404);
        }

        $picture = filter_var($user->picture, FILTER_VALIDATE_URL) ? $user->picture : Storage::url($user->picture);

        $perPage = 5;
        $columns = ['*'];
        $discussionPage = 'discussions';
        $answerPage = 'answers';

        return view('pages.users.show', [
            'user' => $user,
            'picture' => $picture,
            'discussions' => Discussion::where('user_id', $user->id)->paginate($perPage, $columns, $discussionPage),
            'answers' => Answer::where('user_id', $user->id)->paginate($perPage, $columns, $answerPage),
        ]);
    }

    public function edit(string $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user || auth()->id() !== $user->id) {
            return abort(404);
        }

        $picture = filter_var($user->picture, FILTER_VALIDATE_URL) ? $user->picture : Storage::url($user->picture);

        return view('pages.users.form', [
            'user' => $user,
            'picture' => $picture,
        ]);
    }

    public function update(UpdateRequest $request, string $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user || auth()->id() !== $user->id) {
            return abort(404);
        }

        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('picture')) {
            if (filter_var($user->picture, FILTER_VALIDATE_URL) === false) {
                Storage::disk('public')->delete($user->picture);
            }
            $filePath = Storage::disk('public')->put('images/users/picture', $request->file('picture'));
            $validated['picture'] = $filePath;
        }

        $update = $user->update($validated);

        if ($update) {
            session()->flash('notif.success', 'User updated successfully');
            return redirect()->route('users.show', $validated['username']);
        }

        return abort(500);
    }
}
