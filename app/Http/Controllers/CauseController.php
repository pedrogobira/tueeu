<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCauseRequest;
use App\Models\Cause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CauseController extends Controller
{
    public function index()
    {
        $causes = Cause::join('members', 'members.cause_id', '=', 'causes.id')
            ->join('users', 'members.user_id', '=', 'users.id')
            ->select('causes.*')
            ->where('users.id', Auth::id())
            ->paginate(10);

        return view('cause.index', ['causes' => $causes]);
    }

    public function create()
    {
        return view('story.create');
    }

    public function show(Cause $cause)
    {
        Gate::authorize('view-cause', $cause);
        return view('cause.show', ['cause' => $cause]);
    }

    public function updateView(int $id)
    {
        $cause = Cause::findOrFail($id);
        return view('cause.update', ['cause' => $cause]);
    }

    public function customUpdate(UpdateCauseRequest $request, int $id)
    {
        $attributes = $request->validated();
        $cause = Cause::findOrFail($id);
        $cause->name = $attributes['name'];
        $cause->description = $attributes['description'];
        $cause->save();
        return redirect()->route('cause.show', $cause);
    }
}
