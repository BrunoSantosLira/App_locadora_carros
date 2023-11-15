<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <card-component titulo="Busca de Marcas">
                <template v-slot:conteudo>
                     <div class="row">
                        <div class="col mb-3 ">
                            <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="Informe o ID da marca">                            
                                <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" v-model='busca.id'>
                            </input-container-component>

                        </div>

                        <div class="col mb-3">

                            <input-container-component titulo="Nome" ID="inputNome" IDHelp="NomeHelp" ajuda="Informe o nome da marca">                            
                                <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome da marca" v-model='busca.nome'>
                            </input-container-component>

                        </div>
                    </div>
                </template>

                <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                </template>

            </card-component>

            <card-component titulo="Relação de Marcas">
                <template v-slot:conteudo> 
                       <table-component :dados="marcas.data" :titulos="{
                            id: {titulo: 'ID', tipo: 'text'},
                            nome: {titulo: 'Nome', tipo: 'text'},
                            imagem: {titulo: 'Imagem', tipo: 'file'},
                            created_at: {titulo: 'Data de criação', tipo: 'data'},
                       }" :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget:'#modalMarcaVisualizar'}"
                        :atualizar="{ visivel: true, dataToggle: 'modal', dataTarget:'#modalMarcaAtualizar'}"
                        :remover="{ visivel: true, dataToggle: 'modal', dataTarget:'#modalMarcaRemover'}">
                       
                       </table-component>
                </template>

                <template v-slot:rodape> 
                    <paginate-component>
                        <li v-for="l,key in marcas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item' " @click="paginate(l)"><a class="page-link"  v-html="l.label"></a></li>
                    </paginate-component>
                     <button data-bs-toggle="modal" data-bs-target="#modalMarca" type="submit" class="btn btn-primary btn-sm float-end">Adicionar</button>
                </template>
            </card-component>

        </div>
    </div>

<!--  Inicio modal inclusão de marca-->
    <modal-component id="modalMarca" titulo='Adicionar marca'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Adicionado com sucesso!" :detalhes="transacaoDetalhes" v-if="transacaoStatus == 'adicionado'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao adicionar" :detalhes="transacaoDetalhes"  v-if="transacaoStatus == 'erro'"></alert-component>
        </template>


        <template v-slot:conteudo>
            <input-container-component titulo="marca" ID="inputMarca" IDHelp="marcaHelp" ajuda="Informe o nome da marca">                            
                    <input type="text" class="form-control" id="inputMarca" aria-describedby="marcaHelp" placeholder="Marca" v-model='nomeMarca'>
            </input-container-component>   
            {{nomeMarca}}   
            <input-container-component titulo="Imagem" ID="novaImagem" IDHelp="ImagemHelp" ajuda="Insira a image no formato PNG">                            
                    <input type="file" class="form-control" id="novaImagem" aria-describedby="ImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
            </input-container-component>
            {{arquivoImagem}}   
        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
<!--  Fim modal de inclusão de marca-->

<!--  Inicio modal visualização de marca-->
    <modal-component id="modalMarcaVisualizar" titulo='Visualizar marca'>
        <template v-slot:alerts></template>
        <template v-slot:conteudo>
            <input-container-component titulo="ID" >
                <input type="text" class="form-control" :value="$store.state.item.id" disabled >
            </input-container-component>

            <input-container-component titulo="Nome">
                <input type="text" class="form-control" :value="$store.state.item.nome" disabled >
            </input-container-component>

            
            <input-container-component titulo="Logo:">
                <br>
                <img :src="'storage/' + $store.state.item.imagem" alt="" srcset="" v-if="$store.state.item.imagem"> 
            </input-container-component>

            
            <input-container-component titulo="Data de criação">
                <input type="text" class="form-control" :value="$store.state.item.created_at" disabled >
            </input-container-component>

        </template>

        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </template>

    </modal-component>
<!--  Fim modal visualização de marca-->

<!--  Inicio modal remoção de marca-->
    <modal-component id="modalMarcaRemover" titulo='Remover marca'>
        <template v-slot:alerts>
            <alert-component tipo="success" titulo="Sucesso na remoção da marca!" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo="danger" titulo="Erro na remoção" :detalhes="$store.state.transacao"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>

        <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
            <input-container-component titulo="ID" >
                <input type="text" class="form-control" :value="$store.state.item.id" disabled >
            </input-container-component>

            <input-container-component titulo="Nome">
                <input type="text" class="form-control" :value="$store.state.item.nome" disabled >
            </input-container-component>
        </template>

        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger"  @click='remover()' v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
        </template>

    </modal-component>
<!--  Fim modal remoção de marca-->

<!--  Inicio modal atualização de marca-->
    <modal-component id="modalMarcaAtualizar" titulo='Atualizar marca'>
        <template v-slot:alerts>
              <alert-component tipo="success" titulo="Sucesso na atualização da marca!" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo="danger" titulo="Erro na atualização" :detalhes="$store.state.transacao"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>


        <template v-slot:conteudo>
            <input-container-component titulo="Novo nome da marca" ID="novoNome" IDHelp="marcaHelp" ajuda="Informe o novo nome da marca">                            
                    <input type="text" class="form-control" id="novoNome" aria-describedby="marcaHelp" placeholder="Novo nome"  v-model="$store.state.item.nome">
            </input-container-component>     
            <input-container-component titulo="Nova imagem da marca" ID="atualizarImagem" IDHelp="ImagemHelp" ajuda="Insira uma nova imagem no formato PNG">                            
                    <input type="file" class="form-control" id="atualizarImagem" aria-describedby="ImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
            </input-container-component>
        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
        </template>
    </modal-component>
<!--  Fim modal de atualização de marca-->

</div>
</template>

<script>
import InputContainer from './InputContainer.vue'
    export default {
  components: { InputContainer },
        computed:{
            token() {
                let token = document.cookie.split(';').find(indice =>{
                    return indice.includes('token=')
                })

                token = token.split('=')[1]
                token = 'Bearer ' + token
                return token
            }
        },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: [],
                marcas: {data:[]},
                busca: {id:'', nome:''}
            }
        },
        methods:{
            atualizar(){
                console.log(this.$store.state.item.nome)
                console.log(this.arquivoImagem)

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)

                if(this.arquivoImagem[0]){
                    formData.append('imagem', this.arquivoImagem[0])
                }

                console.log(this.$store.state.item.id)
                let url = this.urlBase + '/' + this.$store.state.item.id

                let config = {
                    headers: {
                        'Content-Type' : 'multipart/form-data',
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        console.log(response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Registro atualizado com sucesso!'
                        this.carregarLista();
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                        console.log('ERRO DE ATT:',errors.response)
                    })
            },
            remover(){
                let confirmacao = confirm('Deseja realmente remover esse registro?') 

                if(!confirmacao){
                    return false
                }

                let url = this.urlBase + '/' + this.$store.state.item.id

                let formData = new FormData();
                formData.append('_method', 'delete')

                let config = {
                    headers: {
                        'Accept' : 'application/json',
                    }
                }
      
                axios.post(url, formData, config)
                    .then(response => {
                        console.log('Remocao feita!', response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista();
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                        console.log('Houve um erro na requisição', errors.response)
                    })
                

            },

            pesquisar(){
         
                let filtro = ''
                for(let chave in this.busca){
                    //console.log(chave)
                    if(this.busca[chave]){
                        if(filtro != ''){
                            filtro += ';'
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }

                }
                if(filtro != ''){
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro=' + filtro
                }else{
                    this.urlFiltro = ''
                }
                this.carregarLista()

            },
            paginate(objeto){
                if(objeto.url){

        
                    this.urlPaginacao =  objeto.url.split('?')[1]
                      
                    this.carregarLista(); //requisitando uma nova lista
                }
            },
            carregarLista(){

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                console.log('URL CARREGAR:', url);
                let config ={
                    headers: {
                            'Content-Type': 'multipart/form-data',
                    }
                }

                axios.get(url, config)
                .then(response =>{
                    this.marcas = response.data
          
  
                })
                .catch(errors => {
                    console.log(errors);
                })
            },

            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
            salvar(){
                let formData= new FormData();
                formData.append('nome', this.nomeMarca);
                formData.append('imagem', this.arquivoImagem[0]);

                let configuracoes = {
                    method: 'post',
                    body: new URLSearchParams({
                        "nome": this.nomeMarca,
                        "imagem": this.arquivoImagem[0]
                    }),
                     headers: {
                            'Content-Type': 'multipart/form-data',
                    }
                }

                let config ={
                    headers: {
                            'Content-Type': 'multipart/form-data',
                    }
                }

                axios.post(this.urlBase,formData,config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado';
                        this.transacaoDetalhes = {
                            mensagem: 'Status:' + response.status
                        }
                        console.log(response)
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro';
                        this.transacaoDetalhes = {
                            mensagem: 'Status: ' + errors.response.status,
                            dados: errors.response.data.errors
                        }
                       
                    })
                    this.carregarLista()
            }
        },
        mounted(){
            this.carregarLista()
        }
    }
</script>
