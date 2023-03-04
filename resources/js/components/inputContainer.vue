<template>
    <div class="form-group">
        <label :for="id" class="col-sm-2 col-form-label">{{ titulo }}</label>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: ['id', 'titulo']
    }
</script>
