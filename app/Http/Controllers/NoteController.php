<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if($request->wantsJson()){
            return response()->json(['data'=> []]);
        } else {
            return view('notes.index', ['notes'=> []]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->expectsJson()){
            $this->validate($request, [
                'title'=> 'required',
                'content'=> 'required',
            ]);
            $request->user()->notes()->create([
                'title'=> $request->title,
                'content'=> $request->content,
            ]);

            return response()->json(['message'=> 'Note saved successfully.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Note $note)
    {
        if($request->expectsJson()){
            return response()->json($note);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        //
    }
}
