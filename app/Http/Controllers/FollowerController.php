<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{

    public function store( User $user)//user la persona que se esta visitando / request tiene a la persona autenticada
    {
        $user->followers()->attach(auth()->user()->id);
        return back();
    }

    public function destroy( User $user)//user la persona que se esta visitando / request tiene a la persona autenticada
    {
        $user->followers()->detach(auth()->user()->id);
        return back();
    }
}
