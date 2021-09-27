<?php

namespace App\Http\Controllers;

use App\Models\Image;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    const STORAGE_PATH = "storage/app/";
    const PUBLIC_PATH = "public/images/";
    const PRIVATE_PATH = "images/";

    /**
     * Display a listing of all public images.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Image::where('private', 0)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {

            $date = new DateTime();
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $nameInStorage = $name . '-' . $date->getTimestamp() . '.' . $extension;
            $path = Storage::putFileAs(
                self::PUBLIC_PATH,
                $file,
                $nameInStorage
            );
            [$width, $height] = getimagesize($file);

            $image = new Image();
            $image->name = $nameInStorage;
            $image->path = self::STORAGE_PATH . $path;
            $image->user_id = auth()->id();
            $image->width = $width;
            $image->height = $height;
            $image->save();

            return response()->json([
                "message" => "File successfully uploaded",
                "url" => asset("storage/images/" . $nameInStorage),
                "height" => $height,
                "width" => $width,
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //check if the image exists
        $image = Image::findOrFail($id);

        //check if the logged in user owns the image
        if ($image->user_id != auth()->id()) {
            return response()->json(['error' => 'Not authorized.'], 403);
        }

        //getting tags of the image
        $image->tags = $image->tags;

        return response()->json($image);
    }

    /**
     * Change Image Privacy
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePrivacy($id, Request $request)
    {

        //check if the image exists
        $image = Image::findOrFail($id);

        //check if the logged in user owns the image
        if ($image->user_id != auth()->id()) {
            return response()->json(['error' => 'Not authorized.'], 403);
        }

        if ($image->private) {
            //moving file from private folder to public folder
            $fromPath = self::PRIVATE_PATH . "/" . $image->name;
            $toPath = self::PUBLIC_PATH . "/" . $image->name;
            Storage::move($fromPath, $toPath);

            $image->private = false;
            $image->path = $toPath;
            $image->save();
        } else {
            //moving file from public folder to private folder
            $fromPath = self::PUBLIC_PATH . "/" . $image->name;
            $toPath = self::PRIVATE_PATH . "/" . $image->name;
            Storage::move($fromPath, $toPath);

            $image->private = true;
            $image->path = $toPath;
            $image->save();
        }

        return response()->json([
            "message" => "Changed Privacy Of the File",
        ]);

    }
}
