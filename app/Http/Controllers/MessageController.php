<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Notifications\MessageReceived;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, Conversation $conversation = null)
    {
        $data = [];
        $initialConvo = 0;
        $user_id = auth()->id();

        if ($conversation) {
            $this->authorize('view', $conversation);

            $initialConvo = $conversation->id;

            $conversation->messages()
                ->where('messages.user_id', '<>', $user_id)
                ->whereNull('messages.read_at')->update([
                    'read_at' => Carbon::now(),
                ]);

            unset($conversation);
        } else {
            if ($request->get('to')) {
                $data['toUser'] = User::find($request->get('to'));
            }
        }

        $data['user.unreadMessageCount'] = DB::table('messages')
            ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
            ->whereNull('messages.read_at')
            ->where('messages.user_id', '<>', $user_id)
            ->where(function ($query) use ($user_id) {
                $query
                    ->where('conversations.author_id', $user_id)
                    ->orWhere('conversations.recipient_id', $user_id);
            })->count();

        $conversations = Conversation::with([
            'author',
            'recipient',
            'latestMessage' => function (HasOne $query) {
                $query->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc');
            },
        ]);

        if ($initialConvo) {
            $conversations->with([
                'messages' => function ($query) {
                    $query->orderBy('created_at')->orderBy('id', 'asc');
                }
            ]);
        }

        $conversations = $conversations->where('conversations.author_id', $user_id)
            ->orWhere('conversations.recipient_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Messages', array_merge($data, get_defined_vars()));
    }

    public function create(Request $request)
    {

        $request->validate([
            'user_id' =>  'required|exists:users,id'.'|unique:conversations,recipient_id,null,null,author_id,'.auth()->id(),
            'content' => 'required',
        ]);


        $conversation = Conversation::create([
            'author_id' => auth()->id(),
            'recipient_id' => $request->input('user_id'),
        ]);

        $conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('messages.index', $conversation);
    }

    public function reply(Conversation $conversation, Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('message')
        ]);

        $conversation->recipient->notify(new MessageReceived($conversation, auth()->user()));

        return redirect()->route('messages.index', $conversation);
    }

    public function userSearch(Request $request)
    {
        return User::select('id', 'name', 'profile_photo_path')->where('name', 'like', '%'.$request->input('search').'%')->where(auth()->user()->getAuthIdentifierName(), '!=', auth()->id())->limit(10)->get();
    }
}
