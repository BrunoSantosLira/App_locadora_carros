<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">  
            
            <!-- Fim Card busca carros -->
            <card-component titulo="Busca de carros">
                <template v-slot:conteudo>
                     <div class="row">
                         <!-- Busca por ID -->
                        <div class="col mb-3">
                            <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="Informe o ID da marca">                            
                                <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" v-model="busca.id">
                            </input-container-component>
                        </div>

                        <!-- Busca por Placa -->
                        <div class="col mb-3">
                            <input-container-component titulo="Placa" ID="inputPlaca" IDHelp="PlacaHelp" ajuda="Placa do carro">                            
                                <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Placa do carro" v-model="busca.placa">
                            </input-container-component>
                        </div>

                    </div>
                </template>

                <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                </template>
            </card-component>
            <!-- Fim Card busca carros -->

            <!-- Inicio Card Relação de carros -->
            <card-component titulo="Relação de Carros">
                <template v-slot:conteudo>
                     <table-carros-component :carros="carros">

                    </table-carros-component>
                </template>

                <template v-slot:rodape>
                    <paginate-component>
                        <li v-for="l,key in carros.links" :key="key" :class="l.active ? 'page-item active' : 'page-item' " @click="paginate(l)"><a class="page-link"  v-html="l.label"></a></li>
                    </paginate-component>
                        <button data-bs-toggle="modal" data-bs-target="#modalCarro" type="submit" class="btn btn-primary btn-sm float-end">Adicionar</button>
                </template>
            </card-component>
            <!-- Fim Card Relação de carros -->
        </div>
    </div>


<!--  Inicio modal inclusão de carro-->
    <modal-component id="modalCarro" titulo='Adicionar Carro'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Adicionado com sucesso!" :detalhes="transacaoDetalhes" v-if="transacaoStatus == 'adicionado'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao adicionar" :detalhes="transacaoDetalhes"  v-if="transacaoStatus == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <!-- Input de modelo_id-->
            <input-container-component titulo="ID do modelo" ID="inputModeloID" IDHelp="ModeloIDHelp" ajuda="Informe o ID do modelo na qual esse carro pertence">                            
                    <input type="number" class="form-control" id="inputModeloID" aria-describedby="ModeloIDHelp" placeholder="ID" v-model="modeloID">
            </input-container-component>     

            <!-- Input placa-->
            <input-container-component titulo="Placa" ID="inputPlaca" IDHelp="PlacaHelp" ajuda="Informe a placa do carro">                            
                    <input type="text" class="form-control" id="inputPlaca" aria-describedby="PlacaHelp" placeholder="Placa" v-model="placaCarro">
            </input-container-component>   

            <!-- Input de Disponive-->
            <input-container-component titulo="Disponível?" ID="Disponível" IDHelp="DisponívelHelp" ajuda="Informe se o carro está disponível para alocação" class="mt-3">                            
                    <select name="Disponível" id="Disponível" v-model="Disponível" class="form-select">
                        <option value="1">SIM</option><br>
                        <option value="0">NÃO</option><br>
                    </select>
            </input-container-component>      

            <!-- Input de KM-->
            <input-container-component titulo="KM" ID="inputKM" IDHelp="KMHelp" ajuda="Informe a qtde de KM">                            
                    <input type="number" class="form-control" id="inputKM" aria-describedby="KMHelp" v-model="KM">
            </input-container-component>

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
<!--  Fim modal de inclusão de carro-->

<!--  Inicio modal remoção de cliente-->
<modal-component id="modalCarroRemover" titulo='Remover Carro'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Removido com sucesso!" :detalhes="$store.state.transacao.mensagem" v-if="$store.state.transacao.status == 'removido'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao remover" :detalhes="$store.state.transacao.mensagem"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="ID do carro">                            
                <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" :value="$store.state.item.id" disabled >
            </input-container-component>

            <!-- Input de Placa-->
            <input-container-component titulo="Placa" ID="inputPlaca" IDHelp="PlacaHelp" ajuda="Placa do carro">                            
                    <input type="text" class="form-control" id="inputPlaca" aria-describedby="ClienteHelp" placeholder="Placa" :value="$store.state.item.placa" disabled >
            </input-container-component>     

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'removido'">Remover</button>
        </template>
    </modal-component>
<!--  Fim modal de remoção de carro-->


<!--  Inicio modal atualização de carro-->
<modal-component id="modalCarroAtualizar" titulo='Atualizar dados do Carro'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Atualizado com sucesso!" :detalhes="this.$store.state.transacao.mensagem" v-if="this.$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao atualizar" :detalhes="this.$store.state.transacao.mensagem"  v-if="this.$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <!-- Input de modelo_id-->
            <input-container-component titulo="ID do modelo" ID="inputModeloID" IDHelp="ModeloIDHelp" ajuda="Informe o ID do modelo na qual esse carro pertence">                            
                    <input type="number" class="form-control" id="inputModeloID" aria-describedby="ModeloIDHelp" placeholder="ID" v-model="$store.state.item.modelo_id">
            </input-container-component>     

            <!-- Input placa-->
            <input-container-component titulo="Placa" ID="inputPlaca" IDHelp="PlacaHelp" ajuda="Informe a placa do carro">                            
                    <input type="text" class="form-control" id="inputPlaca" aria-describedby="PlacaHelp" placeholder="Placa" v-model="$store.state.item.placa">
            </input-container-component>   

            <!-- Input de Disponive-->
            <input-container-component titulo="Disponível?" ID="Disponível" IDHelp="DisponívelHelp" ajuda="Informe se o carro está disponível para alocação" class="mt-3">                            
                    <select name="Disponível" id="Disponível" v-model="$store.state.item.disponivel" class="form-select">
                        <option value="1">SIM</option><br>
                        <option value="0">NÃO</option><br>
                    </select>
            </input-container-component>      

            <!-- Input de KM-->
            <input-container-component titulo="KM" ID="inputKM" IDHelp="KMHelp" ajuda="Informe a qtde de KM">                            
                    <input type="number" class="form-control" id="inputKM" aria-describedby="KMHelp" v-model="$store.state.item.km">
            </input-container-component>

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">atualizar</button>
        </template>
    </modal-component>
<!--  Fim modal de atualização de carro-->

</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            this.carregarLista()
        },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/carro',
                urlPaginacao : '',
                urlFiltro: '',
                busca: {id : '', placa : ''},
                transacaoStatus: '',
                transacaoDetalhes: '',
                KM:0,
                Disponível: 0,
                modeloID: 0,
                placaCarro: '',
                carros: {detalhes: '', links: ''},
            }
        },
        methods: {
            pesquisar(){
                
                let filtro = ''
                this.busca['placa'] += '%' // na linha(qualquer caractere depois do escrito)
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
                this.busca['placa'] =  this.busca['placa'].split('%').join(''); //Excluir o % da linha
            },
            atualizar(){

                let formData = new FormData()
                formData.append('_method', 'PATCH')
                formData.append('modelo_id', this.$store.state.item.modelo_id)
                formData.append('placa', this.$store.state.item.placa)
                formData.append('disponivel', this.$store.state.item.disponivel)
                formData.append('km', this.$store.state.item.km)

                console.log(this.$store.state.item.id)
                let url = this.urlBase + '/' + this.$store.state.item.id

                let config = {
                headers: {
                    'Accept' : 'application/json',
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
                let confirmacao = confirm('Deseja realmente excluir o registro?')

                if(!confirmacao){
                    return false
                }

                let url = this.urlBase + '/' +  this.$store.state.item.id

                let config = {
                    headers:{
                        'Accept': 'application/json'
                    }
                }

                let formData = new FormData();
                formData.append('_method', 'delete')

                axios.post(url,formData,config)
                    .then(response => {
                        console.log('REMOÇÃO FEITA!')
                        this.$store.state.transacao.status = 'removido'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()
                        console.log(response)
                    })
                    .catch(erro => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                        console.log(erro.response)
                    })


            },
            paginate(objeto){
                if(objeto.url){

                    this.urlPaginacao =  objeto.url.split('?')[1]
                    console.log('URL::',objeto.url)
                    this.carregarLista(); //requisitando uma nova lista
                }
            },
            carregarLista(){
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
                console.log('URL FILTRADA COM PAGE', url)
                let config ={
                    headers: {
                            'Accept': 'application/json',
                    }
                }

                axios.get(url, config)
                .then(response =>{
                   this.carros = response.data.data
                    console.log(response)
                   this.carros.links = response.data.links

                })
                .catch(errors => {
                    console.log(errors);
                })

            },
            salvar(){
                console.log('KM:', this.KM, 'DISPONIVEL?:', this.Disponível, 'MODELO ID:', this.modeloID, 'PLACA:', this.placaCarro)


                 let formData = new FormData();
                formData.append('modelo_id', this.modeloID);
                formData.append('placa', this.placaCarro);
                formData.append('disponivel', this.Disponível);
                formData.append('km', this.KM);

                let config ={
                    headers: {
                            'Content-Type': 'multipart/form-data',
                    }
                }

                axios.post(this.urlBase,formData, config)
                    .then(response => {
                        console.log(response);
                        this.transacaoStatus = 'adicionado';
                        this.transacaoDetalhes = {
                            mensagem: 'Status:' + response.status
                        }
                    })
                    .catch(errors => {
                        console.log(errors)
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: 'Status: ' + errors.response.status,
                            dados: errors.response.data.errors
                        }
                    })

            }
        }
    }
</script>
