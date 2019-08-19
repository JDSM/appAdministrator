<?php

namespace App\Http\Controllers;
use App\Produccion;
use App\Ingreso;
use App\Articulo;
use App\DetalleIngreso;
use App\DetalleProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $producciones = Produccion::join ('articulos','producciones.idarticulo','=','articulos.id')
            ->select('producciones.id','producciones.idarticulo','articulos.nombre','articulos.contenido','producciones.costo_total',
            'producciones.cantidad_artprinc','producciones.cantidad_p','articulos.codigo', 'producciones.created_at')
            ->orderBy('producciones.created_at', 'desc')->paginate(10);
        }else{
            $producciones = Produccion::join ('articulos','producciones.idarticulo','=','articulos.id')
            ->select('producciones.id','producciones.idarticulo','articulos.nombre','articulos.contenido','producciones.costo_total',
            'producciones.cantidad_artprinc','producciones.cantidad_p','articulos.codigo', 'producciones.created_at')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.nombre', 'asc')->paginate(5);
        }
        return [
            'pagination'=> [
                'total'         =>$producciones->total(),
                'current_page'  =>$producciones->currentPage(),
                'per_page'      =>$producciones->perPage(),
                'last_page'     =>$producciones->lastPage(),
                'from'          =>$producciones->firstItem(),
                'to'            =>$producciones->lastItem(), 
            ],
            'producciones'=> $producciones
        ];
    }
    public function edit (Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }      
        $id = $request->id;
        $produccion = Produccion::join ('articulos','producciones.idarticulo','=','articulos.id')
        ->select('producciones.id','articulos.nombre','producciones.cantidad_artprinc','producciones.idingrediente_principal',
            'articulos.id as idarticulo','producciones.cantidad_p',DB::raw('(select articulos.nombre from articulos where articulos.id=producciones.idingrediente_principal) as nombre_ingrediente'))
        ->where('producciones.id','=', $id)
        ->orderBy('producciones.id', 'asc')->take(1)
        ->get();   
        return ['produccion'=> $produccion];
    }
    public function obtenerDetalles(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $detalles = DetalleProduccion::join ('articulos','detalle_producciones.idarticulo','=','articulos.id')
        ->select('detalle_producciones.id as id','articulos.nombre as ingredientes','detalle_producciones.contenido as contenido_ingredientes',
            'articulos.id','articulos.contenido as cantidad_ingredientes')
        ->where('detalle_producciones.idproduccion','=', $id)
        ->get();   
        return ['detalles'=> $detalles];
    }
    public function store(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        try{
            DB::beginTransaction();
            $mytime= Carbon::now('America/Bogota');
            $produccion = new Produccion();
            $produccion->idarticulo = $request->idarticulo;
            $produccion->cantidad_p = $request->cantidad_p;
            $produccion->idingrediente_principal = $request->idingrediente;
            $produccion->costo_total = $request->costo_total;
            $produccion->cantidad_artprinc = $request->cantidad_artprinc;
            $produccion->save();
            
            //se resta del stock  la cantidad consumida en la producción del articulo (ingrediente principal) 
            $articulo_p = Articulo::findOrFail($request->idingrediente);
            $articulo_p->stock = $articulo_p->stock - ($request->cantidad_artprinc/$articulo_p->contenido);
            $articulo_p->save();

            $articulo = Articulo::findOrFail($request->idarticulo);
            $articulo->stock = $articulo->stock + $request->cantidad_p;
            $articulo->save();
            // $ingreso = new Ingreso();
            // $ingreso->idproveedor = ;
            // $ingreso->idusuario = \Auth::user()->id;
            // $ingreso->tipo_comprobante = 'BOLETA';
            // $ingreso->serie_comprobante = $request->idarticulo;
            // $ingreso->num_comprobante = $produccion->id;
            // $ingreso->impuesto = 0;
            // $ingreso->total = $request->costo_total;
            // $ingreso->estado = 'Registrado';
            // $ingreso->fecha_hora = $mytime->toDateString();
            // $ingreso->save();


            // $dingreso = new DetalleIngreso();
            // $dingreso->idingreso =  $ingreso->id;
            // $dingreso->idarticulo = $request->idarticulo;
            // $dingreso->cantidad = $request->cantidad_p;
            // $dingreso->precio = $request->costo_total;
            // $dingreso->save();

            $detalles = $request->data; //Array de detalles
            //Recorre todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleProduccion();
                if (isset($det['idingrediente'])) {
                    $detalle->idarticulo = $det['idingrediente'];
                }else{
                    $detalle->idarticulo = $det['idingredientes'];
                }
                $detalle->idproduccion = $produccion->id;
                $detalle->contenido = $det['contenido_ingredientes'];
                $detalle->save();

                //se resta del stock  la cantidad consumida en la producción del articulo (ingredientes) 
                if (isset($det['idingrediente'])) {
                    $articulo_i = Articulo::findOrFail($det['idingrediente']);
                }else{
                    $articulo_i = Articulo::findOrFail($det['idingredientes']);
                }
                $articulo_i->stock = $articulo_i->stock - ($det['contenido_ingredientes']/$articulo_i->contenido);
                $articulo_i->save();
            }
            DB::commit();

        } catch(Exception $e){
            DB::rollback();
        }
    }
    // pendiente por validar
    public function desactivar(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $produccion = Produccion::findOrFail($request->id);
        $produccion->estado = 'Anulado';
        $produccion->save();
    }
    public function update(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
       
        try{

            DB::beginTransaction();
            Receta::where('idarticulo',$request->idarticulo)->delete();
            $mytime= Carbon::now('America/Bogota');
            $receta = new Receta();
            $receta->idarticulo= $request->idarticulo;
            $receta->idingrediente = $request->idingrediente;
            $receta->contenido = $request->contenido;
            $receta->principal = 1;
            $receta->save();

            $detalles = $request->data; //Array de detalles
            //Recorre todos los elementos
            
            foreach($detalles as $ep=>$det)
            {
                if (isset( $det['idingredientes'])) {
                    $det['idingrediente'] = $det['idingredientes'];
                }
                var_dump($det['idingrediente']);
                $detalle = new Receta();
                $detalle->idarticulo = $request->idarticulo;
                $detalle->idingrediente = $det['idingrediente'];
                $detalle->contenido = $det['contenido_ingredientes'];
                $detalle->principal = 0;
                $detalle->save();
            }
            DB::commit();

        } catch(Exception $e){
            DB::rollback();
        }
    }
}
