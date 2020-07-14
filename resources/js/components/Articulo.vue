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
                    <i class="fa fa-align-justify"></i> Articulos
                    <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>

                    <button type="button" @click="cargarPdf()" class="btn btn-info">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3"  v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" @keyup.enter = "listarArticulo(1,buscar,criterio)" v-model="buscar" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click="listarArticulo(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                            <td>
                                <button type="button" @click="abrirModal('articulo','actualizar',articulo)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;

                                <template v-if="articulo.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>

                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)">
                                        <i class="icon-ok"></i>
                                    </button>
                                </template>
                            </td>

                            <td v-text="articulo.codigo"></td>
                            <td v-text="articulo.nombre"></td>
                            <td v-text="articulo.nombre_categoria"></td>

                            <td v-text="articulo.precio_venta"></td>
                            <td v-text="articulo.stock"></td>
                            <td v-text="articulo.descripcion"></td>

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
                                <label class="col-md-3 form-control-label">Categoria</label>
                                <div class="col-md-9">
                                    <select v-model="idcategoria" class="form-control">
                                        <option value="0">Seleccione</option>
                                        <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre">Seleccione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Codigo</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="codigo" class="form-control" placeholder="Codigo de barras">
                                    <barcode :value="codigo" :options="{ format: 'EAN-13'}"></barcode>
                                    Generando codigo
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de articulo">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Precio Venta</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="precio_venta" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="stock" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Descripcion Articulo">
                                </div>
                            </div>
                            <div v-show="errorArticulo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in arrayErroresArticulo" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary"  @click="actualizarArticulo()">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        data(){
            return{
                articuloId : 0,
                idcategoria:0,
                nombre_categoria :'',
                codigo : '',
                nombre: '',
                precio_venta :0,
                stock : '',
                descripcion:'',
                arrayArticulo:[],
                modal : 0,
                tituloModal :'',
                tipoAccion : 0,
                errorArticulo :0,
                arrayErroresArticulo :[],
                arrayCategoria : [],

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
        components: {
            'barcode': VueBarcode
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
            cargarPdf(){
                window.open('http://127.0.0.1:8000/articulo/listarPdf','_blank');
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                this.pagination.current_page = page;
                me.listarArticulo(page,buscar,criterio);
            },

            listarArticulo(page,buscar,criterio){
                let me = this;
                var url = '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        var respuesta     = response.data;
                        me.arrayArticulo = respuesta.articulos.data;
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

            selectCategoria(){
                let me = this;
                var url = '/selectCategoria';
                axios.get(url)
                    .then(function (response) {
                        // handle success
                        console.log(response);
                        me.arrayCategoria     = response.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            listarArticuloId(){
                let me = this;
                axios.get('/articulo/'+this.articuloId)
                    .then(function (response) {
                        // handle success
                        me.arrayArticulo = response.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            registrarArticulo(){
                if(this.validarArticulo()){
                    return;
                }

                let me = this;
                axios.post('/articulo', {
                    'nombre'       : this.nombre,
                    'descripcion'  : this.descripcion,
                    'idcategoria'  : this.idcategoria,
                    'codigo'       : this.codigo,
                    'precio_venta' : this.precio_venta,
                    'stock'        :this.stock
                })
                    .then(function (response) {
                        me.cerrarModal();
                        me.listarArticulo(1,'','nombre');
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            actualizarArticulo(){
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.put('/articulo/actualizar', {
                    'nombre'       : this.nombre,
                    'descripcion'  : this.descripcion,
                    'id'           : this.articuloId,
                    'idcategoria'  : this.idcategoria,
                    'codigo'       : this.codigo,
                    'precio_venta' : this.precio_venta,
                    'stock'        :this.stock
                })
                    .then(function (response) {
                        me.cerrarModal();
                        me.listarArticuloId();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            abrirModal(modelo,accion,data = []){

                switch (modelo) {

                    case "articulo":
                    {
                        switch (accion) {
                            case 'registrar':
                            {
                                this.modal=1;
                                this.tituloModal = 'Registrar Articulo';
                                this.tipoAccion  = 1;
                                this.nombre ='';
                                this.descripcion ='';
                                this.idcategoria = 0;
                                this.codigo = '';
                                this.precio_venta = 0;
                                this.stock = 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.articuloId = data.id;
                                this.modal=1;
                                this.nombre =data.nombre;
                                this.descripcion =data.descripcion;
                                this.idcategoria = data.idcategoria;
                                this.codigo = data.codigo;
                                this.precio_venta = data.precio_venta;
                                this.stock = data.stock;
                                this.tituloModal = 'Actualizar Articulo';
                                this.tipoAccion  = 2;
                            }
                        }
                    }
                    this.selectCategoria();
                }
            },
            desactivarArticulo(id){
                var r = confirm("Desea la eliminacion el registro!");
                if (r == true) {
                    let me = this;
                    axios.put('/articulo/desactivar', {
                        'id': id
                    })
                        .then(function (response) {
                            me.listarArticuloId();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            activarArticulo(id){
                var r = confirm("Desea activar el regitro");
                if (r == true) {
                    let me = this;
                    axios.put('/articulo/activar', {
                        'id': id
                    })
                        .then(function (response) {
                            me.listarArticuloId();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            },
            validarArticulo(){
                this.errorArticulo = 0;
                this.arrayErroresArticulo = [];
                if(!this.nombre){
                    this.arrayErroresArticulo.push("El nombre del articulo no puede ser vacio");
                }
                if(this.idcategoria==0){
                    this.arrayErroresArticulo.push("Debe seleccionar categoria");
                }
                if(!this.precio_venta){
                    this.arrayErroresArticulo.push("El precio del producto debe ser numerico y diferente de vacio");
                }
                if(!this.stock){
                    this.arrayErroresArticulo.push("El stock del producto debe ser numerico y diferente de vacio");
                }

                if(this.arrayErroresArticulo.length){
                    this.errorArticulo = 1;
                }
                return this.errorArticulo;
            },
            cerrarModal() {
                this.modal=0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion = '';
                this.idcategoria = 0;
                this.nombre_categoria = '';
                this.codigo = '';
                this.precio_venta = 0;
                this.stock = 0;
                this.errorArticulo = 0;
            }
        },
        mounted() {
            this.listarArticulo(1,this.buscar,this.criterio);
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
