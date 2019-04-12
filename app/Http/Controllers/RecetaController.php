<?php

namespace App\Http\Controllers;
use App\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $recetas = Receta::join ('articulos','recetas.idarticulo','=','articulos.id')
            ->select('recetas.idarticulo','articulos.nombre','articulos.contenido','recetas.idingrediente',
            'articulos.id','recetas.principal','articulos.codigo',DB::raw('(select articulos.nombre from articulos where articulos.id=recetas.idingrediente) as nombre_ingrediente'))
            ->where('recetas.principal',1)
            ->orderBy('articulos.nombre', 'asc')->paginate(5);
        }else{
            $recetas = Receta::join ('articulos','recetas.idarticulo','=','articulos.id')
            ->select('recetas.idarticulo','articulos.nombre','articulos.contenido','recetas.idingrediente',
            'articulos.id','recetas.principal','articulos.codigo',DB::raw('(select articulos.nombre from articulos where articulos.id=recetas.idingrediente) as nombre_ingrediente'))
            ->where('recetas.principal',1)
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.nombre', 'asc')->paginate(5);
        }
        return [
            'pagination'=> [
                'total'         =>$recetas->total(),
                'current_page'  =>$recetas->currentPage(),
                'per_page'      =>$recetas->perPage(),
                'last_page'     =>$recetas->lastPage(),
                'from'          =>$recetas->firstItem(),
                'to'            =>$recetas->lastItem(), 
            ],
            'recetas'=> $recetas
        ];
    }
    public function edit (Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $receta = Receta::join ('articulos','recetas.idarticulo','=','articulos.id')
        ->select('recetas.id','articulos.nombre','recetas.contenido','recetas.idingrediente',
            'articulos.id','recetas.principal','articulos.codigo',DB::raw('(select articulos.nombre from articulos where articulos.id=recetas.idingrediente) as nombre_ingrediente'))
        ->where('recetas.idarticulo','=', $id)
        ->where('recetas.principal',1)
        ->orderBy('recetas.id', 'asc')->take(1)
        ->get();   
        return ['receta'=> $receta];
    }
    public function obtenerDetalles(Request $request){
        if(!$request->ajax()){
            return redirect('/');
        }
        $id = $request->id;
        $detalles = Receta::join ('articulos','recetas.idingrediente','=','articulos.id')
        ->select('recetas.id as id','articulos.nombre as ingredientes','recetas.contenido as contenido_ingredientes','recetas.idingrediente',
            'articulos.id','recetas.principal','articulos.codigo',DB::raw('(select articulos.nombre from articulos where articulos.id=recetas.idingrediente) as nombre_ingrediente'))
        ->where('recetas.idarticulo','=', $id)
        ->where('recetas.principal',0)
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
                $detalle = new Receta();
                $detalle->idarticulo = $request->idarticulo;
                $detalle->idingrediente = $det['idingredientes'];
                $detalle->contenido = $det['contenido_ingredientes'];
                $detalle->principal=0;
                $detalle->save();
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
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
}
