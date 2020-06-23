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
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" @click="abrirModal('persona','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3"  v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento">Numero Documento</option>
                                    <option value="email">email</option>
                                    <option value="telefono">Telefono</option>
                                </select>
                                <input type="text" @keyup.enter = "listarPersona(1,buscar,criterio)" v-model="buscar" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarPersona(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo Documento</th>
                            <th>N. Documento</th>
                            <th>Direccion</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="persona in arrayPersona" :key="persona.id">
                            <td>
                                <button type="button" @click="abrirModal('persona','actualizar',persona)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                            </td>
                            <td v-text="persona.nombre"></td>
                            <td v-text="persona.tipo_documento"></td>
                            <td v-text="persona.num_documento"></td>
                            <td v-text="persona.direccion"></td>
                            <td v-text="persona.telefono"></td>
                            <td v-text="persona.email"></td>
                            <td v-text="persona.usuario"></td>
                            <td v-text="persona.rol"></td>
                        </tr>
                        </tbody>
                    </table>
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
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Empresa</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="tipo_documento" class="form-control" placeholder="Tipo de documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">#Documento</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_documento" class="form-control" placeholder="Numero de documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Direccion de la persona">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" class="form-control" placeholder="Telefono de  la persona">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="email" class="form-control" placeholder="Email de  la persona">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Password</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="Password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="idrol">
                                        <option value="0">Seleccione</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>
                                </div>
                            </div>

                            <div v-show="errorPersona" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in arrayErroresPersona" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                        </form>
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
                personaId : 0,
                nombre: '',
                tipo_documento : '',
                num_documento  : '',
                direccion      : '',
                telefono       : '',
                email          : '',
                usuario        : '',
                password       : '',
                idrol          : 0,
                arrayPersona:[],
                modal : 0,
                tituloModal :'',
                tipoAccion : 0,
                errorPersona :0,
                arrayErroresPersona :[],
                arrayRol : [],
                pagination:{
                    'total'        : 0,
                    'current_page' : 0,
                    'per_page'     : 0,
                    'last_page'    : 0,
                    'from'         : 0,
                    'to'           : 0,
                },
                offset :3,
                criterio : 'nombre',
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
                me.listarPersona(page,buscar,criterio);
            },
            listarPersona(page,buscar,criterio){
                let me = this;
                var url = '/user?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        me.arrayPersona = respuesta.personas.data;
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
                        me.arrayPersona = [response.data];
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
                axios.put('/proveedor/actualizar', {
                    'nombre': this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento'  : this.num_documento,
                    'direccion'      : this.direccion,
                    'telefono'       : this.telefono,
                    'email'          : this.email,
                    'usuario'        : this.usuario,
                    'password'       : this.password,
                    'idrol'          : this.idrol,
                    'id' : this.personaId
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
                    this.arrayErroresPersona.push("El nombre de la persona no puede ser vacio");
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
            this.listarPersona(1,this.buscar,this.criterio);
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
</style>
