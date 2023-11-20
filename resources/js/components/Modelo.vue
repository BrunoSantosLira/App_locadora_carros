<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">  
            
            <!-- Fim Card busca modelo -->
            <card-component titulo="Busca de modelos">
                <template v-slot:conteudo>
                     <div class="row">
                         <!-- Busca por ID -->
                        <div class="col mb-3">
                            <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="Informe o ID da marca">                            
                                <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" v-model="busca.id">
                            </input-container-component>
                        </div>

                        <!-- Busca por nome -->
                        <div class="col mb-3">
                            <input-container-component titulo="Nome" ID="inputNome" IDHelp="NomeHelp" ajuda="Informe o nome da marca">                            
                                <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome da marca" v-model="busca.nome">
                            </input-container-component>
                        </div>

                    </div>
                </template>

                <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                </template>
            </card-component>
            <!-- Fim Card busca modelo -->

            <!-- Inicio Card Relação de modelos -->
            <card-component titulo="Relação de Modelos">
                <template v-slot:conteudo>
                     <table-modelos-component :modelos="modelos">

                    </table-modelos-component>
                </template>

                <template v-slot:rodape>
                    <paginate-component>
                        <li v-for="l,key in modelos.links" :key="key" :class="l.active ? 'page-item active' : 'page-item' " @click="paginate(l)"><a class="page-link"  v-html="l.label"></a></li>
                    </paginate-component>
                        <button data-bs-toggle="modal" data-bs-target="#modalModelo" type="submit" class="btn btn-primary btn-sm float-end">Adicionar</button>
                </template>
            </card-component>
            <!-- Fim Card Relação de modelos -->

        </div>
    </div>

<!--  Inicio modal inclusão de modelo-->
    <modal-component id="modalModelo" titulo='Adicionar Modelo'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Adicionado com sucesso!" :detalhes="transacaoDetalhes" v-if="transacaoStatus == 'adicionado'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao adicionar" :detalhes="transacaoDetalhes"  v-if="transacaoStatus == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <!-- Input de marca_id-->
            <input-container-component titulo="ID da marca" ID="inputMarcaID" IDHelp="MarcaIDHelp" ajuda="Informe o ID da marca na qual esse modelo pertence">                            
                    <input type="number" class="form-control" id="inputMarcaID" aria-describedby="MarcaIDHelp" placeholder="ID" v-model="marcaID">
            </input-container-component>     

            <!-- Input nome-->
            <input-container-component titulo="Nome" ID="inputNome" IDHelp="NomeHelp" ajuda="Informe o nome do modelo">                            
                    <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome" v-model="nomeModelo">
            </input-container-component>   

            <!-- Input de imagem-->
            <input-container-component titulo="Imagem" ID="inputImagem" IDHelp="ImagemHelp" ajuda="Selecione uma imagem para o modelo">                            
                    <input type="file" class="form-control" id="inputImagem" aria-describedby="ImagemHelp" @change="carregarImagem($event)">
            </input-container-component>   

            <!-- Input de NPortas-->
            <input-container-component titulo="Número de Portas" ID="inputNPortas" IDHelp="NPortasHelp" ajuda="Informe o Número de Portas">                            
                    <input type="number" class="form-control" id="inputNPortas" aria-describedby="NPortasHelp" v-model="NPortas">
            </input-container-component>

            <!-- Input de Lugares-->
            <input-container-component titulo="Número de Lugares" ID="inputNLugares" IDHelp="NLugaressHelp" ajuda="Informe o Número de Lugares">                            
                    <input type="number" class="form-control" id="inputNLugares" aria-describedby="LugaresHelp" v-model="NLugares">
            </input-container-component>

            <!-- Input de AirBag-->
            <input-container-component titulo="AirBag" ID="inputAirBag" IDHelp="AirBagHelp" ajuda="Informe se o modelo possui AirBag" class="mt-3">                            
                    <select name="AirBag" id="AirBag" v-model="AirBag" class="form-select">
                        <option value="1">SIM</option><br>
                        <option value="0">NÃO</option><br>
                    </select>
            </input-container-component>      

            <!-- Input de abs-->
            <input-container-component titulo="ABS" ID="inputABS" IDHelp="ABSHelp" ajuda="Informe se o modelo possui ABS">                            
                    <select name="ABS" id="ABS" v-model="ABS" class="form-select">
                        <option value="1">SIM</option><br>
                        <option value="0">NÃO</option><br>
                    </select>
            </input-container-component>     

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
<!--  Fim modal de inclusão de modelo-->

<!--  Inicio modal remoção de cliente-->
    <modal-component id="modalModeloRemover" titulo='Remover Modelo'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Removido com sucesso!" :detalhes="$store.state.transacao.mensagem" v-if="$store.state.transacao.status == 'removido'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao remover" :detalhes="$store.state.transacao.mensagem"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="ID do Modelo">                            
                <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" :value="$store.state.item.id" disabled >
            </input-container-component>

            <!-- Input de Modelo-->
            <input-container-component titulo="Modelo" ID="inputModelo" IDHelp="ModeloHelp" ajuda="Nome do modelo">                            
                    <input type="text" class="form-control" id="inputCliente" aria-describedby="ModeloHelp" placeholder="Nome" :value="$store.state.item.nome" disabled >
            </input-container-component>     

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'removido'">Remover</button>
        </template>
    </modal-component>
<!--  Fim modal de remoção de modelos-->


<!--  Inicio modal atualização de modelo-->
    <modal-component id="modalModeloAtualizar" titulo='Atualizar Modelo'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Atualizado com sucesso!" :detalhes="this.$store.state.transacao.mensagem" v-if="this.$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao atualizar" :detalhes="this.$store.state.transacao.mensagem"  v-if="this.$store.state.transacao.status== 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <!-- Input de marca_id-->
            <input-container-component titulo="ID da marca" ID="inputMarcaID" IDHelp="MarcaIDHelp" ajuda="Informe o ID da marca na qual esse modelo pertence">                            
                    <input type="number" class="form-control" id="inputMarcaID" aria-describedby="MarcaIDHelp" placeholder="ID"  v-model="$store.state.item.marca_id">
            </input-container-component>     

            <!-- Input nome-->
            <input-container-component titulo="Nome" ID="inputNome" IDHelp="NomeHelp" ajuda="Informe o nome do modelo">                            
                    <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome"  v-model="$store.state.item.nome">
            </input-container-component>   

            <!-- Input de imagem-->
            <input-container-component titulo="Imagem" ID="inputImagem" IDHelp="ImagemHelp" ajuda="Selecione uma imagem para o modelo">                            
                    <input type="file" class="form-control" id="inputImagem" aria-describedby="ImagemHelp" @change="carregarImagem($event)">
            </input-container-component>   

            <!-- Input de NPortas-->
            <input-container-component titulo="Número de Portas" ID="inputNPortas" IDHelp="NPortasHelp" ajuda="Informe o Número de Portas">                            
                    <input type="number" class="form-control" id="inputNPortas" aria-describedby="NPortasHelp"  v-model="$store.state.item.numero_portas">
            </input-container-component>

            <!-- Input de Lugares-->
            <input-container-component titulo="Número de Lugares" ID="inputNLugares" IDHelp="NLugaressHelp" ajuda="Informe o Número de Lugares">                            
                    <input type="number" class="form-control" id="inputNLugares" aria-describedby="LugaresHelp"  v-model="$store.state.item.lugares">
            </input-container-component>

            <!-- Input de AirBag-->
            <input-container-component titulo="AirBag" ID="inputAirBag" IDHelp="AirBagHelp" ajuda="Informe se o modelo possui AirBag" class="mt-3">                            
                    <select name="AirBag" id="AirBag"  v-model="$store.state.item.air_bag" class="form-select" >
                        <option value="1">SIM</option><br>
                        <option value="0">NÃO</option><br>
                    </select>
            </input-container-component>      

            <!-- Input de abs-->
            <input-container-component titulo="ABS" ID="inputABS" IDHelp="ABSHelp" ajuda="Informe se o modelo possui ABS">                            
                    <select name="ABS" id="ABS" v-model="$store.state.item.abs" class="form-select">
                        <option value="1">SIM</option><br>
                        <option value="0">NÃO</option><br>
                    </select>
            </input-container-component>     

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
        </template>
    </modal-component>
<!--  Fim modal de atualização de modelo-->

</div>
</template>

<script>
    export default {
        mounted() {
            this.carregarLista()
        },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlPaginacao : '',
                urlFiltro: '',
                ABS: 0,
                AirBag: 0,
                NPortas: 0,
                NLugares: 0,
                marcaID: 0,
                nomeModelo: '',
                transacaoDetalhes: '',
                transacaoStatus: '',
                modelos: {detalhes: '', links: ''},
                arquivoImagem: [],
                busca: {id : '', nome : ''}
            }
        },
        methods:{
            atualizar(){

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('marca_id', this.$store.state.item.marca_id)
                formData.append('nome', this.$store.state.item.nome)
                formData.append('numero_portas', this.$store.state.item.numero_portas)
                formData.append('lugares', this.$store.state.item.lugares)
                formData.append('air_bag', this.$store.state.item.air_bag)
                formData.append('abs', this.$store.state.item.abs)

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
            carregarImagem(e){
                this.arquivoImagem = e.target.files
            },
             pesquisar(){
         
                let filtro = ''
                this.busca['nome'] += '%' // na linha(qualquer caractere depois do escrito)
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
                this.busca['nome'] =  this.busca['nome'].split('%').join(''); //Excluir o % da linha
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
                   this.modelos = response.data.data
                
                   this.modelos.links = response.data.links

                })
                .catch(errors => {
                    console.log(errors);
                })

            },
            salvar(){
                console.log('ABS:', this.ABS, ' AIRBAG:', this.AirBag, ' NPORTAS:', this.NPortas, ' MARCAID', this.marcaID, 'nomeModelo', this.nomeModelo, 'imagem:', this.arquivoImagem, ' NLUGARES:', this.NLugares);

                let formData = new FormData();
                formData.append('marca_id', this.marcaID);
                formData.append('nome', this.nomeModelo);
                formData.append('imagem', this.arquivoImagem[0]);
                formData.append('numero_portas', this.NPortas);
                formData.append('lugares', this.NLugares);
                formData.append('air_bag', this.AirBag);
                formData.append('abs', this.ABS);

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
                        this.carregarLista();
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
