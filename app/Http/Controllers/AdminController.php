<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request)
    {
        // validation
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function getTags()
    {
        return Tag::orderBy('id', 'DESC')->get();
    }
}
