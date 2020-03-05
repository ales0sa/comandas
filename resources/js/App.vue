<template>
  <div id="app">
    <CategoriasComponent />
    <ProductosComponent />
  </div>
</template>
<script>
import ProductosComponent from './components/ProductosComponent.vue';
import CategoriasComponent from './components/CategoriasComponent.vue';
export default {
  name: 'app',
  components: {
    CategoriasComponent, ProductosComponent
  },
  data (){
    return {
      
    }
  }
}
</script>
<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;

}
</style>