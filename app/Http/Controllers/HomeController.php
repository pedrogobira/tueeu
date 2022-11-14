<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $lastStory = Story::where('author_id', '!=', Auth::user()->id)->select('id')->orderBy('id', 'DESC')->first();

        if ($lastStory == null) {
            return view('received', ['received' => null]);
        }

        $received = null;
        while ($received == null) {
            $received = Story::find(rand(1, $lastStory->id));
        }

        if (!$received->canBeSent(Auth::user())) {
            return view('received', ['received' => null]);
        }

        return view('received', ['received' => $received]);
    }
}
