<template>
	    <div class="vld-parent">
        <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>

    <div class="container container-task">
        <div class="row">
            <div class="col-md-8 boder-left">
                <h2>Lista de productos</h2>
                <table class="table table-responsive text-center"><!--Creamos una tabla que mostrará todas las tareas-->
                        <thead>
                            <tr>
                            	<th scope="col"></th>
                            	<th scope="col"></th>
                                <th scope="col">Nombre</th>
                                <th scope="col">$</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="prod in arrayProductos" :key="prod.id"> <!--Recorremos el array y cargamos nuestra tabla-->
                            	<td v-text="prod.catname"></td>
                            	<td> <img :src="'images/' + prod.foto" width="100px" class="imgproducto">  </td>
                                <td v-text="prod.nombre"></td>
                                <td v-text="prod.precio"></td>
                                <td>
                                    <!--Botón modificar, que carga los datos del formulario con la tarea seleccionada-->
                                    <button class="btn" @click="loadFieldsUpdate(prod)">Modificar</button>
                                    <!--Botón que borra la tarea que seleccionemos-->
                                    <button class="btn" @click="deleteTask(prod)">Borrar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <div class="col-md-4 border-left">

            <!--- <div v-if="loading"><div class="spinner-border" role="status">
  <span class="sr-only">Cargando...</span>
</div></div> 

            <div v-else>--->
                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
                <h3>Crear o Modificar Productos</h3>
                    <label>Nombre</label>
                    <input v-model="nombre" type="text" class="form-control">

                </div>
                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
                    <label>Descripción</label>
                    <input v-model="descripcion" type="text" class="form-control">

                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <input type="file" class="form-control" v-on:change="onImageChange" ref="myFileInput">

                </div>
                <div class="form-group">
                    <label>Categoria</label>

                      <select class="form-control" v-model="categoria">
    				<option v-for="categoria in arrayCategorias" :value="categoria.id" :key="categoria.id">
     					{{ categoria.nombre }}
    					</option>
  					</select>

                    <!-- <input v-model="categoria" type="text" class="form-control"> --->

                </div>
                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
                    <label>Precio</label>
                    <input v-model="precio" type="text" class="form-control">

                </div>
                <div class="container-buttons">
                    <!-- Botón que añade los datos del formulario, solo se muestra si la variable update es igual a 0-->
                    <button v-if="update == 0" @click="crearProducto()" class="btn btn-success">Añadir</button>
                    <!-- Botón que modifica la tarea que anteriormente hemos seleccionado, solo se muestra si la variable update es diferente a 0-->
                    <button v-if="update != 0" @click="updateTasks()" class="btn btn-warning">Actualizar</button>
                    <!-- Botón que limpia el formulario y inicializa la variable a 0, solo se muestra si la variable update es diferente a 0-->
                    <button v-if="update != 0" @click="clearFields()" class="btn">Atrás</button>
                </div>
            </div>
            <!--- </div> --->
        </div>
    </div>

</div>

</template>

<script>
import {bus} from "../app";

    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

    

    export default {
    	name: 'B',
        data(){
            return{
            	isLoading: true,
                nombre:"",
                descripcion:"",
                categoria:"",
                image:"",
                precio:"", //Esta variable, mediante v-model esta relacionada con el input del formulario
                update:0, /*Esta variable contrarolará cuando es una nueva tarea o una modificación, si es 0 significará que no hemos seleccionado
                          ninguna tarea, pero si es diferente de 0 entonces tendrá el id de la tarea y no mostrará el boton guardar sino el modificar*/
                arrayProductos:[], //Este array contendrá las tareas de nuestra bd
                arrayCategorias:[],
            fullPage: true
            }
        },
        components: {
            Loading
        },
        methods:{

            onCancel() {
              console.log('User cancelled the loader.')
            },
        	onImageChange(e){
                console.log(e.target.files[0]);
                this.image = e.target.files[0];
            },
        	getCategorias(){
                let me =this;
                let url = '/categorias' //Ruta que hemos creado para que nos devuelva todas las tareas
                axios.get(url).then(function (response) {
                    //creamos un array y guardamos el contenido que nos devuelve el response
                    me.arrayCategorias = response.data;
                    console.log(response.data);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            getProducts(){
                let me =this;
                let url = '/productos' //Ruta que hemos creado para que nos devuelva todas las tareas
                axios.get(url).then(function (response) {
                    //creamos un array y guardamos el contenido que nos devuelve el response
                    me.arrayProductos = response.data;
                    console.log(response.data);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                }).finally(() => this.isLoading = false );
            },
            crearProducto(){
                let me =this;
                let url = '/productos/guardar' //Ruta que hemos creado para enviar una tarea y guardarla
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('image', this.image);

                formData.append('nombre', this.nombre);
                formData.append('descripcion', this.descripcion);
                formData.append('categoria', this.categoria);
                formData.append('precio', this.precio);

                /*{ //estas variables son las que enviaremos para que crear la tarea
                    'nombre':this.nombre,
                    'descripcion':this.descripcion,
                    'categoria':this.categoria,
                    'precio':this.precio,
                    'image':this.image,
                }
                */

                this.isLoading = true;

                axios.post(url, formData ,config)
            .then(function (response) {
                    me.getProducts();//llamamos al metodo getTask(); para que refresque nuestro array y muestro los nuevos datos
                    me.clearFields();//Limpiamos los campos e inicializamos la variable update a 0
                })
                .catch(function (error) {
                    console.log(error);
                });   
                
            },
            updateTasks(){/*Esta funcion, es igual que la anterior, solo que tambien envia la variable update que contiene el id de la
                tarea que queremos modificar*/
                let me = this;
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('id', this.update);
                formData.append('nombre', this.nombre);
                formData.append('descripcion', this.descripcion);
                formData.append('categoria', this.categoria);
                formData.append('precio', this.precio);

                this.isLoading = true;

                axios.post('/productos/actualizar',formData, config).then(function (response) {
                   me.getProducts();//llamamos al metodo getTask(); para que refresque nuestro array y muestro los nuevos datos
                   me.clearFields();//Limpiamos los campos e inicializamos la variable update a 0
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadFieldsUpdate(data){ //Esta función rellena los campos y la variable update, con la tarea que queremos modificar

                this.update = data.id
                let me =this;

                let url = '/productos/buscar?id='+this.update;
                axios.get(url).then(function (response) {
                    me.nombre= response.data.nombre;
                    me.precio= response.data.precio;
                    me.descripcion= response.data.descripcion;
                    me.categoria= response.data.categoria_id;
                    
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                }); 
            },
            deleteTask(data){//Esta nos abrirá un alert de javascript y si aceptamos borrará la tarea que hemos elegido
                let me =this;
                let task_id = data.id
                if (confirm('¿Seguro que deseas borrar este producto?')) {
                    axios.delete('/productos/borrar/'+task_id
                    ).then(function (response) {
                        me.getProducts();
                    })
                    .catch(function (error) {
                        console.log(error);
                    }); 
                }
            },
            clearFields(){/*Limpia los campos e inicializa la variable update a 0*/
                this.nombre = "";
                this.categoria = "";
                this.descripcion = "";
                this.precio = "";
                //this.image = "";
                //fileInput.file = null;
                this.$refs.myFileInput.value = '';

                this.update = 0;

            }
        },
        mounted() {

        	bus.$on('changeIt', (data) => {
      this.getCategorias();
    })

           this.getProducts(); 

           this.getCategorias();

        }
    }
</script>