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
                    <i class="fa fa-align-justify"></i> Clientes
                    <button type="button" @click="abrirModal('persona','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio" >
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento">#Documento</option>
                                    <option value="email">Email</option>
                                    <option value="telefono">Teléfono</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarPersona(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPersona(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo de Documento</th>
                                <th>Número</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="persona in arrayPersona" :key="persona.id">
                                <td>
                                    <button type="button" @click="abrirModal('persona','actualizar',persona)" class="btn btn-warning btn-sm" >
                                        <i class="icon-pencil"></i>
                                    </button> 
                                </td>
                                <td v-text="persona.nombre"></td>
                                <td v-text="persona.tipo_documento"></td>
                                <td v-text="persona.num_documento"></td>
                                <td v-text="persona.direccion"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.email"></td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent ="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent ="cambiarPagina(pagination.current_page + 1,buscar, criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de persona">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASS">PASS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Número Documento</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_documento" class="form-control" placeholder="Número de Documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Telélefono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Telélefono">
                                </div>
                            </div>
                            <div v-show="errorPersona" class="form-group row div-error">
                                <div class="text-center alert alert-danger">
                                    <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal" class="btn btn-secondary" >Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarPersona()" class="btn btn-primary">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarPersona()" class="btn btn-primary">actualizar</button>

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
    export default {
        data() {
            return {
                persona_id : 0,
                nombre : '',
                tipo_documento : 'DNI',
                num_documento : '',
                direccion : '',
                telefono : '',
                email : '',
                modal: 0,
                arrayPersona: [],
                tituloModal: '',
                tipoAccion: 0,
                errorPersona : 0,
                errorMostrarMsjPersona : [],
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
                buscar : ''
            }
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
                if(from<1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page) {
                    to =this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
            listarPersona(page, buscar, criterio) {
                let me = this;
                var url = '/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio; 
                axios.get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayPersona = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersona(page, buscar, criterio);
            },
            registrarPersona() {
                if (this.validarPersona()){
                    return;
                }
                let me = this;
                axios.post('/cliente/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email': this.email
                }).then(function(response){
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarPersona(){
                if (this.validarPersona()){
                    return;
                }
                let me = this;
                axios.put('/cliente/actualizar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email': this.email,
                    'id': this.persona_id
                }).then(function(response){
                    me.cerrarModal();
                    me.listarPersona(1, '', 'nombre');
                }).catch(function (error){
                    console.log(error);
                });
            },
            validarPersona(){
                this.errorPersona = 0;
                this.errorMostrarMsjPersona =[];

                if (!this.nombre) { 
                    this.errorMostrarMsjPersona.push ("El nombre del cliente no puede estar vacío.");
                }
                if (this.errorMostrarMsjPersona.length) {
                    this.errorPersona = 1;
                }
                return this.errorPersona;
            },
            cerrarModal() {
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.tipo_documento='DNI';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.errorPersona=0;
            },
            abrirModal(modelo,accion,data=[]) {
                switch(modelo) {
                    case 'persona' : {
                        switch (accion) {
                            case 'registrar': {
                                this.modal = 1;
                                this.nombre='';
                                this.tipo_documento='DNI';
                                this.num_documento='';
                                this.direccion='';
                                this.telefono='';
                                this.email='';
                                this.tituloModal = "Registrar Cliente";
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Cliente';
                                this.tipoAccion = 2;
                                this.persona_id=data['id'];
                                this.nombre=data['nombre'];
                                this.tipo_documento=data['tipo_documento'];
                                this.num_documento=data['num_docuemnto'];
                                this.direccion=data['diereccion'];
                                this.telefono=data['telefono'];
                                this.email=data['email'];
                                break;
                            }
                        }
                        break;
                    }
                    
                }
            }
        },
        mounted() {
            this.listarPersona(1, this.buscar, this.criterio);
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
</style>

