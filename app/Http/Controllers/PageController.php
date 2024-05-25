<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
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
            //aqui saca el id propio de usuario
            $user = $req->user();

            //id de los usuarios que son mis amigos
            $friends_from_id = $user->friendsFrom()->pluck('users.id');
            //id de los usuarios de los que soy su amigo
            $friends_to_id   = $user->friendsTo()->pluck('users.id');
            //merge de los tres
            $users_ids=$friends_from_id->merge($friends_to_id)->push($user->id);

            //$posts=Post::where('user_id', $req->user()->id)->latest()->get();
            //esta es una manera mas directa de hacer lo mismo que la linea de arriba
            //$posts=$req->user()->posts()->latest()->get();
            //esta linea hace un where en el modelo de posts de los ids
            $posts=Post::whereIn('user_id',$users_ids)->latest()->get();
        } else {
            $posts=Post::latest()->get();
        }
        
        

        return view('dashboard', compact('posts'));
    }

    public function profile(User $user) {
        $posts=$user->posts()->latest()->get();

        return view('profile', compact('user','posts'));
    }

    public function status(Request $req) {
        $requests = $req->user()->pendingTo;
        $sent     = $req->user()->pendingFrom;
        $friends  = $req->user()->friends();

        return view('status', compact('requests','sent', 'friends'));
    }
}
