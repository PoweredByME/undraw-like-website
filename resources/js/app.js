/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VModal from 'vue-js-modal';
import { Sketch } from 'vue-color';
import VueObserveVisibility from 'vue-observe-visibility'



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('calendar-component', require('./components/Calendar.vue').default);
Vue.component('calendar-controller', require('./components/CalendarController.vue').default);
Vue.component('calendar-controller-btn', require('./components/CalendarControllerBtn.vue').default);
Vue.component('calendar-weekly', require('./components/CalendarWeekly.vue').default);
Vue.component('calendar-week-day', require('./components/CalendarWeekDay.vue').default);
Vue.component('day-card', require('./components/DayCard.vue').default);
Vue.component('v-modal', require('./components/admin/vModal.vue').default);
Vue.component('va-card', require('./components/admin/vCard.vue').default);
Vue.component('calendar-modal', require('./components/CalendarModal.vue').default);
Vue.component('calendar-monthly', require('./components/CalendarMonthly.vue').default);



Vue.component('illustrations-component', require('./components/Illustrations.vue').default);
Vue.component('illustrations-view', require('./components/IllustrationsView.vue').default);
Vue.component('illustration-card', require('./components/IllustrationCard.vue').default);
Vue.component('illustration-editor', require('./components/IllustrationEditor.vue').default);
Vue.component('sketch-picker', Sketch);

Vue.use(VModal);
Vue.use(VueObserveVisibility);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    data : {
        search_in:''
    },
    methods : {
        editColor(i,color){
            // This is for the coloring of the images via the button under the search bar.
            // This has nothing to do with the code that is responsible for the coloring
            // of the elements using the FAB at the bottom right corner.
            var selectedColorID_1 = [
                {ref : '.color-1', },
                {ref : '.color-2', },
                {ref : '.color-3', },
                {ref : '.color-4', },
                {ref : '.color-5', },
            ];
            var selectedColorID_2 = [
                {ref : '.color_x5F_1', },
                {ref : '.color_x5F_2', },
                {ref : '.color_x5F_3', },
                {ref : '.color_x5F_4', },
                {ref : '.color_x5F_5', },
            ];
            var selectedColorID_3 = [
                {ref : '.color_1', },
                {ref : '.color_2', },
                {ref : '.color_3', },
                {ref : '.color_4', },
                {ref : '.color_5', },
            ];

            for(var idx = 0; idx < 5; idx++){
                $(selectedColorID_1[idx].ref).attr('fill', color);
                $(selectedColorID_2[idx].ref).attr('fill', color);
                $(selectedColorID_3[idx].ref).attr('fill', color);
            }
        }
    }
});


