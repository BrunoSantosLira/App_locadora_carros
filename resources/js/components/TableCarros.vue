<template>
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">ID do modelo</th>
          <th scope="col">Placa</th>
          <th scope="col">Disponível</th>
          <th scope="col">KM</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c, key in carros" :key="key">
            <td>{{c.id}}</td>
            <td>{{c.modelo_id}}</td>
            <td>{{c.placa}}</td>
            <td>{{c.disponivel == 1 ? 'SIM' : 'NÃO'}}</td>
            <td>{{c.km}}</td>
            <td>
              <button class="btn btn-outline-primary btn-sm m-2" @click="setStore(c)" data-bs-toggle="modal" data-bs-target="#modalCarroAtualizar">Atualizar</button>
              <button class="btn btn-outline-danger btn-sm  m-2" @click="setStore(c)" data-bs-toggle="modal" data-bs-target="#modalCarroRemover">Remover</button>
            </td>
        </tr>
      </tbody>
    </table>
    </template>
    
    <script>
        export default {
            props: ['carros'],
            mounted() {
    
            },
            methods:{
              
              setStore(objeto){
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = objeto
                console.log(this.$store.state.item)
              }
    
            }
    
        }
    </script>
    