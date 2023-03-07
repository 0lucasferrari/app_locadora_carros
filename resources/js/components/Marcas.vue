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
                                    <input type="number" class="form-control" id="inputId">
                                </div>
                            </input-container-component>
                        </div>
                
                        <div class="row mb-3">
                            <input-container-component titulo="Nome da marca" id="inputNome">
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputNome">
                                </div>
                            </input-container-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary float-end">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <div class="card-body">
                            <table-component>

                            </table-component>
                        </div>        
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>

            <!-- Modal -->
            <modal-component id="modalMarca" titulo="Adicionar marca">

                <template v-slot:alertas>
                    <alert-component tipo="success"></alert-component>
                    <alert-component tipo="danger"></alert-component>
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
                nomeMarca: '',
                arquivoImagem: []
            }
        },
        methods: {
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
                    .then(response => console.log(response))
                    .catch(errors => console.log(errors))
            }
        }
    }
</script>


