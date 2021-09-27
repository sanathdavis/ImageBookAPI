<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{

    /**
     * Display a listing of a user's images.
     *
     * @return \Illuminate\Http\Response
     */
    public function images()
    {
        return User::find(auth()->id())->images;
    }
}
