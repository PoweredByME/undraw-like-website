<template>
    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
        <a :href="redirectTo" target="" style="text-decoration:none">
            <div class="ill-card mb-4 p-4 pt-3 text-center shadow-sm">
                <div style="width:100%;min-height:200px" class="d-flex align-items-center justify-content-stretch"  v-html='svgData'></div>
                <p class="text-center m-0 mt-2 calendar-text-dark">{{ title }}</p>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="#" @click.prevent="save('SVG')">.svg</a>
                    <p class="m-0 mx-2 text-dark">|</p>
                    <a href="#" @click.prevent="save('PNG')">.png</a>
                    <p class="m-0 mx-2 text-dark">|</p>
                    <a :href="redirectTo">Edit</a>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        props:{
            class_uid:{
                default: '',
                type:String
            },
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
            this.svgData = svgItem.attr('width', '100%').addClass(this.class_uid).attr('height','auto').prop('outerHTML');
        },

        methods: {
            save(type){
                let _SVG = $("."+this.class_uid);
                if (type=="SVG"){
                    this.saveSvg(_SVG, 'image.svg');
                }else if(type=="PNG"){
                    saveSvgAsPng(document.getElementsByClassName(this.class_uid)[0], 'image.png');
                }
            },

            saveSvg(svgEl, name) {
                svgEl = svgEl[0]
                svgEl.setAttribute("xmlns", "http://www.w3.org/2000/svg");
                var svgData = svgEl.outerHTML;
                var preface = '<?xml version="1.0" standalone="no"?>\r\n';
                var svgBlob = new Blob([preface, svgData], {type:"image/svg+xml;charset=utf-8"});
                var svgUrl = URL.createObjectURL(svgBlob);
                var downloadLink = document.createElement("a");
                downloadLink.href = svgUrl;
                downloadLink.download = name;
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            },
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
