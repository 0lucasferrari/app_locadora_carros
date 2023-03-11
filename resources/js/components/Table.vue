<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col" v-for="titulo in titulos" :key="titulo.id" class="text-uppercase">{{ titulo }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj in dados" :key="obj.id">
                <template v-for="value, key in obj">
                    <td v-if="titulos.includes(key)" :key="key">
                        <span v-if="key === 'imagem'">
                            <img :src="'/storage/' + value" width="30" height="30">
                        </span>
                        <span v-else>
                            {{value}}
                        </span>
                    </td>
                    <!--
                    <th scope="row">{{ marca.id }}</th>
                    <td>{{ marca.nome }}</td>
                    <td><img :src="'/storage/' + marca.imagem" width="30" height="30"></td>
                    -->
                </template>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dados', 'titulos']
    }
</script>
