<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
            ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id', 'desc')->paginate(3);
        }else{
            $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
            ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id', 'desc')->paginate(3);
        }
        
        return [
            'pagination'=> [
                'total'         =>$articulos->total(),
                'current_page'  =>$articulos->currentPage(),
                'per_page'      =>$articulos->perPage(),
                'last_page'     =>$articulos->lastPage(),
                'from'          =>$articulos->firstItem(),
                'to'            =>$articulos->lastItem(), 
            ],
            'articulos'=> $articulos
        ];
    }
    public function listarArticulo(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $tipo = $request->tipo;
        if ($tipo ==0) {
            if($buscar==''){
                $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
                ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }else{
                $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
                ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
                ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }
        }else{
            if($buscar==''){
                $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
                ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
                ->where('articulos.tipo',$tipo)
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }else{
                $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
                ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
                ->where('articulos.tipo',$tipo)
                ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('articulos.id', 'desc')->paginate(10);
            }
        }
        
        return ['articulos'=> $articulos];
    }
    public function listarArticuloVenta(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
            ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
            ->where('articulos.stock','>','0')
            ->where('articulos.idcategoria','5')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }else{
            $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
            ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('articulos.stock','>','0')
            ->where('articulos.idcategoria','5')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        
        return ['articulos'=> $articulos];
    }
    public function buscarArticulo(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=',$filtro)
        ->select('id','nombre')
        ->orderBy('nombre','asc')
        ->take(1)
        ->get();
        return ['articulos' => $articulos];
    }
    public function buscarArticuloProduccion(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=',$filtro)
        ->where('tipo',2)
        ->select('id','nombre')
        ->orderBy('nombre','asc')
        ->take(1)
        ->get();
        return ['articulos' => $articulos];
    }
    public function buscarArticuloVenta(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=',$filtro)
        ->select('id','nombre','stock','precio_venta','contenido')
        ->where('stock','>','0')
        ->orderBy('nombre','asc')
        ->take(1)
        ->get();
        return ['articulos' => $articulos];
    }
    public function store(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        if ($request->codigo == '') {
            $cod_barra = Articulo::select('codigo')
                        ->orderBy('codigo','desc')
                        ->first()
                        ->toArray();
            var_dump($cod_barra);

            $articulo->codigo = intval($cod_barra);   
        }else{
            $articulo->codigo = $request->codigo;
        }
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->contenido = $request->contenido;
        $articulo->tipo = $request->tipo;
        $articulo->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $articulo = Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->contenido = $request->contenido;
        $articulo->tipo = $request->tipo;
        $articulo->save();
    }

    //Se elimina el metodo destroy, ya que la eliminacion es logica usando desactivar y activar
    public function desactivar(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }

    public function listarPdf()
    {
        $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id' )
            ->select('articulos.id','articulos.idcategoria','articulos.codigo', 'articulos.nombre', 'categorias.nombre as nombre_categoria', 'articulos.precio_venta', 'articulos.stock', 'articulos.contenido', 'articulos.tipo', 'articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.nombre', 'desc')->get();

        $cont = Articulo::count();
        $pdf = \PDF::loadView('pdf.articulospdf',['articulos'=>$articulos, 'cont'=>$cont]);
        return $pdf->download('articulos.pdf');
    }
}
