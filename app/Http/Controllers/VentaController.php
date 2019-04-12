<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\Cartera;
use App\DetalleVenta;
use App\CostoArticulo;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $ventas = Venta::join ('personas','ventas.idcliente','=','personas.id')
            ->join ('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto',
            'ventas.total','ventas.estado','personas.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->paginate(10);
        }else{
            $ventas = Venta::join ('personas','ventas.idcliente','=','personas.id')
            ->join ('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto',
            'ventas.total','ventas.estado','personas.nombre','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ventas.id', 'desc')->paginate(10);
        }
        return [
            'pagination'=> [
                'total'         =>$ventas->total(),
                'current_page'  =>$ventas->currentPage(),
                'per_page'      =>$ventas->perPage(),
                'last_page'     =>$ventas->lastPage(),
                'from'          =>$ventas->firstItem(),
                'to'            =>$ventas->lastItem(), 
            ],
            'ventas'=> $ventas
        ];
    }
    public function obtenerCabecera(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $venta = Venta::join ('personas','ventas.idcliente','=','personas.id')
        ->join ('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto',
        'ventas.total','ventas.estado','personas.nombre','users.usuario')
        ->where('ventas.id','=', $id)
        ->orderBy('ventas.id', 'desc')->take(1)
        ->get();   
        return ['venta'=> $venta];
    }
    public function obtenerDetalles(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $detalles = DetalleVenta::join ('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=', $id)
        ->orderBy('detalle_ventas.id', 'desc')
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
            $venta = new Venta();
            $venta->idcliente= $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora= $mytime->toDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->tipo_venta = $request->tipo_venta;
            if ($request->tipo_venta=='CONTADO') {
                $venta->estado = 'Registrado';
            }else{
                $venta->estado = 'Pendiente';
            }
            $venta->fecha_pago = $request->fecha_pago;
            $venta->save();
            // se valida si la venta es a credito para crear la cartera.
            if ($request->tipo_venta=='CREDITO') {
                $cartera = new Cartera();
                $cartera->idventa = $venta->id;
                $cartera->idcliente = $request->idcliente;
                $cartera->abono = $request->abono;
                $cartera->deuda = $request->total - $request->abono;
                $cartera->save();

            }
            $detalles = $request->data; //Array de detalles
            //Recorre todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
                $detalle->save();

                $id_dventa=$detalle->id;
                
                $costArt=DB::table('costo_articulos')
                        ->where('idarticulo',$det['idarticulo'])
                        ->where('cantidad','>',0)
                        ->orderBy('id','asc')
                        ->get()->toArray();
                //var_dump(count($costArt));
                //throw new \Exception ('Debes insertar un número positivo',1);
                $cantidad_pendiente = 0;
                for ($i=0; $i < count($costArt) ; $i++) { 
                    if ($cantidad_pendiente == 0) {
                        if ($costArt[$i]->cantidad <= $det['cantidad']) {
                            $cantidad_pendiente = $det['cantidad'] - $costArt[$i]->cantidad;
                            $desCosArt = DB::table('costo_articulos')
                                        ->where('id',$costArt[$i]->id)
                                        ->update(['cantidad'=>0,'idventa'=>$id_dventa, 'cant_venta'=>$costArt[$i]->cantidad]);
                        }else {
                            $cantidad_pendiente=$costArt[$i]->cantidad - $det['cantidad'];
                            $desCosArt = DB::table('costo_articulos')
                                        ->where('id',$costArt[$i]->id)
                                        ->update(['cantidad'=>$cantidad_pendiente,'idventa'=>$id_dventa, 'cant_venta'=> $det['cantidad']]);
                            $cantidad_pendiente = 0;
                        }
                    }else{
                        if ($costArt[$i]->cantidad <= $cantidad_pendiente) {
                            $cantidad_pendiente = $cantidad_pendiente - $costArt[$i]->cantidad;
                            $desCosArt = DB::table('costo_articulos')
                                        ->where('id',$costArt[$i]->id)
                                        ->update(['cantidad'=>0,'idventa'=>$id_dventa, 'cant_venta'=>$costArt[$i]->cantidad]);
                        }else {
                            $cantidad_r=$cantidad_pendiente;
                            $cantidad_pendiente=$costArt[$i]->cantidad - $cantidad_pendiente;
                            $desCosArt = DB::table('costo_articulos')
                                        ->where('id',$costArt[$i]->id)
                                        ->update(['cantidad'=>$cantidad_pendiente,'idventa'=>$id_dventa, 'cant_venta'=>$cantidad_r]);
                            $cantidad_pendiente = 0;
                        }
                    }
                }
            }
            DB::commit();
            return [
                'id' => $venta->id
            ];

        } catch(Exception $e){
            DB::rollback();
        }
    }
    public function desactivar(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();

        $id_detalle = DB::table('detalle_ventas')->where('idventa',$request->id)->get()->toArray();
        for ($i = 0; $i <count($id_detalle); $i++) {  
            $cant_old = DB::table('costo_articulos')->select('cant_venta')->where('idventa',$id_detalle[$i]->id)->get()->toArray();
            $des_costArt = DB::update('update costo_articulos set cantidad=? where idventa=?',[$id_detalle[$i]->id,$cant_old[0]->cant_venta]);
        }
    }
    public function pdf(Request $request, $id)
    {
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.num_comprobante','ventas.serie_comprobante','ventas.created_at','ventas.impuesto','ventas.total','ventas.estado',
            'personas.nombre', 'personas.num_documento','personas.direccion','personas.email','personas.telefono','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id','desc')->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id','desc')->get();

        $numventa = Venta::select('num_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta',['venta'=>$venta, 'detalles'=>$detalles]);
        return $pdf->download('venta-'.$numventa[0]->num_comprobante.'.pdf');
    }
}
