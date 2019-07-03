<template>
    <illustrations-view
        :illustrations = 'illustrations'
    ></illustrations-view>
</template>

<script>
import { setTimeout } from 'timers';
    export default {
        data() {
            return {
                illustrations:[],
                next_page_url:'/assets/illustration/8',
            };
        },

        created(){

        },

        mounted(){
            this.loadNextData();
        },

        methods: {
            loadNextData(){
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
            }
        },
    }
</script>

<style>

</style>
