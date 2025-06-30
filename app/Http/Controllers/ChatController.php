<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('chat', compact('users'));
    }

    public function fetchMessages(Request $request)
    {
        $from = Auth::id();
        $to = $request->to;
        $messages = Message::where(function($q) use ($from, $to) {
            $q->where('from', $from)->where('to', $to);
        })->orWhere(function($q) use ($from, $to) {
            $q->where('from', $to)->where('to', $from);
        })->orderBy('created_at')->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'from' => Auth::id(),
            'to' => $request->to,
            'message' => $request->message
        ]);
        return response()->json($message);
    }
}
