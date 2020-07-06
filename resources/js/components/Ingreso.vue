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
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <template v-if="listado">
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
                </template>

                <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <select v-model="idproveedor" class="form-control">
                                        <option value="0">Seleccione</option>
                                        <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
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
                                    <label>Articulo
                                        <span style="color: red" v-show="idarticulo==0">(*Seleccione)</span>
                                    </label>
                                    <div class="form-inline">
                                        <input type="text" v-model="codigo" placeholder="Ingrese Articulo" @keyup.enter="buscarArticulo">
                                        <button class="btn btn-primary" @onclick="buscarArticulo">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio
                                        <span style="color: red" v-show="precio==0">(*Ingrese Precio)</span>
                                    </label>
                                    <input type="number" value="0" class="form-control" v-model="precio" step="any">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad
                                        <span style="color: red" v-show="cantidad==0">(*Cantidad)</span>
                                    </label>
                                    <input type="number" v-model="cantidad" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnagregar" @click="agregarDetalle()"><i class="icon-plus"></i></button>
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
                                    <tbody v-if="arrayDetalle.length">
                                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                        <td>
                                            <button type="button" class="btn btn-danger" @click="eliminarDetalle(index)">
                                                <i class="icon-close"></i>
                                            </button>
                                        </td>
                                        <td v-text="detalle.articulo">
                                        </td>

                                        <td>
                                            <input type="number" value="3" class="form-control" v-model="detalle.precio">
                                        </td>
                                        <td>
                                            <input type="number" value="2" class="form-control" v-model="detalle.cantidad">
                                        </td>
                                        <td>
                                            {{detalle.precio*detalle.cantidad}}
                                        </td>
                                    </tr>

                                    <tr style="background-color:#2eadd3;">
                                        <th colspan="4" align="right">Total Parcial:</th>
                                        <th>$ {{ totalParcial = (total - impuesto)}}</th>
                                    </tr>

                                    <tr style="background-color: #2eadd3;">
                                        <th colspan="4" align="right">Total Impuesto:</th>
                                        <th>$ {{ totalImpuesto = ((total * impuesto)/(1+impuesto)).toFixed(2)}}</th>
                                    </tr>

                                    <tr style="background-color: #2eadd3;">
                                        <th colspan="4" align="left">Total Neto:</th>
                                        <th>$ {{ total = (calcularTotal).toFixed(2) }}</th>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th colspan="5">No hay elementos agregados</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                </template>

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
    import vSelect from 'vue-select';
    export default {
        data(){
            return{
                idarticulo : 0,
                codigo     :'',
                articulo   : '',
                ingresoId : 0,
                idproveedor : 0,
                tipo_comprobante : 'Factura',
                serie_comprobante : '',
                num_comprobante   : '',
                fecha_hora        : '',
                impuesto          : 0.18,
                total             : 0.0,
                totalImpuesto     : 0.0,
                totalParcial      : 0.0,
                precio            : 0,
                cantidad          : 0,
                arrayIngreso      :[],
                arrayDetalle      :[],
                arrayProveedor    :[],
                arrayArticulo     :[],
                listado :1,
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
        components:{
            vSelect
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

            },
            calcularTotal: function()
            {
                var resultado = 0;
                for(var i=0; i<this.arrayDetalle.length;i++){
                    resultado = resultado+(parseInt(this.arrayDetalle[i]['precio']) * parseInt(this.arrayDetalle[i]['cantidad']));
                }
                return resultado;
            }

        },
        methods:{
            cambiarPagina(page,buscar,criterio){
                let me = this;
                this.pagination.current_page = page;
                me.listarIngreso(page,buscar,criterio);
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            agregarDetalle(){
                let me = this;
                if(me.idarticulo == 0 || me.cantidad ==0 || me.precio==0){

                }
                else{
                    if(me.encuentra(me.idarticulo)){
                        alert("el Elemeneto ya se encuantra agregado");
                    }
                    else{
                        me.arrayDetalle.push({
                            idarticulo : me.idarticulo,
                            articulo   : me.articulo,
                            cantidad   : me.cantidad,
                            precio     : me.precio
                        });
                        me.codigo ='';
                        me.idarticulo = 0;
                        me.articulo = '';
                        me.cantidad = 0;
                        me.precio = 0;
                    }
                }
            },
            encuentra(id){
                var sw = false;

                for(var  i = 0; i<this.arrayDetalle.length;i++)
                {
                    if(this.arrayDetalle[i]['idarticulo'] == id){
                        sw = true;
                    }
                }
                return sw;
            },

            buscarArticulo(){
                let me=this;
                var url= '/articulo/buscarArticulo?filtro=' + me.codigo;
                    axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.arrayArticulo=respuesta.articulos;
                    if(me.arrayArticulo.length>0){
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                    }
                    else
                    {
                        me.articulo = "No se encontraron articulos";
                        me.idarticulo = 0;
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            selectProveedor(){
                let me=this;
                var url= '/proveedor/selectProveedor';
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.arrayProveedor=respuesta.proveedores;
                })
                .catch(function (error) {
                        console.log(error);
                });


            },
            getDatosProveedor(val1){
                let me = this;
                //me.loading = true;
                me.idproveedor = val1.id;
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

            mostrarDetalle(){
                this.listado = 0;
            },
            ocultarDetalle(){
                this.listado = 1;
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
            let me = this;
            this.listarIngreso(1,this.buscar,this.criterio);
            me.selectProveedor();
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
