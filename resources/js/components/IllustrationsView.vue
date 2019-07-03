<template>
    <div class="container mt-5 mb-5">
        <div class="row">

            <illustration-card
                v-for="item in illustrations"
                :key='item.id'
                :svg='item.svg'
                :title="item.title"
                :redirectTo="item.redirectTo"
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
