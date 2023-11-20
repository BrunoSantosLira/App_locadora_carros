<template>
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Marca ID</th>
      <th scope="col">Nome</th>
      <th scope="col">Imagem</th>
      <th scope="col">Número de portas</th>
      <th scope="col">Lugares</th>
      <th scope="col">Air bag</th>
      <th scope="col">ABS</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="m, key in modelos" :key="key">
        <td>{{m.id}}</td>
        <td>{{m.marca_id}}</td>
        <td>{{m.nome}}</td>
        <td v-if="m"><img :src=" '/storage/'+m.imagem" alt="" srcset="" width="30" height="30"></td>
        <td>{{m.numero_portas}}</td>
        <td>{{m.lugares}}</td>
        <td>{{m.air_bag == 1 ? 'SIM' : 'NÃO'}}</td>
        <td>{{m.abs == 1 ? 'SIM' : 'NÃO'}}</td>
        <td>
          <button class="btn btn-outline-primary btn-sm m-2" @click="setStore(m)" data-bs-toggle="modal" data-bs-target="#modalModeloAtualizar">Atualizar</button>
          <button class="btn btn-outline-danger btn-sm  m-2" @click="setStore(m)" data-bs-toggle="modal" data-bs-target="#modalModeloRemover">Remover</button>
        </td>
    </tr>
  </tbody>
</table>
</template>

<script>
    export default {
        props: ['modelos'],
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
