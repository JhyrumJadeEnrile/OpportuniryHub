<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $me = $request->user()->id;

        // Build the conversation list: one entry per other member.
        $conversations = Message::where('sender_id', $me)
            ->orWhere('recipient_id', $me)
            ->with(['sender:id,name,member_type', 'recipient:id,name,member_type', 'listing:id,title'])
            ->latest()
            ->get()
            ->groupBy(fn (Message $m) => $m->sender_id === $me ? $m->recipient_id : $m->sender_id)
            ->map(function ($group) use ($me) {
                $last = $group->first(); // newest message, because of latest()

                return [
                    'user'   => $last->sender_id === $me ? $last->recipient : $last->sender,
                    'last'   => $last,
                    'unread' => $group->where('recipient_id', $me)->whereNull('read_at')->count(),
                ];
            });

        $activeUser = null;
        $thread = collect();

        if ($request->filled('with') && (int) $request->with !== $me) {
            $activeUser = User::select('id', 'name', 'member_type', 'program')->findOrFail($request->with);

            $thread = Message::betweenUsers($me, $activeUser->id)
                ->with('listing:id,title')
                ->get();

            // Mark incoming messages in this thread as read.
            Message::where('sender_id', $activeUser->id)
                ->where('recipient_id', $me)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('messages.index', compact('conversations', 'activeUser', 'thread'));
    }

    public function store(Request $request): RedirectResponse
    {
        $me = $request->user()->id;

        $data = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id', Rule::notIn([$me])],
            'body'         => ['required', 'string', 'max:2000'],
            'listing_id'   => ['nullable', 'integer', 'exists:listings,id'],
        ]);

        Message::create([
            'sender_id'    => $me,
            'recipient_id' => $data['recipient_id'],
            'listing_id'   => $data['listing_id'] ?? null,
            'body'         => $data['body'],
        ]);

        return redirect()->route('messages.index', ['with' => $data['recipient_id']]);
    }
}
