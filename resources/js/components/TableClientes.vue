<template>
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Nome</th>
      <th scope="col">Email</th>
      <th scope="col">Telefone</th>
      <th scope="col">Endereço</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="c, key in clientes" :key="key">
        <td>{{c.id}}</td>
        <td>{{c.nome}}</td>
        <td>{{c.email}}</td>
        <td>{{c.telefone}}</td>
        <td>{{c.endereço}}</td>
        <td>
          <button class="btn btn-outline-primary btn-sm m-2" @click="setStore(c)" data-bs-toggle="modal" data-bs-target="#modalClienteAtualizar">Atualizar</button>
          <button class="btn btn-outline-danger btn-sm  m-2" @click="setStore(c)" data-bs-toggle="modal" data-bs-target="#modalClienteRemover">Remover</button>
        </td>
    </tr>
  </tbody>
</table>
</template>

<script>
    export default {
        props: ['clientes'],
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
