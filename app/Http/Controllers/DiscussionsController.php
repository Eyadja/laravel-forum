<?php

namespace LaravelForum\Http\Controllers;

use Illuminate\Http\Request;
use LaravelForum\Discussion;
use LaravelForum\Http\Requests\CreateDiscussionRequest;
use LaravelForum\Replay;

class DiscussionsController extends Controller
{
    /**
     * DiscussionsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['create', 'store']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('discussions.index', [
            'discussions' => Discussion::filterByChannels()->paginate(3)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('discussions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDiscussionRequest $request)
    {
        auth()->user()->discussions()->create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'content' => $request->content,
            'channel_id' => $request->channel,

        ]);

        session()->flash('success', 'Discussion posted');

        return redirect()->route('discussions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Discussion $discussion)
    {
        return view('discussions.show', [
            'discussion' => $discussion
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reply(Discussion $discussion, Replay $reply)
    {
        $discussion->markAsBestReply($reply);

        session()->flash('success', 'Marked as best reply');

        return redirect()->back();
    }
}
