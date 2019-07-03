<template>
    <div>
        <button v-on:click="show" role="btn" class="btn btn-primary">{{ button_title }}</button>
        <modal :name="name" height="auto" :adaptive="true">
            <div class="v-modal-header p-3 d-flex align-items-center justify-content-between">
                <h3 class="m-0">{{ title }}</h3>
                <a href="#" v-on:click.prevent="hide">
                    <i class="fa fa-close"></i>
                </a>
            </div>
            <div class="v-modal-body p-3">
                <slot></slot>
            </div>
            <div class="v-modal-footer d-flex p-3">
                <div class="ml-auto mr-0">
                    <slot name="footer"></slot>
                    <button class="btn btn-danger ml-2" v-on:click="hide">Close</button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        props: {
            name : {type: String},
            title : {type: String},
            button_title: {type: String},
        },

        methods: {
            show(){
                this.$modal.show(this.name);
            },
            hide(){
                this.$modal.hide(this.name);
            }
        },
    }
</script>

<style>
    .v-modal-header{
        border-bottom: 1px solid #eee;
    }

    .v-modal-header a{
        color: #565656;
        font-size: 22px;
    }

    .v-modal-header a:hover{
        color: #212121;
    }

    .v-modal-footer{
        border-top: 1px solid #eee;
    }
</style>
