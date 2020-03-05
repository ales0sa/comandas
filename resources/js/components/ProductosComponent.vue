<template>
    <div class="container container-task">
        <div class="row">
            <div class="col-md-8">
                <h2>Lista de productos</h2>
                <table class="table text-center"><!--Creamos una tabla que mostrará todas las tareas-->
                        <thead>
                            <tr>
                            	<th scope="col"></th>
                                <th scope="col">Nombre</th>
                                <th scope="col">$</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="prod in arrayProductos" :key="prod.id"> <!--Recorremos el array y cargamos nuestra tabla-->
                            	<td v-text="prod.categoria_id"></td>
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
            <div class="col-md-4">
                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
                    <label>Nombre</label>
                    <input v-model="nombre" type="text" class="form-control">

                </div>
                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
                    <label>Descripción</label>
                    <input v-model="descripcion" type="text" class="form-control">

                </div>
                <div class="form-group"><!-- Formulario para la creación o modificación de nuestras tareas-->
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
        </div>
    </div>
</template>

<script>
import {bus} from "../app";

    export default {
    	name: 'B',
        data(){
            return{
                nombre:"",
                descripcion:"",
                categoria:"",
                precio:"", //Esta variable, mediante v-model esta relacionada con el input del formulario
                update:0, /*Esta variable contrarolará cuando es una nueva tarea o una modificación, si es 0 significará que no hemos seleccionado
                          ninguna tarea, pero si es diferente de 0 entonces tendrá el id de la tarea y no mostrará el boton guardar sino el modificar*/
                arrayProductos:[], //Este array contendrá las tareas de nuestra bd
                arrayCategorias:[],
            }
        },
        methods:{
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
                });
            },
            crearProducto(){
                let me =this;
                let url = '/productos/guardar' //Ruta que hemos creado para enviar una tarea y guardarla
                axios.post(url,{ //estas variables son las que enviaremos para que crear la tarea
                    'nombre':this.nombre,
                    'descripcion':this.descripcion,
                    'categoria':this.categoria,
                    'precio':this.precio,
                }).then(function (response) {
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
                axios.put('/productos/actualizar',{
                    'id':this.update,
                    'nombre':this.nombre,
                    'precio':this.precio,
                    'descripcion':this.descripcion,
                    'categoria':this.categoria,
                }).then(function (response) {
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
                    axios.delete('/categorias/borrar/'+task_id
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