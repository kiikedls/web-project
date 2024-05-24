<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard(Request $req) {
        //este muestra lo que trae el request
        //dd($req->all());
        //este recupera lo que trae el campo en este caso "form me"
        //dd($req->get('for-my'));
        //este recupera si es que hay un usuario logeado
        //dd($req->user());
        //este recupera el id del usuario
        //dd($req->user()->id);
        
        //este pequeño bloque hace una condicion si esta en solo for my devuelve 
        //los post solo del usuario logeado, si no devuelve todos los que haya
        if ($req->get('for-my')) {
            //$posts=Post::where('user_id', $req->user()->id)->latest()->get();
            //esta es una manera mas directa de hacer lo mismo que la linea de arriba
            $posts=$req->user()->posts()->latest()->get();
        } else {
            $posts=Post::latest()->get();
        }
        
        

        return view('dashboard', compact('posts'));
    }
}
