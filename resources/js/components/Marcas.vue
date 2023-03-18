<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Início do card de busca -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row mb-3">
                            <input-container-component titulo="ID" id="inputId">
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="inputId" v-model="busca.id">
                                </div>
                            </input-container-component>
                        </div>
                
                        <div class="row mb-3">
                            <input-container-component titulo="Nome da marca" id="inputNome">
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputNome" v-model="busca.nome">
                                </div>
                            </input-container-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <div class="card-body">
                            <table-component 
                            :dados="marcas.data"
                            :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar' }"
                            :atualizar="true"
                            :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover' }"
                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                nome: {titulo: 'Nome', tipo: 'texto'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Criado em', tipo: 'data'}
                            }"
                            >

                            </table-component>
                        </div>        
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li 
                                        v-for="l, key in marcas.links" 
                                        :key="key"
                                        @click="paginacao(l)"
                                        :class="l.active ? 'page-item active' : 'page-item'"
                                        >
                                            <a class="page-link" v-html="l.label">
                                            </a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>

            <!-- Modal de inclusão de marca -->
            <modal-component id="modalMarca" titulo="Adicionar marca">

                <template v-slot:alertas>
                    <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                    <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca" v-if="transacaoStatus == 'erro'"></alert-component>
                </template>

                <template v-slot:conteudo>
                    <div class="form-group">
                        <input-container-component titulo="Nome da marca" id="novoNome">
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="novoNome" v-model="nomeMarca">
                            </div>
                        </input-container-component>

                        <input-container-component titulo="Imagem" id="novoImagem">
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="novoImagem" @change="carregarImagem($event)">
                            </div>
                        </input-container-component>
                    </div>
                </template>

                <template v-slot:rodape>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                </template>
            </modal-component>
            <!-- Modal de inclusão de marca -->

            <!-- Modal de visualização de marca -->
            <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
                <template v-slot:alertas>
                </template>

                <template v-slot:conteudo>
                    <input-container-component titulo="ID">
                        <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                    </input-container-component>

                    <input-container-component titulo="Nome da marca">
                        <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                    </input-container-component>

                    <input-container-component titulo="Imagem">
                        <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem">
                    </input-container-component>

                    <input-container-component titulo="Data de criação">
                        <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                    </input-container-component>
                </template>

                <template v-slot:rodape>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </template>
            </modal-component>
            <!-- Modal de visualização de marca -->

            <!-- Modal de remoção de marca -->
            <modal-component id="modalMarcaRemover" titulo="Remover marca">
                <template v-slot:alertas>
                </template>

                <template v-slot:conteudo>

                    <input-container-component titulo="ID">
                        <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                    </input-container-component>

                    <input-container-component titulo="Nome da marca">
                        <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                    </input-container-component>

                </template>

                <template v-slot:rodape>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="remover()">Remover</button>

                </template>
            </modal-component>
            <!-- Modal de remoção de marca -->

        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            token() {
                return 'Bearer ' + document.cookie.split(';').find(index => index.includes('token=')).split('=')[1]
            }
        },
        data() {
            return {
                urlBase: 'http://127.0.0.1:8000/api/v1/marca',
                urlPaginacao: '',
                urlFiltro: '',
                nomeMarca: null,
                arquivoImagem: [],
                transacaoStatus: null,
                transacaoDetalhes: {},
                marcas: [],
                busca: {
                    id: '',
                    nome: ''
                }
            }
        },
        methods: {
            remover() {
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if (!confirmacao) return false;

                let url = this.urlBase + '/' + this.$store.state.item.id

                let formData = new FormData();
                formData.append('_method', 'delete')
                
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                .then(response => {
                    console.log('Registro removido com sucesso', response)
                    this.carregarLista()
                    })
                .catch(errors => console.log('Houve um erro na tentativa de remoção do registro', errors.data))

            },
            pesquisar() {
                let filtro = ''
                for(let chave in this.busca) {
                    if(this.busca[chave]) {
                        if(filtro != '') {
                            filtro += ';'
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }
                if (filtro !== '') {
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro=' + filtro
                } else {
                    this.urlFiltro = null
                }
                this.carregarLista()
            },
            paginacao(l) {
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista()
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                axios.get(url, config)
                .then(response => {
                    this.marcas = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
            },
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: ' + response.data.id
                        }
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                    })
            }
        },
        mounted() {
            this.carregarLista()
        }
    }
</script>


