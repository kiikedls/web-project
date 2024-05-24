<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function store(Request $req, User $user) {

        //comporbaciones, siempre puedes ir depurando los valores que te devuelven con dd
        /*dd(
            //tu propio id
            $req->user()->id,
            //id de la persona a la que envias solicitud
            $user->id,
            //valor que regresan las comprobaciones
            $req->user()->from()->where('to_id',$user->id)->exists(),
            $req->user()->to()->where('from_id',$user->id)->exists(),
        );*/

        //estas condiciones buscan si hay algun registro anterior de relacion
        //entre el usuario y la persona del perfil solicitado
        $is_from = $req->user()->from()->where('to_id',$user->id)->exists();
        $is_to = $req->user()->to()->where('from_id',$user->id)->exists();
        //si existe registro entonces no manda solicitud
        if ($is_from || $is_to) {
            return back();
        }

        //validacion para no enviarte solicitud de amistad a ti mismo
        if ($req->user()->id === $user->id) {
            return back();
        }

        $req->user()->from()->attach($user);

        return back();
    }
}
