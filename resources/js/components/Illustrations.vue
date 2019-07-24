<template>
    <div>
        <illustrations-view v-if="illustrations.length > 0"
            :illustrations = 'illustrations'
        ></illustrations-view>

        <div v-else-if="illustrations.length < 1 && search_in.length > 0" class="pt-5 pb-5 container">
            <h4 class="text-center">Searching for '{{ search_in }}' illustrations</h4>
        </div>
        <div v-else class="pt-5 pb-5 container">
            <h4 class="text-center">Loading <span style="letter-spacing:2px">Illustration</span> please wait. Thank you...</h4>
        </div>
        <div v-observe-visibility="visibilityChanged"></div>


        <div class="ill-fab-container pb-4 pr-4">
            <div class="d-flex align-items-center justify-content-end">
                <button class="ill-fab-btn shadow text-white calendar-bg-blue" @click="() => {this.showEditColors = !this.showEditColors}">
                    <i v-if="!showEditColors" class="fa fa-edit ill-fab-btn-icon"/>
                    <i v-else class="fa fa-close ill-fab-btn-icon"/>
                </button>
            </div>
            <div v-if="showEditColors"  class="d-flex align-items-center justify-content-center px-2 my-1" style="background:#ffffff99;border-radius:5px;border:1px solid #cacaca">
                <button
                            role="btn"
                            v-for="cs in [0,1,2]"
                            :key="cs"
                            :class='["color-btn mx-1 p-0", cs == selectedColorIdx ? "color-btn-selected" : "", "edit-btn-" + selectedColorID_1[cs].ref.substr(1)]'
                            :edit-data="cs"
                            @click = "onSelectColor(cs)"
                            title=' Select this to edit the referenced elements of the Images'
                >
                    <i :class='["fa", cs == selectedColorIdx ? "fa-square" : "fa-circle"]'></i>
                </button>

                <button class="color-btn mx-1 p-0" @click="undoEdit()">
                    <i class="fa fa-undo text-dark"></i>
                </button>
            </div>
            <sketch-picker v-if="showEditColors"  class="" v-model="colors" :presetColors="[
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
</template>

<script>
import { setTimeout } from 'timers';
    export default {
        props: {
            search_in : {
                type: String,
                default: ''
            },
        },


        data() {
            return {
                showEditColors : false,
                colors:'',
                illustrations:[],
                next_page_url:'/assets/illustration/5',
                selectedColorID_1: [
                    {ref : '.color-1', color : null},
                    {ref : '.color-2', color : null},
                    {ref : '.color-3', color : null},
                ],
                selectedColorID_2: [
                    {ref : '.color_x5F_1', color : null},
                    {ref : '.color_x5F_2', color : null},
                    {ref : '.color_x5F_3', color : null},
                ],
                selectedColorID_3: [
                    {ref : '.color_1', color : null},
                    {ref : '.color_2', color : null},
                    {ref : '.color_3', color : null},
                ],
                selectedColorIdx : null,
                editHistory: [],
            };
        },

        created(){

        },

        mounted(){
            if(this.search_in == ""){
                //this.loadNextData();
            }else{
                //this.searchIllustration();
            }
        },

        methods: {

            searchIllustration(){
                let inst = this;
                axios.get('/illustration/search/' + this.search_in)
                .then(function(response){
                    var data = response.data;
                    inst.illustrations = data;
                    inst.next_page_url = null;
                    for(var i = 0; i < inst.selectedColorID_1.length; i++){
                        if( inst.selectedColorID_1[i].color
                            && inst.selectedColorID_2[i].color
                            && inst.selectedColorID_3[i].color){
                            $(inst.selectedColorID_1[i].ref).attr('fill', inst.selectedColorID_1[i].color);
                            $(inst.selectedColorID_2[i].ref).attr('fill', inst.selectedColorID_2[i].color);
                            $(inst.selectedColorID_3[i].ref).attr('fill', inst.selectedColorID_3[i].color);
                        }
                    }
                })
                .catch(function(error){
                    alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                    console.log(error);
                });
            },

            editSVG(undo = false){
                let inst = this;
                if(inst.selectedColorIdx != null){
                    var selectedColorEl = $(this.selectedColorID_1[this.selectedColorIdx].ref).length > 0 ?
                                        $(this.selectedColorID_1[this.selectedColorIdx].ref) :
                                        $(this.selectedColorID_2[this.selectedColorIdx].ref).length > 0 ?
                                        $(this.selectedColorID_2[this.selectedColorIdx].ref) :
                                        $(this.selectedColorID_3[this.selectedColorIdx].ref).length > 0 ?
                                        $(this.selectedColorID_3[this.selectedColorIdx].ref) :
                                        null;
                    if(!selectedColorEl) return;
                    let oldEdit = selectedColorEl.attr('fill');
                    $(inst.selectedColorID_1[inst.selectedColorIdx].ref).attr('fill', inst.selectedColorID_1[inst.selectedColorIdx].color);
                    $(inst.selectedColorID_2[inst.selectedColorIdx].ref).attr('fill', inst.selectedColorID_2[inst.selectedColorIdx].color);
                    $(inst.selectedColorID_3[inst.selectedColorIdx].ref).attr('fill', inst.selectedColorID_3[inst.selectedColorIdx].color);
                    let newEdit = inst.selectedColorID_1[inst.selectedColorIdx].color;
                    $(".edit-btn-" + inst.selectedColorID_1[inst.selectedColorIdx].ref.substr(1) + " i").css('color', inst.selectedColorID_1[inst.selectedColorIdx].color);
                    if(!undo){
                        // if undo function not happening
                        inst.editHistory.push({
                            idx : inst.selectedColorIdx,
                            newEdit : newEdit,
                            oldEdit : oldEdit,
                        });
                    }
                }
            },

            onSelectColor(idx){
                if(idx > -1 && idx < this.selectedColorID_1.length + 1){
                    this.selectedColorIdx = idx;
                }else{
                    this.selectedColorIdx = null;
                    return;
                }
                var selectedColorEl = $(this.selectedColorID_1[this.selectedColorIdx]).length > 0 ?
                                        $(this.selectedColorID_1[this.selectedColorIdx].ref) :
                                        $(this.selectedColorID_2[this.selectedColorIdx].ref).length > 0 ?
                                        $(this.selectedColorID_2[this.selectedColorIdx].ref) :
                                        $(this.selectedColorID_3[this.selectedColorIdx].ref).length > 0 ?
                                        $(this.selectedColorID_3[this.selectedColorIdx].ref) :
                                        null;


                if(selectedColorEl){
                    this.colors = {hex : selectedColorEl.attr('fill')};
                }

            },

            loadNextData(){
                if(!this.next_page_url){return;}
                let inst = this;
                var nextPageURl = this.next_page_url;
                this.next_page_url = null;
                axios.get(nextPageURl)
                .then(function(response){
                    var data = response.data;
                    inst.illustrations = inst.illustrations.concat(data.illustrations);
                    inst.next_page_url = data.next_page_url;
                    for(var i = 0; i < inst.selectedColorID_1.length; i++){
                        if( inst.selectedColorID_1[i].color
                            && inst.selectedColorID_2[i].color
                            && inst.selectedColorID_3[i].color){
                            $(inst.selectedColorID_1[i].ref).attr('fill', inst.selectedColorID_1[i].color);
                            $(inst.selectedColorID_2[i].ref).attr('fill', inst.selectedColorID_2[i].color);
                            $(inst.selectedColorID_3[i].ref).attr('fill', inst.selectedColorID_3[i].color);
                        }
                    }
                })
                .catch(function(error){
                    alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                    console.log(error);
                });
            },

            visibilityChanged (isVisible, entry) {
                this.isVisible = isVisible
                if(this.search_in == ""){
                    this.loadNextData();
                }
            },

            undoEdit(){
                var edit = this.editHistory.pop();
                if(!edit) return;
                this.selectedColorIdx = edit.idx;
                this.selectedColorID_1[this.selectedColorIdx].color = edit.oldEdit;
                this.selectedColorID_2[this.selectedColorIdx].color = edit.oldEdit;
                this.selectedColorID_3[this.selectedColorIdx].color = edit.oldEdit;
                this.editSVG(true);
                if (this.editHistory.length < 1) this.selectedColorIdx = null;
            }
        },

        watch: {
            search_in : function(newVal, oldVal){
                if(this.search_in == ""){
                    this.next_page_url = "/assets/illustration/5";
                    this.illustrations = [];
                    this.loadNextData();
                }else{
                    this.illustrations = [];
                    this.next_page_url = null;
                    this.searchIllustration();
                }
            },

            colors : function(newVal, oldVal){
                if(this.selectedColorIdx  != null){
                    this.selectedColorID_1[this.selectedColorIdx].color = newVal.hex8;
                    this.selectedColorID_2[this.selectedColorIdx].color = newVal.hex8;
                    this.selectedColorID_3[this.selectedColorIdx].color = newVal.hex8;
                    this.editSVG();
                }
            }
        },
    }
</script>

<style>

    .color-btn{
        background:transparent;
        border:none;
        outline:none;
        font-size:24px;
        cursor: pointer;

    }

    .color-btn i{
        color:#2171cd;
    }

</style>
