<template>
    <div :class='[!monthly ?"mb-5 pb-5":""]'>
        <div :class='[!monthly ?"ml-5 mr-5":""]'>
            <div :class='["calendar-weekly-container align-items-center", (week != null && week.length < 7) ? "justify-content-start" : ""]'>

                <calendar-week-day
                    v-for="day in week"
                    :day="day.day"
                    :date="day.date"
                    :key="day.id"
                    :full_date="day.full_date"
                    :items = "day.items"
                    :monthly = 'monthly'
                    :incomplete_week = '(week != null && week.length < 7)'
                ></calendar-week-day>


            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : {
            week : {type:Array, default:[]},
            monthly: {type:Boolean, default:false},
        }
    }
</script>

<style>
    .calendar-weekly-container{
        display: flex;
    }

    @media (max-width: 992px) {
        .calendar-weekly-container{
            display:block;
        }
    }
</style>

