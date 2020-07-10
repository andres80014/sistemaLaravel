<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ventas</a></li>
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
                <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3"  v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Numero Comprobante</option>
                                        <option value="fecha_hora">Fecha</option>
                                    </select>
                                    <input type="text" @keyup.enter = "listarVenta(1,buscar,criterio)" v-model="buscar" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary" @click="listarVenta(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Usuario </th>
                                    <th>Cliente</th>
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

                                <tr v-for="venta in arrayVenta" :key="venta.id">
                                    <td>
                                        <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                        </button> &nbsp;

                                        <template v-if="venta.estado == 'Registrado'">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>

                                    </td>
                                    <td v-text="venta.usuario"></td>
                                    <td v-text="venta.cliente"></td>
                                    <td v-text="venta.tipo_comprobante"></td>
                                    <td v-text="venta.serie_comprobante"></td>
                                    <td v-text="venta.num_comprobante"></td>
                                    <td v-text="venta.fecha_hora"></td>
                                    <td v-text="venta.total"></td>
                                    <td v-text="venta.impuesto"></td>
                                    <td v-text="venta.estado"></td>
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

                <template v-else-if="listado ==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <select v-model="idcliente" class="form-control">
                                        <option value="0">Seleccione</option>
                                        <option v-for="cliente in arrayCliente" :key="cliente.id" :value="cliente.id"
                                                v-text="cliente.nombre"></option>
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

                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in arrayErroresVenta" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Articulo
                                        <span style="color: red" v-show="idarticulo==0">(*Seleccione)</span>
                                    </label>
                                    <div class="form-inline">
                                        <input type="text" v-model="codigo" placeholder="Ingrese Articulo" @keyup.enter="buscarArticuloVenta">
                                        <button class="btn btn-primary" @click="abrirModal">...</button>
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
                                    <label>Descuento</label>
                                    <input type="number" v-model="descuento" class="form-control">
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
                                        <th>Descuento</th>
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
                                            <input type="number" class="form-control" v-model="detalle.precio">
                                        </td>
                                        <td>
                                            <span style="color: red" v-show="detalle.cantidad>detalle.stock">Stock disponible : {{detalle.stock}} </span>
                                            <input type="number" class="form-control" v-model="detalle.cantidad">
                                        </td>

                                        <td>
                                            <span style="color: red" v-show="detalle.descuento>(detalle.precio * detalle.cantidad)">Descuento superior </span>
                                            <input type="number"  class="form-control" v-model="detalle.descuento">
                                        </td>

                                        <td>
                                            {{detalle.precio*detalle.cantidad-detalle.descuento}}
                                        </td>


                                    </tr>

                                    <tr style="background-color:#2eadd3;">
                                        <th colspan="5" align="right">Total Parcial:</th>
                                        <th>$ {{ totalParcial = (total - impuesto)}}</th>
                                    </tr>

                                    <tr style="background-color: #2eadd3;">
                                        <th colspan="5" align="right">Total Impuesto:</th>
                                        <th>$ {{ totalImpuesto = ((total * impuesto)/(1+impuesto)).toFixed(2)}}</th>
                                    </tr>

                                    <tr style="background-color: #2eadd3;">
                                        <th colspan="5" align="left">Total Neto:</th>
                                        <th>$ {{ total = (calcularTotal).toFixed(2) }}</th>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th colspan="6">No hay elementos agregados</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- ver detlle ingreso-->
                <template v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <p v-text="impuesto"></p>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante(*)</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th>Articulo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.articulo">
                                        </td>

                                        <td v-text="detalle.precio">
                                        </td>

                                        <td v-text="detalle.cantidad">
                                        </td>

                                        <td>
                                            {{detalle.precio*detalle.cantidad-detalle.descuento}}
                                        </td>
                                    </tr>

                                    <tr style="background-color:#2eadd3;">
                                        <th colspan="3" align="right">Total Parcial:</th>
                                        <th>$ {{ totalParcial = (total - impuesto)}}</th>
                                    </tr>

                                    <tr style="background-color: #2eadd3;">
                                        <th colspan="3" align="right">Total Impuesto:</th>
                                        <th>$ {{ totalImpuesto = ((total * impuesto)/(1+impuesto)).toFixed(2)}}</th>
                                    </tr>

                                    <tr style="background-color: #2eadd3;">
                                        <th colspan="3" align="left">Total Neto:</th>
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
                            </div>
                        </div>
                    </div>
                </template>
                <!-- fin ver detlle ingreso-->



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
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3"  v-model="criterioArticulo">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Codigo</option>
                                    </select>
                                    <input type="text" @keyup.enter = "listarArticulo(buscarArticul,criterioArticulo)" v-model="buscarArticul" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary" @click="listarArticulo(buscarArticul,criterioArticulo)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                    <td>
                                        <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                            <i class="icon-check"></i>
                                        </button> &nbsp;
                                    </td>

                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.nombre_categoria"></td>

                                    <td v-text="articulo.precio_venta"></td>
                                    <td v-text="articulo.stock"></td>

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
                stock      : 0,
                idarticulo : 0,
                codigo     :'',
                articulo   : '',
                ventaId : 0,
                idcliente : 0,
                tipo_comprobante : 'Factura',
                serie_comprobante : '',
                num_comprobante   : '',
                fecha_hora        : '',
                impuesto          : 0.18,
                total             : 0.0,
                totalImpuesto     : 0.0,
                totalParcial      : 0.0,
                precio            : 0,
                descuento         : 0,
                cantidad          : 0,
                arrayVenta      :[],
                arrayDetalle      :[],
                arrayCliente    :[],
                arrayArticulo     :[],
                listado :1,
                modal : 0,
                tituloModal :'',
                tipoAccion : 0,
                errorVenta :0,
                arrayErroresVenta :[],
                buscarArticul  : '',
                criterioArticulo : 'nombre',
                cliente : '',

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
                    resultado = resultado+(parseInt(this.arrayDetalle[i]['precio']) * parseInt(this.arrayDetalle[i]['cantidad'])-parseInt(this.arrayDetalle[i]['descuento']));
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

            listarArticulo(buscar,criterio){
                let me = this;
                var url = '/articulo/listarArticulosStock?buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        me.arrayArticulo = respuesta.articulos.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            agregarDetalleModal(data = []){
                let me = this;
                if(me.encuentra(data['id'])){
                    alert("el Elemeneto ya se encuantra agregado");
                }
                else{
                    me.arrayDetalle.push({
                        idarticulo : data['id'],
                        articulo   : data['nombre'],
                        cantidad   : 1,
                        precio     : data['precio_venta'],
                        descuento  : 0,
                        stock      : data['stock']
                    });
                }
            },

            verIngreso(id){
                let me = this;
                var arrayIngresoTem = [];

                me.listado = 2;
                //obtener cabecera
                var url = '/ingreso/obtenerCabecera?id=' + id;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        arrayIngresoTem = respuesta.ingreso.data;
                        me.proveedor = me.arrayIngreso[0]['proveedor'];
                        me.tipo_comprobante = me.arrayIngreso[0]['tipo_comprobante'];
                        me.serie_comprobante = me.arrayIngreso[0]['serie_comprobante'];
                        me.num_comprobante   = me.arrayIngreso[0]['num_comprobante'];
                        me.impuesto          = me.arrayIngreso[0]['impuesto'];
                        me.total             = me.arrayIngreso[0]['total'];
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
                //obtener detalles

                var urldet = '/ingreso/obtenerDetalles?id=' + id;
                axios.get(urldet)
                    .then(function (response) {
                        // handle success
                        var respuestadet     = response.data;
                        me.arrayDetalle = respuestadet.detalles;
                        console.log(me.arrayDetalle);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
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
                        if(me.cantidad > me.stock){
                            alert("No hay stock dsponible ");
                        }
                        else{
                            me.arrayDetalle.push({
                                idarticulo : me.idarticulo,
                                articulo   : me.articulo,
                                cantidad   : me.cantidad,
                                precio     : me.precio,
                                descuento  : me.descuento,
                                stock      : me.stock
                            });
                            me.codigo ='';
                            me.idarticulo = 0;
                            me.articulo = '';
                            me.cantidad = 0;
                            me.precio = 0;
                            me.descuento = 0;
                            me.stock     = 0;
                        }
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

            buscarArticuloVenta(){
                let me=this;
                var url= '/articulo/buscarArticuloVenta?filtro=' + me.codigo;
                    axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.arrayArticulo=respuesta.articulos;
                    console.log(respuesta.articulos);
                    if(me.arrayArticulo.length>0){
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                        me.precio     = me.arrayArticulo[0]['precio_venta'];
                        me.stock      = me.arrayArticulo[0]['stock'];
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
            selectCliente(){
                let me=this;
                var url= '/cliente/selectCliente';
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.arrayCliente=respuesta.clientes;
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
            listarVenta(page,buscar,criterio){
                let me = this;
                var url = '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        me.arrayVenta = respuesta.ventas.data;
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
            desactivarIngreso(id){
                this.personaId = id;
                var r = confirm("Desea la anulacion del ingreso!");
                if (r == true) {
                    let me = this;
                    axios.put('/ingreso/desactivar', {
                        'id': id
                    })
                        .then(function (response) {
                            me.listarIngreso(1,'','num_comprobante');
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            registrarIngreso(){
                if(this.validarIngreso()){
                    return;
                }

                let me = this;
                axios.post('/ingreso', {
                    'idproveedor'       : this.idproveedor,
                    'tipo_comprobante'  : this.tipo_comprobante,
                    'serie_comprobante' : this.serie_comprobante,
                    'num_comprobante'   : this.num_comprobante,
                    'impuesto'          : this.impuesto,
                    'total'             : this.total,
                    'data'              : this.arrayDetalle
                })
                    .then(function (response) {
                        me.listado = 1;
                        me.listarIngreso(1,'','num_comprobante');
                        me.idproveedor= 0;
                        me.tipo_comprobante  = 'Factura';
                        me.serie_comprobante = '';
                        me.num_comprobante   = '';
                        me.impuesto          = 0.18;
                        me.total             = 0.0;
                        me.idarticulo        = 0;
                        me.articulo          = '';
                        me.cantidad          = 0;
                        me.precio            = 0;
                        me.arrayDetalle = [];
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
            cerrarModal(){
                this.modal=0;
            },

            abrirModal(){
                this.arrayArticulo = [];
                this.modal=1;
                this.tituloModal    = 'Seleccione articulos';
            },
            validarIngreso(){
                this.errorIngreso = 0;
                this.arrayErroresIngreso = [];
                if(this.idproveedor == 0 || this.idproveedor==''){
                    this.arrayErroresIngreso.push("Selecione un proveedor");
                }
                if(this.tipo_comprobante == 0){
                    this.arrayErroresIngreso.push("Seleccione el tipo de comprobante");
                }
                if(!this.num_comprobante){
                    this.arrayErroresIngreso.push("Digite el numero de comprobante");
                }
                if(!this.impuesto){
                    this.arrayErroresIngreso.push("Digite impuesto de compra");
                }
                if(this.arrayDetalle.length<=0){
                    this.arrayErroresIngreso.push("Ingrese detalles");
                }
                if(this.arrayErroresIngreso.length){
                    this.errorIngreso = 1;
                }
                return this.errorIngreso;
            },

            mostrarDetalle(){
                let me = this;
                me.listado = 0;
                me.idproveedor= 0;
                me.tipo_comprobante  = 'Factura';
                me.serie_comprobante = '';
                me.num_comprobante   = '';
                me.impuesto          = 0.18;
                me.total             = 0.0;
                me.idarticulo        = 0;
                me.articulo          = '';
                me.cantidad          = 0;
                me.precio            = 0;
                me.arrayDetalle = [];
            },
            ocultarDetalle(){
                this.listado = 1;
            }
        },
        mounted() {
            let me = this;
            this.listarVenta(1,this.buscar,this.criterio);
            me.selectCliente();
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
