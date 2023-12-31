<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(){//para validar que el usuario este autenticado  para acceder 
        $this->middleware("auth")->except(["show","index"]);
    }
    
   public function index(User $user){

    $posts= Post::Where("user_id", $user->id)->latest()->paginate(20);//tenemos la cunsulta para traer los datos de las publicaciones de la bd y paginamos
   // dd($post);
  
    return view("dashboard",[
        "user"=>$user,      //pasando datos a la vista
        "posts"=>$posts
    ]);
   }
  
   public function create(){
    //dd("creando Post...");
    return view('posts.create');
   }

   public function store(Request $request){
    
    $this->validate($request,[
        "titulo"=>"required|max:255",
        "descripcion"=>"required",
        "imagen"=>"required",
    ]);

   // Post::create([
     //   "titulo"=>$request->titulo,
     //   "descripcion"=>$request->descripcion,
     //   "imagen"=>$request->imagen,
     //   "user_id"=> auth()->user()->id,
    //]);

    //otra forma 
    //$post=new Post;
    //$post->titulo=$request->titulo;
    //$post->descripcion=$request->descripciion;
    //$post->imagen=$request->imagen;
    //$post->user_id=auth()->user()->id;
    //$post->save();

    $request->user()->post()->create([
        "titulo"=>$request->titulo,
        "descripcion"=>$request->descripcion,
        "imagen"=>$request->imagen,
        "user_id"=> auth()->user()->id,
    ]);

    return redirect()->route("post.index",auth()->user()->username);
   }

   public function show(User $user, Post $post){
    return view("posts.show",[
        "post"=>$post,
        "user"=>$user
        
    ]);
   }


   public function destroy(Post $post)
   {
    $this->authorize("delete",$post); //este metod esta enlazado con el policy
    $post->delete();

    //eliminar imagen 
    $imagen_path=public_path("uploads/". $post->imagen);
    if (File_exists($imagen_path)) {
        unlink($imagen_path);
    }

    return redirect()->route('post.index',auth()->user()->username);

   }

}
