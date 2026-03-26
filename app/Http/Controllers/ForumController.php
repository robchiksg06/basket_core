<?php
namespace App\Http\Controllers;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ForumController extends Controller
{
    public function index() {
        $threads = Thread::latest()->get();
        return view('forum.index', compact('threads'));
    }

    public function create() {
        return view('forum.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $thread = Thread::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        return redirect()->route('forum.show', $thread);
    }

    public function show(Thread $thread) {
        return view('forum.show', compact('thread'));
    }

    public function reply(Request $request, Thread $thread) {
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'thread_id' => $thread->id,
            'body' => $data['body'],
        ]);

        return back();
    }
}

