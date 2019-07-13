<template>
    <div class="ml-5 mr-5 mt-5 mb-4">
        <div class="row">
            <div class="mb-3 col-sm-12 col-md-12 col-lg-4 col-xl-4 calendar-controller-container">
                <div>
                    <calendar-controller-btn :onClick="prevBtnClicked" class="prev-ctrl-btn" icon="fa-chevron-left"></calendar-controller-btn>
                    <calendar-controller-btn :onClick="todayBtnClicked" class="today-ctrl-btn" text="Today" ></calendar-controller-btn>
                    <calendar-controller-btn :onClick="nextBtnClicked" class="next-ctrl-btn" icon="fa-chevron-right"></calendar-controller-btn>
                </div>
            </div>
            <div class="mb-3 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="">
                        <p class="text-center mb-1 calendar-text-dark" style="opacity:0.8">Free Social Calendar</p>
                        <h2 class="text-center" style="opacity:0.9">{{ calendar_MM_YYYY }}</h2>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                <div class="showMonthlyButton-mobile d-flex align-items-center" style="height:100%">
                    <slot name="showMonthlyButton"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            prevBtnClicked : {type:Function},
            nextBtnClicked : {type:Function},
            todayBtnClicked : {type:Function},
            calendar_MM_YYYY : {type : String},
        }
    }
</script>

<style>
    .prev-ctrl-btn{
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        width:80px;
    }
    .next-ctrl-btn{
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        width:80px;
    }

    .today-ctrl-btn{
        width:125px;
    }

    .showMonthlyButton-mobile{
        justify-content: flex-end;
    }


    .calendar-share-btn{
        background: rgba(33, 113, 205, 0.8) !important;
        border-radius: 5px;
        color:white;
        transition-property: all;
        transition-duration: 150ms;
    }

    .calendar-share-btn:hover{
        background: rgba(33, 113, 205, 1) !important;
        color: white;
        text-decoration: none;
    }

    @media (max-width:992px) {
        .calendar-controller-container{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .share-btn-container{
            margin-right:auto;
        }

        .showMonthlyButton-mobile{
           justify-content: center;
        }

        .prev-ctrl-btn{
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            width:60px;
        }
        .next-ctrl-btn{
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            width:60px;
        }

        .today-ctrl-btn{
            width:95px;
        }

    }

</style>
