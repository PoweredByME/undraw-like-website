<template>
    <div class="p-3 mb-2 shadow-sm d-flex align-items-center justify-content-between" style="background:white">
        <div>
            <slot></slot>
        </div>
        <div>
            <slot name="buttons"></slot>
        </div>
    </div>
</template>


<script>
    export default {

    }
</script>
