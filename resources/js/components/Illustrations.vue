<template>
    <div>
        <illustrations-view v-if="illustrations.length > 0"
            :illustrations = 'illustrations'
        ></illustrations-view>
        <div v-else-if="illustrations.length < 1 && search_in.length > 0" class="pt-5 pb-5 container">
            <h4 class="text-center">Sorry, could not find the '{{ search_in }}' illustrations</h4>
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
            <sketch-picker v-if="showEditColors" class="mt-3" v-model="colors" :presetColors="[
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
                })
                .catch(function(error){
                    alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                    console.log(error);
                });
            },

            loadNextData(){
                if(!this.next_page_url){return;}
                let inst = this;
                axios.get(this.next_page_url)
                .then(function(response){
                    var data = response.data;
                    inst.illustrations = inst.illustrations.concat(data.illustrations);
                    inst.next_page_url = data.next_page_url;
                })
                .catch(function(error){
                    alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                    console.log(error);
                });
            },

            visibilityChanged (isVisible, entry) {
                this.isVisible = isVisible
                this.loadNextData();
            },
        },

        watch: {
            search_in : function(newVal, oldVal){
                if(this.search_in == ""){
                    this.next_page_url = "/assets/illustration/5";
                    this.illustrations = [];
                    this.loadNextData();
                }else{
                    this.searchIllustration();
                }
            },

            colors : function(newVal, oldVal){
                $(".color-0").attr('fill', newVal.hex8);
            }
        },
    }
</script>

<style>

</style>
