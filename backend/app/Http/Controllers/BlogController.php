<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ApiResponser;
    public function all($key)
    {
        $string = 'yyQhZRp4zX0ZZY7RbESvP9ZbuzwbF4wC';
        if ($string != $key) {
            return $this->errorResponse('No tienes permiso', 402);
        }
        $blog = Blog::orderBy('id','desc')->get();
        return $this->successResponse($blog);

    }
    public function index()
    {
        $blog = Blog::where('publicado', 1)->orderBy('fecha_noticia', 'desc')->take(6)->paginate();
        return $this->successResponse($blog);
    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return $this->errorResponse('No existe el blog', 400);
        }
        return $this->successResponse($blog);
    }

    public function store(Request $request)
    {
        $string = 'yyQhZRp4zX0ZZY7RbESvP9ZbuzwbF4wC';
        if ($string != $request->password) {
            return $this->errorResponse('No tienes permiso', 402);
        }
        $blog = new Blog;
        $blog->titulo = $request->titulo;
        $blog->resumen = $request->resumen;
        $blog->img = $request->img;
        $blog->link = $request->link;
        $blog->fecha_noticia = $request->fecha_noticia;
        $blog->type = $request->type;
        $blog->body = $request->body ?? null;
        $blog->publicado = $request->publicado ?? 0;
        $blog->save();
        return $this->successResponse($blog);
    }

    public function update($id, Request $request)
    {

        $string = 'yyQhZRp4zX0ZZY7RbESvP9ZbuzwbF4wC';
        if ($string != $request->password) {
            return $this->errorResponse('No tienes permiso', 402);
        }
        $blog = Blog::find($id);
        if (!$blog) {
            return $this->errorResponse('No existe el blog', 400);
        }
        $blog->titulo = $request->titulo;
        $blog->resumen = $request->resumen;
        $blog->img = $request->img;
        $blog->link = $request->link;
        $blog->fecha_noticia = $request->fecha_noticia;
        $blog->type = $request->type;
        $blog->body = $request->body ?? null;
        $blog->publicado = $request->publicado ?? 0;
        $blog->save();
        return $this->successResponse($blog);

    }

    public function delete($id, $password)
    {

        $string = 'yyQhZRp4zX0ZZY7RbESvP9ZbuzwbF4wC';
        if ($string != base64_decode($password)) {
            return $this->errorResponse('No tienes permiso', 402);
        }
        $blog = Blog::find($id);
        if (!$blog) {
            return $this->errorResponse('No existe el blog', 400);
        }
        $blog->delete();
        return $this->successResponse(1);

    }

}
