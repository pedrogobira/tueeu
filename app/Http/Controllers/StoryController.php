<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateCauseRequest;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        $stories = Story::where('author_id', Auth::user()->id)->paginate(10);
        return view('story.index', ['stories' => $stories]);
    }

    public function store(StoreStoryRequest $request)
    {
        $attributes = $request->validated();

        Story::create([
            'title' => $attributes['title'],
            'body' => $attributes['body'],
            'author_id' => Auth::user()->id
        ]);

        $story = Story::where('title', $attributes['title'])->where('author_id', Auth::user()->id)->first();

        return $this->show($story);
    }

    public function create()
    {
        return view('story.create');
    }

    public function show(Story $story)
    {
        return view('story.show', ['story' => $story]);
    }
}
