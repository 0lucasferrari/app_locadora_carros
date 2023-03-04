<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Busca de marcas</div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="inputId" class="col-sm-2 col-form-label">ID</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="inputId">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputNome" class="col-sm-2 col-form-label">Nome</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputNome">
                            </div>
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


