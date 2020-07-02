<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ingresos</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" @click="abrirModal('ingreso','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3"  v-model="criterio">
                                    <option value="tipo_comprobante">Tipo Comprobante</option>
                                    <option value="num_comprobante">Numero Comprobante</option>
                                    <option value="fecha_hora">Fecha</option>
                                </select>
                                <input type="text" @keyup.enter = "listarIngreso(1,buscar,criterio)" v-model="buscar" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarIngreso(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Usuario </th>
                            <th>Proveedor</th>
                            <th>Tipo Comprobante</th>
                            <th>Serie </th>
                            <th>Num Comprobante</th>
                            <th>Fecha-Hora</th>
                            <th>Total</th>
                            <th>Impuesto</th>
                            <th>Estado</th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                            <td>
                                <button type="button" @click="abrirModal('ingreso','actualizar',ingreso)" class="btn btn-success btn-sm">
                                    <i class="icon-eye"></i>
                                </button> &nbsp;

                                <template v-if="ingreso.estado = 'Registrado'">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(ingreso.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>

                            </td>
                            <td v-text="ingreso.usuario"></td>
                            <td v-text="ingreso.proveedor"></td>
                            <td v-text="ingreso.tipo_comprobante"></td>
                            <td v-text="ingreso.serie_comprobante"></td>
                            <td v-text="ingreso.num_comprobante"></td>
                            <td v-text="ingreso.fecha_hora"></td>
                            <td v-text="ingreso.total"></td>
                            <td v-text="ingreso.impuesto"></td>
                            <td v-text="ingreso.estado"></td>
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
                                <a class="page-link" href="#" @click="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Proveedor(*)</label>
                                <select class="form-control">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                             <label for="">Impuesto(*)</label>
                            <input type="text" class="form-control" v-model="impuesto">
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo Comprobante(*)</label>
                                <select class="form-control" v-model="tipo_comprobante">
                                    <option value="Boleta">Boleta</option>
                                    <option value="Factura">Factura</option>
                                    <option value="Ticket">Ticket</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serie Comprobante</label>
                                <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numero Comprobante(*)</label>
                                <input type="text" class="form-control" v-model="num_comprobante" placeholder="000x">
                            </div>
                        </div>

                    </div>
                    <div class="form-group row border">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Articulo</label>
                                <div class="form-inline">
                                    <input type="text" v-model="idarticulo" placeholder="Ingrese Articulo">
                                    <button class="btn btn-primary">...</button>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Precio</label>
                                <input type="number" value="0" class="form-control" v-model="precio" step="any">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cantidad</label>
                                <input type="number" v-model="cantidad" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Articulo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-danger ">
                                                <i class="icon-close"></i>
                                            </button>
                                        </td>
                                        <td>Articulo</td>
                                        <td>
                                            <input type="number" value="3" class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" value="2" class="form-control">
                                        </td>
                                        <td>
                                            $ 6.66
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                        <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary"  @click="actualizarPersona()">Actualizar</button>
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
        data(){
            return{
                ingresoId : 0,
                idproveedor : 0,
                tipo_comprobante : 'Factura',
                serie_comprobante : '',
                num_comprobante   : '',
                fecha_hora        : '',
                impuesto          : 0.19,
                total             : 0.0,
                arrayIngreso      :[],
                arrayDetalle      :[],

                modal : 0,
                tituloModal :'',
                tipoAccion : 0,
                errorIngreso :0,
                arrayErroresIngreso :[],

                pagination:{
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0,
                },
                offset :3,
                criterio : 'num_comprobante',
                buscar   : '',
            }
        },
        computed:{
            isActived(){
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }
        },
        methods:{
            cambiarPagina(page,buscar,criterio){
                let me = this;
                this.pagination.current_page = page;
                me.listarIngreso(page,buscar,criterio);
            },
            listarIngreso(page,buscar,criterio){
                let me = this;
                var url = '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        me.arrayIngreso = respuesta.ingresos.data;
                        me.pagination     = respuesta.pagination;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            listarpersonaId(){
                let me = this;
                axios.get('/user/'+this.personaId)
                    .then(function (response) {
                        // handle success
                        me.arrayPersona = response.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },

            selectRol(){
                let me = this;
                axios.get('/selectRol')
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        me.arrayRol = respuesta.roles;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            desactivarUsuario(id){
                this.personaId = id;
                var r = confirm("Desea la eliminacion del usuario!");
                if (r == true) {
                    let me = this;
                    axios.put('/user/desactivar', {
                        'id': id
                    })
                        .then(function (response) {
                            me.listarpersonaId();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            activarUsuario(id){
                this.personaId = id;
                var r = confirm("Desea activar el usuario");
                if (r == true) {
                    let me = this;
                    axios.put('/user/activar', {
                        'id': id
                    })
                        .then(function (response) {
                            me.listarpersonaId();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            registrarPersona(){
                if(this.validarPersona()){
                    return;
                }

                let me = this;
                axios.post('/user', {
                    'nombre': this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento'  : this.num_documento,
                    'direccion'      : this.direccion,
                    'telefono'       : this.telefono,
                    'email'          : this.email,
                    'usuario'        : this.usuario,
                    'password'       : this.password,
                    'idrol'          : this.idrol
                })
                    .then(function (response) {
                        me.cerrarModal();
                        me.listarPersona(1,'','nombre');
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            actualizarPersona(){
                if(this.validarPersona()){
                    return;
                }
                let me = this;
                axios.put('/user/actualizar', {
                    'nombre': this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento'  : this.num_documento,
                    'direccion'      : this.direccion,
                    'telefono'       : this.telefono,
                    'email'          : this.email,
                    'usuario'        : this.usuario,
                    'password'       : this.password,
                    'idrol'          : this.idrol,
                    'id'             : this.personaId
                })
                    .then(function (response) {
                        me.cerrarModal();
                        me.listarpersonaId();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            abrirModal(modelo,accion,data = [])
            {
                this.selectRol();
                switch (modelo) {
                    case "persona":
                    {
                        switch (accion) {
                            case 'registrar':
                            {
                                this.modal=1;
                                this.nombre ='';
                                this.tipo_documento = 'CEDULA';
                                this.num_documento  = '';
                                this.direccion      = '';
                                this.telefono       = '';
                                this.email          = '';
                                this.usuario        = '';
                                this.password       = '';
                                this.idrol          = 0;
                                this.tituloModal    = 'Registrar Usuario';
                                this.tipoAccion  = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.personaId         = data.id;
                                this.modal             = 1;
                                this.nombre            = data.nombre;
                                this.tipo_documento    = data.tipo_documento;
                                this.num_documento     = data.num_documento;
                                this.direccion         = data.direccion;
                                this.telefono          = data.telefono;
                                this.email             = data.email;
                                this.usuario           = data.usuario;
                                this.password          = data.password;
                                this.idrol             = data.idrol;
                                this.tituloModal = 'Actualizar Usuario';
                                this.tipoAccion  = 2;
                            }
                        }
                    }
                }
            },
            validarPersona(){
                this.errorPersona = 0;
                this.arrayErroresPersona = [];
                if(!this.nombre){
                    this.arrayErroresPersona.push("El nombre del usuario no puede ser vacio");
                }
                if(!this.usuario){
                    this.arrayErroresPersona.push("El usuario no puede ser vacio");
                }
                if(!this.password){
                    this.arrayErroresPersona.push("El password del usuario no puede ser vacio");
                }
                if(this.idrol===0){
                    this.arrayErroresPersona.push("Seleccione un tipo de rol para usuarios");
                }

                if(this.arrayErroresPersona.length){
                    this.errorPersona = 1;
                }
                return this.errorPersona;
            },
            cerrarModal()
            {
                this.modal=0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo_documento = '';
                this.num_documento  = '';
                this.direccion      = '';
                this.telefono       = '';
                this.email          = '';
                this.usuario       = '';
                this.password = '';
                this.idrol = 0;
            }
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
.mostrar{
    display: list-item !important;
    opacity: 1  !important;
    position: absolute !important;
    background-color: #3c29297a  !important;
}
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }

    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color:red;
        font-weight: bold;

    }
    @media(min-width:600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }

</style>
