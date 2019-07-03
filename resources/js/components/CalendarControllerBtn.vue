<template>
    <button class="calendar-controller-btn pt-3 pb-3 pl-4 pr-4" v-on:click='onClick'>
        <i v-if="icon.length > 0" :class="['fa', icon]"></i>{{ text }}
    </button>
</template>

<script>
    export default {
        props : {
            onClick :{
                type:Function,
                default:function(){console.log('No function passed. Please set a function')}
            },
            text : {
                type: String,
                default: "",
            },
            icon : {
                type: String,
                default: "",
            },
        },
    }
</script>

<style>
    .calendar-controller-btn{
        background: rgba(41, 192, 184, 0.2);
        color: #212529;
        cursor: pointer;
        outline: none;
        border:none;
        transition-property: all;
        transition-duration: 150ms;
    }
    .calendar-controller-btn:hover{
        background: #29c0b8;
        color:white
    }
</style>
