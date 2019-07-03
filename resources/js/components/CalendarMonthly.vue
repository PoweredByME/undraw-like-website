<template>
    <div class="mb-5 pb-5">
        <div class="ml-5 mr-5">

                <div v-if="!month" style="height:100vh"></div>
                <calendar-weekly v-else
                    v-for="week in weekedMonth"
                    :key="week.id"
                    :week="week"
                    :monthly="true"
                ></calendar-weekly>

        </div>
    </div>
</template>

<script>
    export default {
        props : {
            month:{type:Array,default:[]},
        },

        data(){
            return {
                weekedMonth : [],
            };
        },

        beforeUpdate(){
            Array.prototype.chunk = function ( n ) {
                if ( !this.length ) {
                    return [];
                }
                return [ this.slice( 0, n ) ].concat( this.slice(n).chunk(n) );
            };
            this.weekedMonth = this.month.chunk(7);
        },
    }
</script>

<style>

</style>

