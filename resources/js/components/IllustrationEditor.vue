<template>
        <div v-if="item" class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="ill-svg-container d-flex align-items-center justify-content-center p-2 pt-5 pb-5">
                        <div style="cursor:pointer" v-html='svg'></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="pl-2 pr-2 pb-5 ill-svg-container d-flex align-items-center justify-content-stretch">
                        <div class="pt-5">
                            <div class="mb-4">
                                <h1>{{ item.title }}</h1>
                                <!--<h5 style="letter-spacing:2px;">Edit Or Download</h5>-->
                            </div>
                            <div class="mb-3">
                                <button @click="save('SVG')" role="btn" class="btn btn-outline-dark mr-2 mb-2"><i class="fa fa-download mr-2"></i>Download SVG</button>
                                <button @click="save('PNG')" role="btn" class="btn btn-outline-dark mr-2 mb-2"><i class="fa fa-download mr-2"></i>Download PNG</button>
                            </div>
                            <div>
                                <h4 class="mb-2"><i class="fa fa-edit mr-2"></i>Edit Colors</h4>
                                <!--<p>Please click on any part of the image to color it. Please, click the button below to color the entire image.
                                    <br>
                                    <strong>NOTE : The button to color the entire image is already selected.</strong>
                                </p>-->
                                <div>
                                    <button
                                        role="btn"
                                        v-for="cs in item.color_slots"
                                        :key="cs.id"
                                        :class='["btn mr-2 mb-1", cs.color_id == selectedColorID ? "btn-dark" : " btn-outline-dark", "edit-btn-" + cs.color_id]'
                                        :edit-data="cs.color_id"
                                        @click = "onSelectColor(cs.color_id)"
                                    >
                                    <i class="fa fa-paint-brush mr-2"></i>
                                    <span>{{ cs.color_id }}</span>
                                    </button>
                                </div>
                                <sketch-picker v-if="selectedColorID.length > 0" class="mt-3" v-model="colors" :presetColors="[
                                                                                            '#ffffff',
                                                                                            '#f3ec3a',
                                                                                            '#f9bd17',
                                                                                            '#f99b1d',
                                                                                            '#f15523',
                                                                                            '#ef3124',
                                                                                            '#DE0081',
                                                                                            '#a71e48',
                                                                                            '#7c3597',
                                                                                            '#463191',
                                                                                            '#000000',
                                                                                            '#3d5dac',
                                                                                            '#1296ce',
                                                                                            '#63b145',
                                                                                            '#19BC81',
                                                                                            '#d0dd36',
                                                                                            ]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="pt-5 pb-5 mt-5 container">
            <!--<h4 class="text-center">Loading <span style="letter-spacing:2px">Illustration</span> please wait. Thank you...</h4>-->
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
                colors: {hex : "#324534"},
                selectedColorID:'',
                svgEventsAttached: false
            };
        },


        mounted(){
            let inst = this;
            axios.post(this.redirect_to)
            .then(function(response){
                inst.selectedColorID = '';
                inst.item = response.data;
                var svgItem = $(inst.item.svg);
                if(svgItem.length > 1){
                    svgItem = svgItem[svgItem.length - 1];
                    svgItem = $(svgItem);
                }
                inst.svg = svgItem.attr('width', '100%').attr('height','auto').prop('outerHTML');
                //inst.item.color_slots = [ inst.item.color_slots[0] ];
                //inst.item.color_slots[0].color_id = 'color-entire-image';
                //inst.selectedColorID = inst.item.color_slots[0] ? inst.item.color_slots[0].color_id : '';
            }).then(function(){
                inst.item.color_slots.forEach(element => {
                    $('.edit-btn-' + element.color_id + " i").css('color', $("." + element.color_id).attr('fill'));
                });
                if(inst.selectedColorID != ''){
                    inst.colors = {hex : $('.' + inst.selectedColorID).attr('fill')};
                }
                inst.item.color_slots.forEach(element => {
                    $('.edit-btn-' + element.color_id + " i").css('color', $("." + element.color_id).attr('fill'));
                    $('.edit-btn-' + element.color_id + " span").html($("." + element.color_id).attr('fill'));
                });
            })
            .catch(function(error){
                alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                console.log(error);
            });
        },

        updated() {
            if(!this.svgEventsAttached){
                let _i = 1;
                let _CSEN = 'element-';
                let inst = this;
                $('svg').find("*").each(function(){
                    var el = $('svg').find($(this));
                    el.addClass('color-all');
                    el.addClass(_CSEN + _i);
                    el.click(function(){
                        inst.onSelectColor(el.attr('class').split(' ').pop());
                    })
                    _i += 1;
                });
                this.svgEventsAttached = true;
            }
        },

        watch:{
            colors: function(newVal, oldVal){
                $('.' + this.selectedColorID).attr('fill', newVal.hex8).find('*').each(function(){
                    $('svg').find($(this)).attr('fill', newVal.hex8);
                });

                let inst = this;
                inst.item.color_slots.forEach(element => {
                    $('.edit-btn-' + element.color_id + " i").css('color', $("." + element.color_id).attr('fill'));
                    $('.edit-btn-' + element.color_id + " span").html($("." + element.color_id).attr('fill'));
                });
            },
        },



        methods:{

            onSelectColor(color_id){
                this.selectedColorID = color_id;
                this.colors = {hex : $('.' + this.selectedColorID).attr('fill')};
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
        min-height:100vh;
        padding-top:24px;
    }

    @media (max-width:992px){
        .ill-svg-container{
            min-height: auto;
            height:auto;
        }
    }
</style>
