<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Producción
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nueva
                    </button>
                </div>
                <!-- Listado-->
                <template v-if="listado==1">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre Artículo</option>
                                    <option value="codigo">Código Artículo</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarProduccion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarProduccion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código Artículo</th>
                                    <th>Nombre Artículo</th>
                                    <th>Cantidad Producida</th>
                                    <th>Fecha de Producción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="produccion in arrayProduccion" :key="produccion.id">
                                    <td>
                                        <button type="button" @click="verProduccion(produccion.id)" class="btn btn-success btn-sm">
                                        <i class="icon-eye"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarProduccion(produccion.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="produccion.codigo"></td>
                                    <td v-text="produccion.nombre"></td>
                                    <td v-text="produccion.cantidad_p"></td>
                                    <td v-text="produccion.created_at"></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                </template>
                <!--Fin Listado-->
                <!-- Detalle-->
                <template v-else-if="listado==0">
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label>Artículo a Producir
                                    <span style="color:red;" v-show="idarticulo==0">
                                        (*Seleccione)
                                    </span>
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control" v-model="codigo_articulo" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                                    <button @click="abrirModal(1)" class="btn btn-primary">...</button>
                                    <input type="text" readonly class="form-control" v-model="articulo" >
                                </div>                                    
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div class="form-group">
                                <label>Ingrediente Principal</label>
                                <div class="form-inline">
                                    <input type="text" readonly class="form-control" v-model="ingrediente" >
                                </div>                                    
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cantidad (g,ml,c/u):</label>
                                <input type="number" class="form-control" v-model="contenido_ingrediente" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Costo:</label>
                                <input type="number" class="form-control" v-model="costo_ingrediente" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div v-show="errorProduccion" class="form-group row div-error">
                                <div class="text-center alert alert-danger">
                                    <div v-for="error in errorMostrarMsjProduccion" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Ingrediente</th>
                                        <th>Contenido</th>
                                        <th>Costo</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="(detalle) in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.ingredientes" readonly></td>
                                        <td>
                                            <input v-model="detalle.contenido_ingredientes" readonly type="number" value="2" class="form-control">
                                        </td>
                                        <td>
                                            {{calcularCosto(detalle)}}
                                        </td>
                                    </tr>
                                </tbody> 
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6">
                                            No hay artículos agregados
                                        </td>
                                    </tr>
                                </tbody>                                   
                            </table>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="col-md-6" style="padding-right:0px !important;">
                            <div class="form-group">
                                <label>Cantidad Producida 
                                    <span style="color:red;" v-show="idingredientes==0">
                                        (*)
                                    </span>
                                </label>
                                <div class="form-inline">
                                    <input type="text" readonly class="form-control" v-model="cantidad_producida" >
                                </div>                                    
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                        </div>
                    </div>
                </div>
                </template>
                <!-- Fin Detalle-->
                <!-- Editar Receta -->
                <template v-else-if="listado==2">
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label>Artículo a Producir:</label>
                                <input type="text" hidden v-model="idarticulo">
                                <p v-text="nombre"></p>                                 
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div class="form-group">
                                <label>Ingrediente Principal
                                    <span style="color:red;" v-show="idingrediente==0">
                                        (*Seleccione)
                                    </span>
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control" v-model="codigo_ingrediente" @keyup.enter="buscarIngrediente()" placeholder="Ingrese artículo">
                                    <button @click="abrirModal(2)" class="btn btn-primary">...</button>
                                    <input type="text" readonly class="form-control" v-model="ingrediente" >
                                </div>                                    
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cantidad (g,ml,c/u):</label>
                                <input type="number" class="form-control" v-model="contenido_ingrediente"  v-on:change="costoIngrediente(costo_real_ingrediente,contenido_ingrediente,cantidad_ingrediente)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Costo:</label>
                                <input type="number" class="form-control" v-model="costo_ingrediente" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div v-show="errorProduccion" class="form-group row div-error">
                                <div class="text-center alert alert-danger">
                                    <div v-for="error in errorMostrarMsjProduccion" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="col-md-6" style="padding-right:0px !important;">
                            <div class="form-group">
                                <label>Ingredientes 
                                    <span style="color:red;" v-show="idingredientes==0">
                                        (*Seleccione)
                                    </span>
                                </label>
                                <div class="form-inline">
                                    <input type="text" class="form-control" v-model="codigo_ingredientes" @keyup.enter="buscarIngredientes()" placeholder="Ingrese artículo">
                                    <button @click="abrirModal(3)" class="btn btn-primary">...</button>
                                    <input type="text" readonly class="form-control" v-model="ingredientes" >
                                </div>                                    
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cantidad (g,ml,c/u):
                                    <span style="color:red;" v-show="contenido_ingredientes==0">
                                        (*Ingrese)
                                    </span>
                                </label>
                                <input type="number" value="0" class="form-control" v-model="contenido_ingredientes">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Ingrediente</th>
                                        <th>Contenido</th>
                                        <th>Costo</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                        <td>
                                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>
                                        </td>
                                        <td v-text="detalle.ingredientes"></td>
                                        <td>
                                            <input v-model="detalle.contenido_ingredientes" type="number" value="2" class="form-control">
                                        </td>
                                        <td>
                                            {{calcularCosto(detalle)}}
                                        </td>
                                    </tr>
                                    <tr style="background-color: #CEECF5;">
                                        <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                        <td>$ {{costo_total=calcularTotal}}</td>
                                    </tr>
                                </tbody> 
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6">
                                            No hay artículos agregados
                                        </td>
                                    </tr>
                                </tbody>                                   
                            </table>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="col-md-6" style="padding-right:0px !important;">
                            <div class="form-group">
                                <label>Cantidad Producida 
                                    <span style="color:red;" v-show="idingredientes==0">
                                        (*)
                                    </span>
                                </label>
                                <div class="form-inline">
                                    <input type="text"  class="form-control" v-model="cantidad_producida" >
                                </div>                                    
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="registrarProduccion()">Registrar Producción</button>
                        </div>
                    </div>
                </div>
                </template>
                <!-- Fin Editar Receta -->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA" >
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                    </select>
                                    <template v-if="validar_tipo==1">
                                    <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA, criterioA, 2)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(buscarA, criterioA, 2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </template>
                                    <template v-else>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA, criterioA, 3)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(buscarA, criterioA, 3)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                        <th>Contenido</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td v-if="validar_tipo==3">
                                            <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm" >
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-if="validar_tipo==2">
                                            <button type="button" @click="agregarIngrediente(articulo)" class="btn btn-success btn-sm" >
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-if="validar_tipo==1">
                                            <button type="button" @click="agregarArticulo(articulo)" class="btn btn-success btn-sm" >
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="articulo.codigo"></td>
                                        <td v-text="articulo.nombre"></td>
                                        <td v-text="articulo.nombre_categoria"></td>
                                        <td v-text="articulo.precio_venta"></td>
                                        <td v-text="articulo.stock"></td>
                                        <td v-text="articulo.contenido"></td>
                                        <td v-text="articulo.tipo"></td>
                                        <td>
                                            <div v-if="articulo.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    import  vSelect  from 'vue-select';
    export default {
        data() {
            return {
                ingrediente : '',
                ingredientes : '',
                idingrediente : 0,
                principal : 0,
                nombre : '',
                arrayProduccion : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayIngrediente : [],
                arrayIngredientes : [],
                listado: 1,
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorProduccion : 0,
                errorMostrarMsjProduccion : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                idarticulo: 0,
                idingredientes: 0,
                codigo_articulo: '',
                codigo_ingrediente:'',
                codigo_ingredientes:'',
                articulo: '',
                cantidad: 0,
                contenido_ingrediente: 0,
                contenido_ingredientes: 0,
                criterioA: 'nombre',
                buscarA: '',
                validar_tipo: 0,
                costo_ingrediente: 0,
                costo_ingredientes: 0,
                costo_total: 0,
                cantidad_ingrediente: 0,
                costo_real_ingrediente: 0,
                cantidad_producida: 0
            }
        },
        components: {
            vSelect
        },
        computed : {
            isActived: function() {
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado = 0.0;
                for(var i = 0;i < this.arrayDetalle.length;i++){
                    resultado = resultado+(this.calcularCosto(this.arrayDetalle[i]))
                }
                    //console.log(this.arrayDetalle);
                return resultado+this.costo_ingrediente;
            }
        },
        watch : {
        },
        methods : {
            listarProduccion(page, buscar, criterio) {
                let me = this;
                var url = '/produccion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio; 
                axios.get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayProduccion = respuesta.producciones.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            listarArticulo(buscarA, criterioA, tipo=0) {
                let me = this;
                var url = '/articulo/listarArticulo?buscar=' + buscarA + '&criterio=' + criterioA + '&tipo=' + tipo; 
                axios.get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            buscarArticulo(){
                let me = this;
                var url = '/articulo/buscarArticuloProduccion?filtro='+ me.codigo_articulo;
                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;
                    if (me.arrayArticulo.length > 0) {
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                    }
                    else{
                        me.articulo = 'No existe artículo';
                        me.idarticulo = 0;
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            buscarIngrediente(){
                let me = this;
                var url = '/articulo/buscarArticulo?filtro='+ me.codigo_ingrediente;
                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayIngrediente = respuesta.articulos;
                    if (me.arrayIngrediente.length > 0) {
                        me.ingrediente = me.arrayIngrediente[0]['nombre'];
                        me.idingrediente = me.arrayIngrediente[0]['id'];
                    }
                    else{
                        me.ingrediente = 'No existe artículo';
                        me.idingrediente = 0;
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            buscarIngredientes(){
                let me = this;
                var url = '/articulo/buscarArticulo?filtro='+ me.codigo_ingredientes;
                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayIngredientes = respuesta.articulos;
                    if (me.arrayIngredientes.length > 0) {
                        me.ingredientes = me.arrayIngredientes[0]['nombre'];
                        me.idingredientes = me.arrayIngredientes[0]['id'];
                    }
                    else{
                        me.ingredientes = 'No existe artículo';
                        me.idingredientes = 0;
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarProduccion(page, buscar, criterio);
            },
            encuentra(id) {
                let me = this;
                console.log("entra"+id+'/'+me.arrayDetalle.length);
                
                var sw = 0;
                for (let i = 0; i < me.arrayDetalle.length; i++) {
                    console.log(me.arrayDetalle[i].idarticulo);
                    if (me.arrayDetalle[i].idarticulo == id) {
                        sw = true;
                    } 
                }
                return sw;
            },
            agregarDetalle() {
                let me = this;
                if (me.idingredientes == 0 || me.contenido_ingredientes == 0) {
                    
                }
                else{
                    if (me.encuentra(me.idingredientes)) {
                        //console.log("alerta");
                        swal({
                            type: 'error',
                            title: "Error ...",
                            text: " Ese Artículo ya se encuentra agregado",
                        })
                    }
                    else {
                        //console.log("no entra");
                        me.arrayDetalle.push({
                            idingredientes: me.idingredientes,
                            ingredientes: me.ingredientes,
                            contenido: me.contenido
                        });
                        me.codigo_ingredientes = "";
                        me.idingredientes = 0;
                        me.ingredientes = "";
                        me.contenido_ingredientes = 0;
                    }
                }
            },
            agregarDetalleModal(data = []){
                let me = this;
                if (me.encuentra(data['id'])) {
                    //console.log("alerta");
                    swal({
                        type: 'error',
                        title: "Error ...",
                        text: " Ese Artículo ya se encuentra agregado",
                    })
                }
                else {
                    console.log(data);
                    me.arrayDetalle.push({
                        idingredientes: data['id'],
                        ingredientes: data['nombre'],
                        contenido_ingredientes: 1,
                        costo_ingredientes: data['precio_venta'],
                        cantidad_ingredientes : data['contenido']
                    });
                }
            },
            agregarIngrediente(data = []){
                let me = this;
                if (me.encuentra(data['id'])) {
                    //console.log("alerta");
                    swal({
                        type: 'error',
                        title: "Error ...",
                        text: " Ese Artículo ya se encuentra agregado",
                    })
                }
                else {
                    //console.log("no entra");
                    me.idingrediente = data['id'];
                    me.ingrediente = data['nombre'];
                    console.log('ingrediente', me.ingrediente);
                    me.contenido_ingrediente = 1;
              
                }
            },
            agregarArticulo(data = []){
                let me = this;
                if (me.encuentra(data['id'])) {
                    //console.log("alerta");
                    swal({
                        type: 'error',
                        title: "Error ...",
                        text: " Ese Artículo ya se encuentra agregado",
                    })
                }
                else {
                    //console.log("no entra");
                    me.idarticulo = data['id'];
                    me.articulo = data['nombre'];
                    console.log('articulo', me.articulo);
                    me.editProduccion(me.idarticulo);
                }
            },
            eliminarDetalle (index) {
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            registrarProduccion() {
                if (this.validarProduccion()){
                    return;
                }
                let me = this;
                axios.post('/produccion/registrar',{
                    'idarticulo': this.idarticulo,
                    'idingrediente': this.idingrediente,
                    'cantidad_artprinc': this.contenido_ingrediente,
                    'cantidad_p': this.cantidad_producida,
                    'costo_total': this.costo_total,
                    'data': this.arrayDetalle
                }).then(function(response){
                    me.listado = 1;
                    me.listarProduccion(1,'','nombre');
                    me.idarticulo = 0;
                    me.idingrediente = 0;
                    me.ingrediente = '';
                    me.articulo = '';
                    me.contenido_ingrediente = 0;
                    me.arrayDetalle = [];
                    me.cantidad_producida = 0;
                    me.costo_total = 0; 
                    me.costo_ingrediente = 0;

                }).catch(function (error){
                    console.log(error);
                });
            },
            editarProduccion(){
                if (this.validarProduccion()){
                    return;
                }
                let me = this;
                axios.post('/produccion/update',{
                    'idarticulo': me.idarticulo,
                    'principal': me.principal,
                    'idingrediente': me.idingrediente,
                    'contenido': me.contenido_ingrediente,
                    'data': me.arrayDetalle
                }).then(function(response){
                    me.listado = 1;
                    me.listarProduccion(1,'','nombre');
                    me.idarticulo = 0;
                    me.idingrediente = 0;
                    me.ingrediente = '';
                    me.articulo = '';
                    me.contenido_ingrediente = 0;
                    me.arrayDetalle = [];

                }).catch(function (error){
                    console.log(error);
                });
            },
            validarProduccion(){
                this.errorProduccion = 0;
                this.errorMostrarMsjProduccion = [];
                if (this.arrayDetalle.length <= 0) { 
                    this.errorMostrarMsjProduccion.push ("Ingrese detalles.");
                }
                if (this.cantidad_producida == 0) this.errorMostrarMsjProduccion.push("Ingrese la catidad Producida");
                if (this.errorMostrarMsjProduccion.length) {
                    this.errorProduccion = 1;
                }
                return this.errorProduccion;
            },
            desactivarProduccion(id){
                swal({
                title: 'Estas seguro de anular esta Receta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/produccion/desactivar',{
                        'id': id
                    }).then(function(response){
                        me.listarProduccion(1, '', 'nombre');
                        swal(
                            'Anulado!',
                            ' La receta ha sido anulada con éxito',
                            'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                    });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                })
            },
            mostrarDetalle(){
                let me = this;
                me.listado = 0;
                me.idingrediente = 0;
                me.ingrediente = '';
                me.idarticulo = 0;
                me.articulo = '';
                me.contenido_ingrediente = 0;
                me.arrayDetalle = [];
            },
            ocultarDetalle(){
                this.listado = 1;
            },
            calcularCosto({cantidad_ingredientes,contenido_ingredientes,costo_ingredientes}){
                //console.log(cantidad_ingredientes,contenido_ingredientes,costo_ingredientes);
                return (contenido_ingredientes*costo_ingredientes)/cantidad_ingredientes;

            },
            costoIngrediente(costo_ingrediente,contenido_ingrediente,cantidad_ingrediente){
                console.log(costo_ingrediente,contenido_ingrediente,cantidad_ingrediente);
                this.costo_ingrediente = (contenido_ingrediente*costo_ingrediente)/cantidad_ingrediente;
            },
            verProduccion(id){
                let me = this;
                me.listado = 0;
                //Obtener los datos de la receta
                var arrayProduccionT = [];
                var url = '/produccion/edit?id=' + id; 
                axios.get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    arrayProduccionT = respuesta.produccion;
                    console.log(respuesta,respuesta.produccion);
                    me.idarticulo = arrayProduccionT[0]['idarticulo'];
                    me.articulo = arrayProduccionT[0]['nombre'];
                    me.idingrediente = arrayProduccionT[0]['idingrediente'];
                    me.ingrediente = arrayProduccionT[0]['nombre_ingrediente'];
                    me.contenido_ingrediente = arrayProduccionT[0]['contenido'];
                    me.nombre = arrayProduccionT[0]['nombre'];
                    me.costo_ingrediente = (arrayProduccionT[0]['contenido']*arrayProduccionT[0]['costo_ingrediente'])/arrayProduccionT[0]['contenido_ingrediente']; 
                    me.cantidad_ingrediente = arrayProduccionT[0]['contenido_ingrediente'];
                    me.costo_real_ingrediente = arrayProduccionT[0]['costo_ingrediente'];
                })
                .catch(function(error) {
                    console.log(error);
                });
                //Obtener los datos de los detalles
                var urld = '/produccion/obtenerDetalles?id=' + id; 
                axios.get(urld)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayDetalle = respuesta.detalles;
                    //console.log(me.arrayDetalle[0].costo_ingredientes);
                })
                .catch(function(error) {
                    console.log(error);
                });              
            },
            editProduccion(id){
                let me = this;
                me.listado = 2;
                //Obtener los datos de la receta
                var arrayProduccionT = [];
                var url = '/receta/edit?id=' + id; 
                axios.get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    arrayProduccionT = respuesta.receta;
                    me.idarticulo = arrayProduccionT[0]['idarticulo'];
                    me.articulo = arrayProduccionT[0]['nombre'];
                    me.idingrediente = arrayProduccionT[0]['idingrediente'];
                    me.ingrediente = arrayProduccionT[0]['nombre_ingrediente'];
                    me.contenido_ingrediente = arrayProduccionT[0]['contenido'];
                    me.nombre = arrayProduccionT[0]['nombre'];
                    me.costo_ingrediente = (arrayProduccionT[0]['contenido']*arrayProduccionT[0]['costo_ingrediente'])/arrayProduccionT[0]['contenido_ingrediente']; 
                    me.cantidad_ingrediente = arrayProduccionT[0]['contenido_ingrediente'];
                    me.costo_real_ingrediente = arrayProduccionT[0]['costo_ingrediente'];
                })
                .catch(function(error) {
                    console.log(error);
                });
                //Obtener los datos de los detalles
                var urld = '/receta/obtenerDetalles?id=' + id; 
                axios.get(urld)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayDetalle = respuesta.detalles;
                    //console.log(me.arrayDetalle[0].costo_ingredientes);
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            cerrarModal() {
                this.modal = 0;
                this.tituloModal = '';
                
            },
            abrirModal(tipo) {
                this.validar_tipo = tipo;
                if (tipo == 1) {
                    this.arrayArticulo = [];
                }else if(tipo == 2){
                    this.arrayIngrediente = [];
                }else if (tipo == 3) {
                    this.arrayIngredientes = [];
                }
                
                this.modal = 1;
                this.tituloModal = "Seleccione uno o varios artículos";
            }
        },
        mounted() {
            this.listarProduccion(1, this.buscar, this.criterio);
        }
    }
</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error {
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>

