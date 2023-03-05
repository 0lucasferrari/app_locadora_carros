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
                        <button type="button" class="btn btn-primary float-end">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {}
</script>


