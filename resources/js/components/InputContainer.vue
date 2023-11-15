<template>

    <div class="form-group">
        <label :for="id" class="form-label">{{titulo}}</label>
        <slot></slot>
        <div :id="IDHelp" class="form-text">{{ajuda}}</div>
    </div>

</template>

<script>
    export default {
        props: ['id', 'titulo','IDHelp', 'ajuda']
    }
</script>
