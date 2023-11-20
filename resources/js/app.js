/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

//config do vuex


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import Login from './components/Login.vue';
app.component('login-component', Login);

import Home from './components/Home.vue';
app.component('home-component', Home);

import Marcas from './components/Marcas.vue';
app.component('marca-component', Marcas);

import InputContainer from './components/InputContainer.vue';
app.component('input-container-component', InputContainer);

import Table from './components/Table.vue';
app.component('table-component', Table);



import TableClientes from './components/TableClientes.vue';
app.component('table-clientes-component', TableClientes);

import TableModelos from './components/TableModelos.vue';
app.component('table-modelos-component', TableModelos);

import TableCarros from './components/TableCarros.vue';
app.component('table-carros-component', TableCarros);


import Card from './components/Card.vue';
app.component('card-component', Card);

import Modal from './components/Modal.vue';
app.component('modal-component', Modal);

import Alert from './components/Alert.vue';
app.component('alert-component', Alert);

import Paginate from './components/Paginate.vue';
app.component('paginate-component', Paginate);


import Cliente from './components/Cliente.vue';
app.component('cliente-component', Cliente);

import Modelo from './components/Modelo.vue';
app.component('modelo-component', Modelo);

import Carro from './components/Carro.vue';
app.component('carro-component', Carro);

import Locações from './components/Locações.vue';
app.component('locacoes-component', Locações);


//Cria propriedade GlOBAL nos componentes VUEs
app.config.globalProperties.$filters = {
    formataDataTempo(d) {
      if (!d) return "";
      
      d = d.split("T")
      let data = d[0]
      data = data.split("-")
      data = data[2] + "/" + data[1] + "/" + data[0];
   
      let tempo = d[1]  
      tempo = tempo.split('.')
      tempo = tempo[0]     
   
      return data + ' ' + tempo
    },
  }

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

import { createStore } from 'vuex'
const store = createStore({
     state: {
         item: {},
         transacao: {status:'', mensagem: '', dados: ''}
    } 
}) 
app.use(store)

app.mount('#app');
