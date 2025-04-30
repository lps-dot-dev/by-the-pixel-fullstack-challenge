<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::paginate(5));
    }

    public function count()
    {
        return response()->json(['count' => User::count()]);
    }
}
