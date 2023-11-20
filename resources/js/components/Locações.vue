<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">  
                
                <!-- Fim Card busca Locações -->
                <card-component titulo="Busca de carros">
                    <template v-slot:conteudo>
                         <div class="row">
                             <!-- Busca por ID -->
                            <div class="col mb-3">
                                <input-container-component titulo="ID" ID="inputID" IDHelp="IDHelp" ajuda="Informe o ID da marca">                            
                                    <input type="number" class="form-control" id="inputID" aria-describedby="IDHelp" placeholder="ID" >
                                </input-container-component>
                            </div>
                        </div>
                    </template>
    
                    <template v-slot:rodape>
                            <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Fim Card busca carros -->
    
                <!-- Inicio Card Relação de carros -->
                <card-component titulo="Relação de Carros">
                    <template v-slot:conteudo>
                         <table-carros-component >
    
                        </table-carros-component>
                    </template>
    
                    <template v-slot:rodape>
                        <paginate-component>
                        </paginate-component>
                            <button data-bs-toggle="modal" data-bs-target="#modalLocação" type="submit" class="btn btn-primary btn-sm float-end">Adicionar</button>
                    </template>
                </card-component>
                <!-- Fim Card Relação de carros -->
            </div>
        </div>

<!--  Inicio modal inclusão de Locações-->
    <modal-component id="modalLocação" titulo='Adicionar Locação'>
        <template v-slot:alerts>
        </template>
        <template v-slot:conteudo>
            <div class="row">

            <div class="col mb-3">
                <!-- Input de cliente_id-->
                <input-container-component titulo="ID do Cliente" ID="inputcliente_id" IDHelp="cliente_idHelp" ajuda="Informe o ID do cliente na qual é essa locação">                            
                        <input type="number" class="form-control" id="inputcliente_id" aria-describedby="cliente_idHelp" placeholder="ID do cliente" v-model="cliente_id">
                </input-container-component>     
            </div>

            <div class="col mb-3">
                <!-- Input de carro_id-->
                <input-container-component titulo="ID do carro" ID="inputIDcarro" IDHelp="IDCarroHelp" ajuda="Informe o ID do qual na qual será alocado">                            
                        <input type="number" class="form-control" id="IDCarrotKM" aria-describedby="IDCarroHelp" v-model="carro_id">
                </input-container-component>
            </div>

            
            <!-- Input do valor da diária-->
            <input-container-component titulo="Valor da diária" ID="inputIDdiaria" IDHelp="diáriaHelp" ajuda="Informe o valor da diária">                            
                    <input type="number" class="form-control" id="diária" aria-describedby="diária" v-model="valor_diaria">
            </input-container-component>

            <!-- Input do valor do KM_INICIAL-->
            <input-container-component titulo="Km inicial" ID="inputKMINICIAL" IDHelp="KMINICIALHelp" ajuda="Informe o KM inicial">                            
                    <input type="number" class="form-control" id="KMINICIAL" aria-describedby="KMINICIAL" v-model="km_inicial">
            </input-container-component>

            <!-- Input do valor do KM_Final-->
            <input-container-component titulo="Km Final" ID="inputKMFina" IDHelp="KMFinaHelp" ajuda="Informe o KM Fina">                            
                    <input type="number" class="form-control" id="KMFinaL" aria-describedby="KMFina" v-model="km_final">
            </input-container-component>

            <div class="col mb-3">
            <!-- Input dq data do inicio da alocação-->
            <input-container-component titulo="Data de inicío" ID="inputDATAINICIO" IDHelp="DATAINICIOHelp" ajuda="Informe a data de iniício da alocação">                            
                    <input type="date" class="form-control" id="DATAINICIO" aria-describedby="DATAINICIO" v-model="data_inicio_periodo">
            </input-container-component>
            </div>

            <div class="col mb-3">
                <!-- Input da data final prevista-->
                <input-container-component titulo="Data do fim prevista" ID="inputDATAFIMPREVISTA" IDHelp="DATAFIMPREVISTAHelp" ajuda="Informe a data do fim prevista">                            
                    <input type="date" class="form-control" id="DATAFIMPREVISTA" aria-describedby="DATAFIMPREVISTA" v-model="data_final_previsto_periodo">
                </input-container-component>
            </div>

            <div class="col mb-3">
                <!-- Input da data final realizada-->
                <input-container-component titulo="Data do fim realizada" ID="inputDATAFIMrealizada" IDHelp="DATAFrealizadaHelp" ajuda="Informe a data do fim realizada">                            
                    <input type="date" class="form-control" id="DATAFIMrealizada" aria-describedby="DATAFIMrealizada" v-model="data_final_realizado_periodo">
                </input-container-component>
            </div>
        </div>
        </template>
      
        <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
<!--  Fim modal de inclusão de Locações-->
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data(){
            return {
                urlBase: 'http://localhost:8000/api/v1/locacao',
                urlPaginacao : '',
                urlFiltro: '',
                busca: {id : ''},
                transacaoStatus: '',
                transacaoDetalhes: {},
                cliente_id:0,
                carro_id: 0,
                data_inicio_periodo: '',
                data_final_previsto_periodo: '',
                data_final_realizado_periodo: '',
                valor_diaria: 0,
                km_inicial: 0,
                km_final: 0,
                locacoes: {detalhes: '', links: ''},
            }
        },
        methods: {
            salvar(){
                console.log('cliente_id:', this.cliente_id, 'carro_id?:', this.carro_id, 'data_inicio_periodo :', this.data_inicio_periodo, 'data_final_previsto_periodo:', this.data_final_previsto_periodo)


                 let formData = new FormData();
                formData.append('cliente_id', this.cliente_id);
                formData.append('carro_id', this.carro_id);
                formData.append('data_inicio_periodo', this.data_inicio_periodo);
                formData.append('data_final_previsto_periodo', this.data_final_previsto_periodo);
                formData.append('data_final_realizado_periodo', this.data_final_realizado_periodo);
                formData.append('valor_diaria', this.valor_diaria);
                formData.append('km_inicial', this.km_inicial);
                formData.append('km_final', this.km_final);

                let config ={
                    headers: {
                            'Accept': 'application/json',
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
                        console.log(errors.response.data)
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
