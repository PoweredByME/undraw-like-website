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
                            <div class="mb-3">
                                <h1>{{ item.title }}</h1>
                                <!--<h5 style="letter-spacing:2px;">Edit Or Download</h5>-->
                            </div>
                            <div>
                                <div>
                                    <button
                                        role="btn"
                                        v-for="cs in item.color_slots"
                                        :key="cs.id"
                                        :class='["color-btn mr-3 p-0 mb-1", cs.color_id == selectedColorID ? "color-btn-selected" : "", "edit-btn-" + cs.color_id]'
                                        :edit-data="cs.color_id"
                                        @click = "onSelectColor(cs.color_id)"
                                        :title="' Select this to edit the referenced element of the Image. (' + cs.color_id + ')'"
                                    >
                                    <i :class='["fa", cs.color_id == selectedColorID ? "fa-square" : "fa-circle"]'></i>
                                    </button>
                                </div>
                                <div v-if="selectedColorID.length > 0" class="d-flex flex-wrap mt-3">
                                    <div class="mb-3 mr-3">
                                        <sketch-picker  class="" v-model="colors" :presetColors="[
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
                                <div>
                                    <button v-if="editHistory.length > 0" @click="undoEdit()"  class="dl-svg-btn undo-btn mr-2 mb-2" role='btn'><i class="fa fa-undo mr-2"></i>Undo Changes</button>
                                </div>
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <div>
                                    <button @click="save('SVG')"  class="dl-svg-btn mr-2 mb-2" role='btn'><i class="fa fa-download mr-2"></i>Download SVG</button>
                                    <button @click="save('PNG')"  class="dl-svg-btn mr-2 mb-2" role='btn'><i class="fa fa-download mr-2"></i>Download PNG</button>
                                </div>
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
                colors: {hex8 : "#324534FF"},
                selectedColorID:'',
                svgEventsAttached: false,
                editHistory: [],
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
                    //el.addClass('color-all');
                    if(!el.attr('fill')) return;
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
                if(!newVal || !oldVal) return;
                var oldEdit = $('.' + this.selectedColorID).attr('fill');
                $('.' + this.selectedColorID).attr('fill', newVal.hex8).find('*').each(function(){
                    //$('svg').find($(this)).attr('fill', newVal.hex8);
                });
                var newEdit = $('.' + this.selectedColorID).attr('fill');

                let inst = this;
                inst.item.color_slots.forEach(element => {
                    $('.edit-btn-' + element.color_id + " i").css('color', $("." + element.color_id).attr('fill'));
                    $('.edit-btn-' + element.color_id + " span").html($("." + element.color_id).attr('fill'));
                });

                this.editHistory.push({
                    item : this.selectedColorID,
                    newEdit : newEdit,
                    oldEdit : oldEdit,
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
            },

            undoEdit(){
                var edit = this.editHistory.pop();
                if(!edit) return;
                $('.' + edit.item).attr('fill', edit.oldEdit).find('*').each(function(){
                    $('svg').find($(this)).attr('fill', edit.oldEdit);
                });

                let inst = this;
                inst.item.color_slots.forEach(element => {
                    $('.edit-btn-' + element.color_id + " i").css('color', $("." + element.color_id).attr('fill'));
                    $('.edit-btn-' + element.color_id + " span").html($("." + element.color_id).attr('fill'));
                });
                if (this.editHistory.length < 1) this.selectedColorID = "";
            }
        },

    }
</script>

<style>
    .ill-svg-container{
        min-height:100vh;
        padding-top:24px;
    }

    .color-btn{
        background:transparent;
        border:none;
        outline:none;
        font-size:24px;
        cursor: pointer;
    }

    .dl-svg-btn{
        background: #eee;
        border: none;
        outline: none;
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 24px;
        padding-right: 24px;
        border-radius: 4px;
        transition-property: all;
        transition-duration: 150ms;
    }

    .undo-btn{
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 16px;
        padding-right: 16px;
        border-radius: 24px;

    }

    .undo-btn:hover{
        background-color: #fc8318 !important;
        color:white
    }

    .dl-svg-btn:hover{
        background: #2171cd;
        color: white
    }

    .dl-png-btn{}

    @media (max-width:992px){
        .ill-svg-container{
            min-height: auto;
            height:auto;
        }
    }
</style>
