<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $ingresos = Ingreso::join ('personas','ingresos.idproveedor','=','personas.id')
            ->join ('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
            'ingresos.total','ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id', 'desc')->paginate(3);
        }else{
            $ingresos = Ingreso::join ('personas','ingresos.idproveedor','=','personas.id')
            ->join ('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
            'ingresos.total','ingresos.estado','personas.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ingresos.id', 'desc')->paginate(3);
        }
        return [
            'pagination'=> [
                'total'         =>$ingresos->total(),
                'current_page'  =>$ingresos->currentPage(),
                'per_page'      =>$ingresos->perPage(),
                'last_page'     =>$ingresos->lastPage(),
                'from'          =>$ingresos->firstItem(),
                'to'            =>$ingresos->lastItem(), 
            ],
            'ingresos'=> $ingresos
        ];
    }
    public function obtenerCabecera(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $ingreso = Ingreso::join ('personas','ingresos.idproveedor','=','personas.id')
        ->join ('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
        'ingresos.total','ingresos.estado','personas.nombre','users.usuario')
        ->where('ingresos.id','=', $id)
        ->orderBy('ingresos.id', 'desc')->take(1)
        ->get();   
        return ['ingreso'=> $ingreso];
    }
    public function obtenerDetalles(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $detalles = DetalleIngreso::join ('articulos','detalle_ingreso.idarticulo','=','articulos.id')
        ->select('detalle_ingreso.cantidad','detalle_ingreso.precio','articulos.nombre as articulo')
        ->where('detalle_ingreso.idingreso','=', $id)
        ->orderBy('detalle_ingreso.id', 'desc')
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
            $ingreso = new Ingreso();
            $ingreso->idproveedor= $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora= $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';
            $ingreso->save();

            $detalles = $request->data; //Array de detalles
            //Recorre todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio=$det['precio'];
                $detalle->save();
                $id_detalle=$detalle->id;
                $contenido= DB::table('articulos')->select('contenido')->where('id',$det['idarticulo'])->get()->toArray();
                $ingre_costArt= DB::table('costo_articulos')->insert(['cantidad' => $det['cantidad'], 'costo_articulo' => $det['precio'], 'contenido' => $contenido[0]->contenido, 'idarticulo' => $det['idarticulo'], 'id_detalle_ingreso' => $id_detalle]);
            }
            DB::commit();

        } catch(Exception $e){
            DB::rollback();
        }
    }
    public function desactivar(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
        $id_detalle = DB::table('detalle_ingreso')->where('idingreso',$request->id)->get()->toArray();
        for ($i = 0; $i <count($id_detalle); $i++) { 
            $des_costArt = DB::update('update costo_articulos set cantidad=0 where id_detalle_ingreso=?',[$id_detalle[$i]->id]);
        }
    }
}
