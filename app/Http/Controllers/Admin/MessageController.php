<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{

    public function index()
    {
        $messages = Message::all();
        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $messages = '';
        return view('admin.messages.create', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'activitydate' => 'required|date',
            'category_id' => 'required',
            'file' => 'nullable|file',
        ]);

        $message = new Message();
        $message->title = $request->title;
        $message->content = $request->content;
        $message->activitydate = $request->activitydate;
        $message->category_id = $request->category_id;

        if ($request->hasFile('file')) {
            $fileName = $request->file->getClientOriginalName();
            $request->file->move(public_path('uploads'), $fileName);
            $message->file = $fileName;
        }

        $message->save();

        $toastType = $message->exists ? 'success' : 'error';
        $toastMessage = $message->exists ? 'Message Saved.' : 'Failed to save message.';

        return redirect()->route('admin.messages.index')->with(['toast' => $toastType, 'message' => $toastMessage]);
    }


    public function edit($id)
    {
        $message = Message::find($id);
        return view('admin.messages.edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'activitydate' => 'required|date',
            'category_id' => 'required',
            'file' => 'nullable|file',
        ]);

        $message = Message::find($id);
        $message->title = $request->title;
        $message->content = $request->content;
        $message->activitydate = $request->activitydate;
        $message->category_id = $request->category_id;

        if ($request->hasFile('file')) {

            $fileName = $request->file->getClientOriginalName();
            $request->file->move(public_path('uploads'), $fileName);
            $message->file = $fileName;
        }

        $message->save();

        return redirect()->route('admin.messages.index')->with('toast', 'success')->with('message', 'Message Saved.');
    }


    public function show($id)
    {
        $message = Message::find($id);
        return view('admin.messages.show', compact('message'));
    }


    public function destroy($id)
    {
        $message = Message::find($id);

        if ($message) {
            $message->forceDelete();
        }

        return redirect()->route('admin.messages.index')->with('toast', 'success')->with('message', 'Message Deleted.');
    }

    public function switch(Request $request)
    {
        try {
            Message::find($request->id)->update([
                'status' => $request->status=="true" ? 1 : 0
            ]);
        } catch (Throwable $th) {
           
        }
        return $request->status;
    }

}