<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key" class="text-uppercase">{{t.titulo}}</th>
                    <th v-if="atualizar.visivel || remover.visivel || visualizar.visivel"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{valor}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{formataDataTempo(valor)}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'file'"><img :src="'/storage/'+valor" width="30" height="30"></span>
                    </td>
                    <td v-if="atualizar.visivel || remover.visivel || visualizar.visivel">
                        <button v-if='visualizar.visivel' class="btn btn-outline-primary btn-sm  m-2" :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if='atualizar.visivel' class="btn btn-outline-primary btn-sm  m-2"  :data-bs-toggle="atualizar.dataToggle" :data-bs-target="atualizar.dataTarget" @click="setStore(obj)">Atualizar</button>
                        <button v-if='remover.visivel' class="btn btn-outline-danger  btn-sm  m-2" :data-bs-toggle="remover.dataToggle" :data-bs-target="remover.dataTarget" @click="setStore(obj)" >Remover</button>
                    </td>
                </tr>
                <!--
                <tr v-for="obj in dados" :key="obj.id">
                    <td  v-for="valor, chave in obj" :key="chave">
                        <p v-if="titulos.includes(chave)">
                            <span v-if="chave == 'imagem'">
                                <img :src="'/storage/'+valor" width="30" height="30">
                            </span>
                            <span v-else>
                                {{ valor }}
                            </span>
                        </p>
                    </td>
                    
    
                    <th scope="row">{{m.id}}</th>
                    <td>{{m.nome}}</td>
                    <td><img :src="'/storage/'+m.imagem" width="30" height="30"></td>
                </tr>
                -->
            </tbody>
        </table>
    </div>
</template>
 
<script>
    export default {
        props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
        methods:{
            formataDataTempo(d){

                if(!d) return ''

                d = d.split('T')

                let data = d[0];
                let tempo = d[1];

                data = data.split('-')
                console.log(data)
                data = data[2] + '/' + data[1] + '/' + data[0]

                tempo = tempo.split('.')
                tempo = tempo[0]

                return  data + '  ' + tempo
            },
            setStore(obj){
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = obj
            }
        },
        computed:{
            dadosFiltrados(){
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []

                this.dados.map((item,chave) =>{

                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]
                    })
                    dadosFiltrados.push(itemFiltrado)

                })
                return dadosFiltrados
            }
        }
    }
</script>