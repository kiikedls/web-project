<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $req) {
        $req->validate([
            'body'=>'required'
        ]);

        $req->user()->posts()->create($req->all());

        return back();
    }
}
