<template>
    <div :class='["calendar-modal-wrapper", toShowClass]'>
        <div style="position:relative">

            <slot></slot>

            <slot name="close"></slot>
        </div>

    </div>
</template>

<script>
    export default {
        props:{
            show : {
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                toShowClass: '',
            }
        },

        mounted(){
            if(this.show){
                this.toShowClass = 'show'
                $('body').addClass('no-scroll');
            }
        },

        watch: {
            show : {
                immidiate : true,
                handler(_, __){
                    if(this.show){
                        this.toShowClass = 'show';
                        $('body').addClass('no-scroll');
                    }else{
                        this.toShowClass = '';
                        $('body').removeClass('no-scroll');
                    }

                }
            }
        },
    }
</script>

<style>

    .calendar-modal-wrapper{
        z-index: 100;
        background: rgba(252, 131, 24,0.8);
        color:white;

        position:fixed;;
        height: 100vh;
        width: 100vw;
        top:0;

        left:-100vw;
        transition-property: opacity;
        transition-duration: 250ms;
        opacity: 0;
    }

    .calendar-modal-wrapper.show{
        left:0;
        opacity: 1;
    }

    .modal-title-head{
        font-size: 72px;
    }

</style>
