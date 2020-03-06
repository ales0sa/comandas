<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use Auth;

class ProductoController extends Controller
{

    public function index(Request $request)
    {
        $useron = Auth::id();
        $prods = Producto::select('productos.*', 'categorias.nombre AS catname')
                ->where('productos.user_id', $useron)
                ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                ->orderBy('catname')
                ->get();

        //return response()->json(['cats'=>$cats]);
        return $prods;
        //Esta función nos devolvera todas las tareas que tenemos en nuestra BD
    }

    public function store(Request $request)
    {


        $prod = new Producto();
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->descripcion = $request->descripcion;
        $prod->categoria_id = $request->categoria;
        $prod->user_id = Auth::id();


        if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $imageName);
        }else{
        $imageName = "no-photo.png";
        }


        //echo $request->image;

        $prod->foto = $imageName;

        $prod->save();
        //Esta función guardará las tareas que enviaremos mediante vuejs
    }
    public function show(Request $request)
    {
        $prod = Producto::findOrFail($request->id);
        return $prod;
        //Esta función devolverá los datos de una tarea que hayamos seleccionado para cargar el formulario con sus datos
    }

    public function update(Request $request)
    {
        $prod = Producto::findOrFail($request->id);

        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->descripcion = $request->descripcion;
        $prod->categoria_id = $request->categoria;

        if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $imageName);
        $prod->foto = $imageName;
        }else{
        //$imageName = "no-photo.png";
        }

        $prod->save();

        return $prod;
        //Esta función actualizará la tarea que hayamos seleccionado
       
    }

    public function destroy(Request $request)
    {
        $prod = Producto::destroy($request->id);
        return $prod;
        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
    }
}