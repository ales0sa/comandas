<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;
use Auth;

class CategoriaController extends Controller
{

    public function index(Request $request)
    {
        $useron = Auth::id();
        $cats = Categoria::where('user_id', $useron)->get();

        //return response()->json(['cats'=>$cats]);
        return $cats;
        //Esta función nos devolvera todas las tareas que tenemos en nuestra BD
    }

    public function store(Request $request)
    {


        $cat = new Categoria();
        $cat->nombre = $request->nombre;
        $cat->user_id = Auth::id();
        $cat->save();
        //Esta función guardará las tareas que enviaremos mediante vuejs
    }
    public function show(Request $request)
    {
        $cat = Categoria::findOrFail($request->id);
        return $cat;
        //Esta función devolverá los datos de una tarea que hayamos seleccionado para cargar el formulario con sus datos
    }

    public function update(Request $request)
    {
        $cat = Categoria::findOrFail($request->id);

        $cat->nombre = $request->nombre;

        $cat->save();

        return $cat;
        //Esta función actualizará la tarea que hayamos seleccionado
       
    }

    public function destroy(Request $request)
    {
        $cat = Categoria::destroy($request->id);
        return $cat;
        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
    }
}