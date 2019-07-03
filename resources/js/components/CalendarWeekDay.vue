<template>
    <div :class="['p-1', 'mb-3',]" style="
        flex-grow:1;
        flex-basis:100%
    ">
        <div :class="['p-2', 'day-container', todayShadowClass, monthly ? 'monthly' : '']">
            <div class="mb-4">
                <p class="text-center mb-1 calendar-text-dark" style="opacity:0.7">{{ day }}</p>
                <h3 class="text-center calendar-text-dark">{{ date }}</h3>
            </div>
            <div v-if="items.length > 0">
                <day-card
                    v-for="item in items"
                    :key = "item.id"
                    :text = "item.text"
                    :videoBackgroundImage="item.videoBackgroundImage"
                    :videoBackgroundImageURL="item.videoBackgroundImageURL"
                    :video="item.video"
                    :redirectTo="item.redirectTo"
                    :description="item.description"
                    :videoURL="item.videoURL"
                    :month="monthly"
                >
                </day-card>
            </div>
            <div v-else>
                <p class="text-center calendar-text-dark" style="font-size:12px;opacity:0.6;">No agenda for this day</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            day: {
                type:String,
                default: 'day',
            },
            date:{
                type:Number,
                default:  1,
            },
            full_date: {type:String},
            items : {type:Array},
            monthly:{type:Boolean,default:false},
            incomplete_week:{type:Boolean,default:false},
        },

        mounted() {
            this.setTodayShadowClass();
        },

        beforeUpdate(){
            this.setTodayShadowClass();
        },

        methods: {
            setTodayShadowClass(){
                var currentDate = new Date();
                if(this.full_date ==`${currentDate.getFullYear()}-${currentDate.getMonth()+1}-${currentDate.getDate()}`){
                    this.todayShadowClass = "calendar-bg-light-blue";
                }else{
                    this.todayShadowClass = 'calendar-bg-light-green';
                }
            }
        },

        data() {
            return {
                todayShadowClass : '',
            }
        },

    }
</script>

<style>
    .day-container{
        border-radius: 5px;
        height: 560px;
        overflow-y: auto
    }

    .day-container.monthly{
        height:200px;
    }

    @media (max-width:992px) {
        .day-container.monthly{
            height:400px;
        }
    }


</style>
