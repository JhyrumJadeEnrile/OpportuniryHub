<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function show(Request $request, User $member): View
    {
        return view('members.show', [
            'member' => $member,
            'posts'  => $member->posts()->latest()->take(10)->get(),
            'isSelf' => $member->id === $request->user()->id,
        ]);
    }
}