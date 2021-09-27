<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //x1, x2, y1, y2 being the coordinates of the tag
        $request->validate([
            'label' => 'required',
            'image_id' => 'required',
            'x1' => 'required',
            'x2' => 'required',
            'y1' => 'required',
            'y2' => 'required',
        ]);

        //check if the logged in user owns the image
        if (Image::findOrFail($request->image_id)->user->id != auth()->id()) {
            return response()->json(['error' => 'Not authorized.'], 403);
        }

        Tag::create($request->all());

        return response()->json([
            "message" => "Tag successfully added",
        ], 201);
    }
}
