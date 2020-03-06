<template>
  <div id="app">
        <div class="vld-parent">
        <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>

    <CategoriasComponent />
    <ProductosComponent />

        </div>
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