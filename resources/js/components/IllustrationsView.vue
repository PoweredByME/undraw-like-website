<template>
    <div class="container mt-5 mb-5">
        <div class="row">

            <illustration-card
                v-for="(item, index) in illustrations"
                :key='index'
                :svg='item.svg'
                :title="item.title"
                :redirectTo="item.redirectTo"
                :class_uid="'svg-' + index"
            ></illustration-card>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['illustrations']
    }
</script>

<style>

</style>
