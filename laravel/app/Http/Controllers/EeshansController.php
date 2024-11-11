<?php

namespace App\Http\Controllers;

use App\Models\UserComment;
use Illuminate\Http\Request;

class EeshansController extends Controller
{
    public function index()
    {
        return view('Eeshan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:32',
            'email' => 'required|email',
            'message' => 'required|string|required|max:255'
        ]);
        $userComment = new UserComment();
        $userComment->name = $request->input('name');
        $userComment->email = $request->input('email');
        $userComment->message = $request->input('message');
        $userComment->save();

        // Additional logic or redirection after successful data storage

        return redirect()->back()->with('success', 'Comment stored successfully!');
    }
}
