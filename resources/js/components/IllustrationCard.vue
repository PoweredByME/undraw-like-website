<template>
    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
        <a :href="redirectTo" target="" style="text-decoration:none">
            <div class="ill-card mb-4 p-4 pt-3 text-center shadow-sm">
                <div style="width:100%;min-height:200px" class="d-flex align-items-center justify-content-stretch"  v-html='svgData'></div>
                <p class="text-center m-0 calendar-text-dark">{{ title }}</p>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        props:{
            svg : {
                default:'',
            },
            title:{
                type:String,
                default:'',
            },
            redirectTo:{
                type:String,
                default:'#',
            },
        },

        data(){
            return {
                svgData:'',
            };
        },

        mounted(){
            var svgItem = $(this.svg);
            if(svgItem.length > 1){
                svgItem = svgItem[svgItem.length - 1];
                svgItem = $(svgItem);
            }
            this.svgData = svgItem.attr('width', '100%').attr('height','auto').prop('outerHTML');
        },
    }
</script>

<style>
    .ill-card{
        border-radius:5px;
        background: white;
        transition-property: all;
        transition-duration: 400ms;
    }

    .ill-card:hover{
        background: rgba(41, 192, 184, 0.3);
    }
</style>
