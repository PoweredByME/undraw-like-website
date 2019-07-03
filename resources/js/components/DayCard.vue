<template>
    <div>
        <a href="#" @click.prevent='viewModal' style="text-decoration:none;">
            <div class=" shadow-sm day-card-container">
                <div v-if="video && !month" style="position:relative">
                    <img :src="videoBackgroundImage" width="100%"/>
                    <div class="day-card-play-container">
                        <i class="fa fa-play-circle" style="font-size:60px;color:white;opacity:0.95"></i>
                    </div>
                </div>
                <div v-if="video" :class="['day-card-text-container', 'calendar-text-dark', 'pt-2', 'pb-2', month ? 'no-video' : '']">
                    <p class="text-center mb-1 mt-1 calendar-text-red" v-if="month"><i class="fa fa-play-circle" style="font-size:24px"></i></p>
                    <p class="text-center mb-1 mt-1 text-truncate">{{ text }}</p>
                </div>
                <div v-else :class="['day-card-text-container', 'calendar-text-dark', 'pt-2', 'pb-2', 'no-video']">
                    <p class="text-center mb-1 mt-1 text-truncate">{{ text }}</p>
                </div>
            </div>
        </a>
        <calendar-modal :show='showModal'>

            <div v-if="!video" class="pb-3 d-flex align-items-center justify-content-center" style="height:100vh">
                <div style="
                    max-width:600px;
                    width:96vw;
                    max-height:70vh;
                    height:auto;
                    overflow-y:scroll !important;
                    background:white;
                    color: #343434;
                    border-radius:5px;
                " class="shadow p-5 pb-2">
                    <h1 class="text-center mb-4 calendar-text-dark">{{ text }}</h1>
                    <p class="m-0 text-justify calendar-text-dark">{{ description }}</p>
                    <p class="mb-3"></p>
                </div>
            </div>

            <div v-else class="row" style="height:100vh;overflow-y:scroll !important">

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4" style="height:100vh;">
                    <div style="
                        height:100vh;
                        overflow-y:auto;
                        background:rgba(255,255,255,0.9);
                    " class="shadow calendar-text-dark pt-5 pl-5 pr-5 pb-3">
                        <div>
                            <h1 class="mb-4 text-dark text-center">{{ text }}</h1>
                            <h4 class="m-0 calendar-text-dark text-justify">{{ description }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-8 col-xl-8" style="
                    height:100vh;
                    overflow-y:auto;
                ">
                    <div class="p-5 mt-5">
                        <div class="d-flex align-items-center justify-content-center">
                            <iframe width="966" height="360" :src="videoURL" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <h4 class="text-dark text-center mt-3" style="font-weight:600"><i class="fa fa-share-alt mr-2"></i>Share</h4>
                        <div class="mt-2 d-flex align-items-center flex-wrap justify-content-center">

                            <a :href='"https://www.facebook.com/sharer/sharer.php?u=" + redirectTo' role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #3b5998 !important"><i class="fa fa-facebook mr-2"></i>Facebook</a>
                            <a :href='"https://twitter.com/intent/tweet?url=" + redirectTo' role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #1DA1F2 !important"><i class="fa fa-twitter mr-2"></i>Twitter</a>
                            <a :href='"https://plus.google.com/share?url==" + redirectTo' role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #d34836 !important"><i class="fa fa-google-plus mr-2"></i>Google Plus</a>
                            <a :href='"https://www.linkedin.com/shareArticle?mini=true&url=" + redirectTo' role="btn" class="btn btn-primary mr-2 mt-2" style="border:none;background: #0077b5 !important"><i class="fa fa-linkedin mr-2"></i>Linked In</a>
                        </div>
                        <div class=" mt-4 d-flex align-items-center justify-content-center">
                            <a :href="videoBackgroundImageURL" target="_blank" class="btn btn-light text-dark" role="btn">
                                <i class="fa fa-image mr-2"></i> View Image Source
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            <template v-slot:close>
                <div style="position:absolute;top:0;right:0;font-size:28px;cursor:pointer" class="pt-4 pr-5 calendar-text-red" v-on:click='closeModal'>
                    <i class="fa fa-close"></i>
                </div>
            </template>
        </calendar-modal>
    </div>

</template>



<script>
    export default {
        props: {
            month:{
                type:Boolean,
                default:false,
            },
            text: {
                type:String,
                default: 'some text'
            },
            video:{
                type:Boolean,
                default:false,
            },
            videoURL:{
                type:String,
                default:'',
            },
            videoBackgroundImage: {
                type:String,
                default: '',
            },
            videoBackgroundImageURL: {
                type:String,
                default: '',
            },
            redirectTo:{
                default:'#',
                type:String,
            },
            description:{
                type:String,
                default:'',
            }
        },

        data() {
            return {
                showModal : false,
            }
        },

        methods: {
            closeModal(){
                this.showModal = false;
                console.log('cccccc');
            },

            viewModal(){
                this.showModal = true;
            }
        },

    }
</script>

<style>

    .day-card-text-container{
        background:white;

        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        transition-property: all;
        transition-duration: 150ms;
    }

    .day-card-text-container.no-video{
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .day-card-container{
        transition-property: all;
        transition-duration: 150ms;
        margin-top:14px;
        margin-bottom:14px;
    }

    .day-card-container:hover .day-card-text-container{
        background: rgba(255,255,255,0.6)
    }

    .day-card-container div img{
        transition-property: all;
        transition-duration: 150ms
    }

    .day-card-container:hover div img{
        opacity: 0.93;
    }

    .day-card-container:hover div .day-card-play-container{
        transform: scale(1);
    }

    .day-card-play-container{
        position: absolute;
        top:50%;
        left:50%;
        margin-left:-25px;
        margin-top:-25px;
        transform-origin: center;
        transform: scale(0);
        transition-property: all;
        transition-duration: 150ms;
    }

</style>

