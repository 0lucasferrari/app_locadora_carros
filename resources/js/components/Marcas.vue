<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de marcas</div>

                    <div class="card-body">
                        
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

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-end">Pesquisar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {}
</script>


