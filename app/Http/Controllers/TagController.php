<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->get();

        return response()->json($tags);
    }

    public function store(TagRequest $request)
    {
        $tag = Tag::create($request->all());

        return response()->json($tag, 201);
    }

    public function show($id)
    {
        $tag = Tag::findOrFail($id);

        return response()->json($tag);
    }

    public function update(TagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->update($request->all());

        return response()->json($tag, 200);
    }

    public function destroy($id)
    {
        Tag::destroy($id);

        return response()->json(null, 204);
    }
}
