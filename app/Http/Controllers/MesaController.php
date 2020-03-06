<?php

namespace App\Http\Controllers;

use App\User;
use App\Producto;
use App\Categoria;
use Illuminate\Http\Request;

class MesaController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verMenu($local, $mesa)
    {
        $mesucha = $mesa;

        $negocioname = User::where('id',$local)->pluck('name');

        $cats = Categoria::where('user_id', $local)->get();

        $prods = Producto::where('user_id', $local)->get();
      //  echo " LOCAL: ".$local. " - MESA: ".$mesa;

        return view('pedirOrden', compact('prods','cats','mesucha','negocioname'));
    }
}
