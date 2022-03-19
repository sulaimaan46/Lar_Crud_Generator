<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Image;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::latest()->get();

        return response()->json($images);
    }

    public function store(ImageRequest $request)
    {
        $image = Image::create($request->all());

        return response()->json($image, 201);
    }

    public function show($id)
    {
        $image = Image::findOrFail($id);

        return response()->json($image);
    }

    public function update(ImageRequest $request, $id)
    {
        $image = Image::findOrFail($id);
        $image->update($request->all());

        return response()->json($image, 200);
    }

    public function destroy($id)
    {
        Image::destroy($id);

        return response()->json(null, 204);
    }
}
