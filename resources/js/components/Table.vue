<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="titulo in titulos" :key="titulo.id" class="text-uppercase">{{ titulo.titulo }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                <td v-for="valor, chaveValor in obj" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo === 'texto'">
                        {{ valor }}
                    </span>
                    <span v-if="titulos[chaveValor].tipo === 'data'">
                        {{ valor }}
                    </span>
                    <span v-if="titulos[chaveValor].tipo === 'imagem'">
                        <img :src="'/storage/' + valor" width="30" height="30">
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dados', 'titulos'],
        computed: {
            dadosFiltrados() {
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []

                this.dados?.map((item) => {
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
