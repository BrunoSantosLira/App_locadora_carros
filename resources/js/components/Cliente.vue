<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">  
            
            <!-- Fim Card busca cliente -->
            <card-component titulo="Busca de clientes">
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
                                <input type="text" class="form-control" id="inputNome" aria-describedby="NomeHelp" placeholder="Nome da marca" v-model="busca.nome ">
                            </input-container-component>
                        </div>

                    </div>
                </template>

                <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                </template>
            </card-component>
            <!-- Fim Card busca cliente -->

            <!-- Inicio Card Relação de clientes -->
            <card-component titulo="Relação de clientes">
                <template v-slot:conteudo>
                     <table-clientes-component :clientes="clientes">

                    </table-clientes-component>
                </template>

                <template v-slot:rodape>
                    <paginate-component>
                        <li v-for="l,key in clientes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item' " @click="paginate(l)"><a class="page-link"  v-html="l.label"></a></li>
                    </paginate-component>
                        <button data-bs-toggle="modal" data-bs-target="#modalCliente" type="submit" class="btn btn-primary btn-sm float-end">Adicionar</button>
                </template>
            </card-component>
            <!-- Fim Card Relação de clientes -->

        </div>
    </div>


    
<!--  Inicio modal inclusão de cliente-->
    <modal-component id="modalCliente" titulo='Adicionar Cliente'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Adicionado com sucesso!" :detalhes="transacaoDetalhes" v-if="transacaoStatus == 'adicionado'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao adicionar" :detalhes="transacaoDetalhes"  v-if="transacaoStatus == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <!-- Input de nome-->
            <input-container-component titulo="Cliente" ID="inputCliente" IDHelp="clienteHelp" ajuda="Informe o nome do cliente">                            
                    <input type="text" class="form-control" id="inputCliente" aria-describedby="ClienteHelp" placeholder="Nome" v-model="nomeCliente">
            </input-container-component>     

            <!-- Input de CPF-->
            <input-container-component titulo="CPF" ID="inputCPF" IDHelp="CPFHelp" ajuda="Informe o CPF do cliente(11 digitos)">                            
                    <input type="text" class="form-control" id="inputClienteCPF" aria-describedby="ClienteCPFHelp" placeholder="CPF" v-model="CPFCliente">
            </input-container-component>   

            <!-- Input de endereço-->
            <input-container-component titulo="Endereço" ID="inputEndereço" IDHelp="EndereçoHelp" ajuda="Informe o Endereço do cliente">                            
                    <input type="text" class="form-control" id="inputClienteEndereço" aria-describedby="ClienteEndereçoHelp" placeholder="Rua, número, Bairro, cidade e UF" v-model="endereçoCliente">
            </input-container-component>   

            <!-- Input de email-->
            <input-container-component titulo="Email" ID="inputEmail" IDHelp="EmailHelp" ajuda="Informe o Email do cliente">                            
                    <input type="email" class="form-control" id="inputClienteEmail" aria-describedby="ClienteEndereçoHelp" placeholder="exemplo@exemplo.com" v-model="emailCliente">
            </input-container-component>

            <!-- Input de telefone-->
            <input-container-component titulo="Telefone" ID="inputTelefone" IDHelp="TelefoneHelp" ajuda="Informe o Telefone do cliente">                            
                    <input type="tel" class="form-control" id="inputClienteTelefone" aria-describedby="ClienteTelefoneHelp" placeholder="Telefone" v-model="telefoneCliente">
            </input-container-component>      

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
<!--  Fim modal de inclusão de cliente-->


<!--  Inicio modal remoção de cliente-->
    <modal-component id="modalClienteRemover" titulo='Remover Cliente'>
        <template v-slot:alerts>
            <alert-component tipo='success' titulo="Removido com sucesso!" :detalhes="$store.state.transacao.mensagem" v-if="$store.state.transacao.status == 'removido'"></alert-component>
            <alert-component tipo='danger' titulo="Erro ao remover" :detalhes="$store.state.transacao.mensagem"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="Informe o ID da marca">                            
                <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" :value="$store.state.item.id" disabled >
            </input-container-component>

            <!-- Input de nome-->
            <input-container-component titulo="Cliente" ID="inputCliente" IDHelp="clienteHelp" ajuda="Informe o nome do cliente">                            
                    <input type="text" class="form-control" id="inputCliente" aria-describedby="ClienteHelp" placeholder="Nome" :value="$store.state.item.nome" disabled >
            </input-container-component>     

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
        </template>
    </modal-component>
<!--  Fim modal de remoção de cliente-->


<!--  Inicio modal atualização de cliente-->
    <modal-component id="modalClienteAtualizar" titulo='Atualizar dados do Cliente'>
        <template v-slot:alerts>
            <alert-component tipo="success" titulo="Sucesso na atualização da marca!" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
            <alert-component tipo="danger" titulo="Erro na atualização" :detalhes="$store.state.transacao"  v-if="$store.state.transacao.status == 'erro'"></alert-component>
        </template>
        <template v-slot:conteudo>
            <!-- Input de nome-->
            <input-container-component titulo="Cliente" ID="inputCliente" IDHelp="clienteHelp" ajuda="Informe o nome do cliente">                            
                    <input type="text" class="form-control" id="inputCliente" aria-describedby="ClienteHelp" placeholder="Nome" v-model="$store.state.item.nome">
            </input-container-component>     

            <!-- Input de CPF-->
            <input-container-component titulo="CPF" ID="inputCPF" IDHelp="CPFHelp" ajuda="Informe o CPF do cliente(11 digitos)">                            
                    <input type="text" class="form-control" id="inputClienteCPF" aria-describedby="ClienteCPFHelp" placeholder="CPF" v-model="$store.state.item.CPF">
            </input-container-component>   

            <!-- Input de endereço-->
            <input-container-component titulo="Endereço" ID="inputEndereço" IDHelp="EndereçoHelp" ajuda="Informe o Endereço do cliente">                            
                    <input type="text" class="form-control" id="inputClienteEndereço" aria-describedby="ClienteEndereçoHelp" placeholder="Rua, número, Bairro, cidade e UF" v-model="$store.state.item.endereço">
            </input-container-component>   

            <!-- Input de email-->
            <input-container-component titulo="Email" ID="inputEmail" IDHelp="EmailHelp" ajuda="Informe o Email do cliente">                            
                    <input type="email" class="form-control" id="inputClienteEmail" aria-describedby="ClienteEndereçoHelp" placeholder="exemplo@exemplo.com" v-model="$store.state.item.email">
            </input-container-component>

            <!-- Input de telefone-->
            <input-container-component titulo="Telefone" ID="inputTelefone" IDHelp="TelefoneHelp" ajuda="Informe o Telefone do cliente">                            
                    <input type="tel" class="form-control" id="inputClienteTelefone" aria-describedby="ClienteTelefoneHelp" placeholder="Telefone" v-model="$store.state.item.telefone">
            </input-container-component>      

        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Salvar</button>
        </template>
    </modal-component>
<!--  Fim modal de atualização de cliente-->
</div>
</template>

<script>
    export default {
        mounted() { //metodo executado ao carregar a página
            this.carregarLista();
        },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/cliente',
                urlFiltro: '',
                urlPaginacao: '',
                transacaoDetalhes : [],
                transacaoStatus : '',
                nomeCliente: '',
                CPFCliente: '',
                endereçoCliente:'',
                telefoneCliente:'',
                emailCliente:'',
                clientes: {detalhes: '', links: ''},
                busca: {id : '', nome : ''}
            }
        },
        methods:{
            atualizar(){
                let formData = new FormData()
                formData.append('_method', 'PATCH')
                formData.append('nome', this.$store.state.item.nome.toUpperCase())
                formData.append('CPF', this.$store.state.item.CPF)
                formData.append('endereço', this.$store.state.item.endereço)
                formData.append('email', this.$store.state.item.email)
                formData.append('telefone', this.$store.state.item.telefone)

                let url = this.urlBase + '/' + this.$store.state.item.id
                let config = {
                    headers: {
                        'Accept' : 'application/json',
                    }
                }
                console.log(this.$store.state.item.nome)
                
                axios.post(url, formData, config)
                    .then(response => {
                        console.log(response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Registro atualizado com sucesso!'
                        console.log('RESPOSTA:', response)
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
                        this.$store.state.transacao.status = 'sucesso'
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
                   this.clientes = response.data.data
                
                   this.clientes.links = response.data.links

                })
                .catch(errors => {
                    console.log(errors);
                })

            },
            salvar(){

                let formData = new FormData()
                formData.append('nome',  this.nomeCliente.toUpperCase());
                formData.append('CPF', this.CPFCliente);
                formData.append('endereço', this.endereçoCliente);
                formData.append('email', this.emailCliente);
                formData.append('telefone', this.telefoneCliente);

                let config = {
                    headers: {
                        'Accept': 'application/json'
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado';
                        this.transacaoDetalhes = {
                            mensagem: 'Status de sucesso:' + response.status
                        }
                        this.carregarLista();
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro';
                        this.transacaoDetalhes = {
                            mensagem: 'Status:' + errors.response.data.message
                        }
                        console.log('ERRO DA RESPOSTA:', errors.response.data);
                    })

            }

        }


    }
</script>
