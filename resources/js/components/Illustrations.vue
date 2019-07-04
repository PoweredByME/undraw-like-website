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
            }
        },
    }
</script>

<style>

</style>
