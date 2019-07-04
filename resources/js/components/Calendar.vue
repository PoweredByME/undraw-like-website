<template>
    <div>
        <calendar-controller
            :prevBtnClicked='prevBtnClicked'
            :nextBtnClicked='nextBtnClicked'
            :todayBtnClicked='todayBtnClicked'
            :calendar_MM_YYYY = 'MM_YYYY'
        >

            <template v-slot:showMonthlyButton>
                <a href="#" @click.prevent="showMonthlyView" class="pt-3 pb-3 pl-5 pr-5 calendar-share-btn"><i class="mr-2 fa fa-calendar"></i>
                    {{ showMonthlyViewBtnText }}
                </a>
            </template>

        </calendar-controller>
        <calendar-weekly
            v-if='!showMonthly'
            :week = "week"
        ></calendar-weekly>
        <calendar-monthly
            v-else
            :month='month'
        ></calendar-monthly>


    </div>
</template>

<script>
    export default {


        computed: {
            today : function(){
                var date = new Date();
                return date;
            },
        },

        data(){
            return {
                centralDate : 0,
                currentMonth : 0,
                currentYear : 0,
                week : null,
                month: null,
                MM_YYYY : null,
                showMonthly : false,
                showMonthlyViewBtnText : 'Monthly View'
            }
        },

        created() {
            this.centralDate = this.today
            this.currentMonth = this.today.getMonth() + 1;
            this.currentYear = this.today.getFullYear();
        },

        mounted() {
            this.getWeek();
        },

        methods: {
            showMonthlyView(){
                this.showMonthly = !this.showMonthly;
                this.showMonthlyViewBtnText = this.showMonthly ? 'Weekly View' : 'Monthly View';
                if(this.showMonthly){
                    this.currentMonth = this.centralDate.getMonth() + 1;
                    this.currentYear = this.centralDate.getFullYear();
                }
                this.getData();
            },

            prevBtnClicked(){
                if(this.showMonthly){
                    this.currentMonth -= 1;
                    this.correctMonths();
                }
                else{
                    this.centralDate.setTime(this.centralDate.getTime() - (7*24*3600000));
                }
                this.getData();
            },

            nextBtnClicked(){
                console.log(this.currentMonth);
                if(this.showMonthly){
                    this.currentMonth += 1;
                    this.correctMonths();
                }
                else{
                    this.centralDate.setTime(this.centralDate.getTime() + (7*24*3600000));
                }
                this.getData();
            },

            todayBtnClicked(){
                this.centralDate = new Date();
                this.currentMonth = this.centralDate.getMonth() + 1;
                this.currentYear = this.centralDate.getFullYear();
                this.getData();
            },

            correctMonths(){
                if(this.currentMonth < 1){
                    this.currentMonth = 12;
                    this.currentYear -= 1;
                }else if(this.currentMonth > 12){
                    this.currentMonth = 1;
                    this.currentYear += 1;
                }
                if(this.currentYear < 1){
                    this.currentYear = (new Date()).getFullYear();
                }
            },

            getData(){
                if(this.showMonthly){
                    this.getMonth();
                }
                else{
                    this.getWeek();
                }
            },

            getWeek(){
                var date = `${this.centralDate.getFullYear()}-${this.centralDate.getMonth()+1}-${this.centralDate.getDate()}`;
                let inst = this;
                axios.get('/calendar/week/' + date)
                .then(function(response){
                    var rd = response.data;
                    inst.week = rd;
                    var showMonth = `${rd[0].english_month}`;
                    var showYear = `${rd[0].year}`;
                    if(rd[0].month != rd[rd.length-1].month){
                        showMonth = `${rd[0].english_month} - ${rd[rd.length-1].english_month}`;
                    }
                    if(rd[0].year != rd[rd.length-1].year){
                        showYear = '';
                        showMonth = `${rd[0].english_month} ${rd[0].year} - ${rd[rd.length-1].english_month} ${rd[rd.length-1].year}`;
                    }
                    inst.MM_YYYY = showMonth + ' ' + showYear;
                })
                .catch(function(error){
                    alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                    console.log(error);
                });

            },

            getMonth(){
                var date = `${this.currentYear}-${this.currentMonth}`;
                let inst = this;
                //inst.month = null;
                axios.get('/calendar/month/' + date)
                .then(function(response){
                    var rd = response.data;
                    inst.month = rd;
                    var showMonth = `${rd[0].english_month}`;
                    var showYear = `${rd[0].year}`;
                    if(rd[0].month != rd[rd.length-1].month){
                        showMonth = `${rd[0].english_month} - ${rd[rd.length-1].english_month}`;
                    }
                    if(rd[0].year != rd[rd.length-1].year){
                        showYear = '';
                        showMonth = `${rd[0].english_month} ${rd[0].year} - ${rd[rd.length-1].english_month} ${rd[rd.length-1].year}`;
                    }
                    inst.MM_YYYY = showMonth + ' ' + showYear;
                })
                .catch(function(error){
                    alert('Could not reach the serve or load the data. Please check your connectivity. We are sorry for your inconvenience.')
                    console.log(error);
                });
            }

        },

    }
</script>
