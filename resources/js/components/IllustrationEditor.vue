<template>
        <div v-if="item" class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="ill-svg-container d-flex align-items-center justify-content-center p-2 pt-5 pb-5">
                        <div v-html='svg'></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="pl-2 pr-2 pb-5 ill-svg-container d-flex align-items-center justify-content-stretch">
                        <div>
                            <div class="mb-4">
                                <h1>{{ item.title }}</h1>
                                <h5 style="letter-spacing:2px;">Edit Or Download</h5>
                            </div>
                            <div class="mb-3">
                                <button @click="save('SVG')" role="btn" class="btn btn-outline-dark mr-2 mb-2"><i class="fa fa-download mr-2"></i>Download SVG</button>
                                <button @click="save('PNG')" role="btn" class="btn btn-outline-dark mr-2 mb-2"><i class="fa fa-download mr-2"></i>Download PNG</button>
                            </div>
                            <div>
                                <h4><i class="fa fa-edit mr-2"></i>Edit Colors</h4>
                                <p>Please click on the color id you want to edit.</p>
                                <div>
                                    <button
                                        role="btn"
                                        v-for="cs in item.color_slots"
                                        :key="cs.id"
                                        :class='["btn mr-2 mb-1", cs.color_id == selectedColorID ? "btn-dark" : " btn-outline-dark", "edit-btn-" + cs.color_id]'
                                        :edit-data="cs.color_id"
                                        @click = "onSelectColor(cs.color_id)"
                                    >{{ cs.color_id }}</button>
                                </div>
                                <sketch-picker class="mt-3" v-model="colors"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</template>

<script>
    export default {
        props : {
            redirect_to : {
                type:String,
                default:null,
            },
        },

        data(){
            return {
                item:null,
                svg : null,
                colors:'',
                selectedColorID:'',
            };
        },

        mounted(){
            let inst = this;
            axios.post(this.redirect_to)
            .then(function(response){
                inst.selectedColorID = response.data.color_slots[0] ? response.data.color_slots[0].color_id : '';
                inst.item = response.data;
                inst.svg = $(inst.item.svg).attr('width', '100%').attr('height','auto').prop('outerHTML');
            })
            .catch(function(error){
                alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                console.log(error);
            });
        },

        watch:{
            colors: function(newVal, oldVal){
                $('.' + this.selectedColorID).attr('fill', newVal.hex8);
            },
        },

        methods:{
            onSelectColor(color_id){
                this.selectedColorID = color_id;
            },

            save(type){
                let _SVG = $('svg');
                if (type=="SVG"){
                    this.saveSvg(_SVG, 'image.svg');
                }else if(type=="PNG"){
                    saveSvgAsPng(document.getElementsByTagName('svg')[0], 'image.png');
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
            }
        },

    }
</script>

<style>
    .ill-svg-container{
        height:100vh;
    }

    @media (max-width:992px){
        .ill-svg-container{
            height:auto;
        }
    }
</style>
