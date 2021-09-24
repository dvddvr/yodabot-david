require('./bootstrap');

import Vue from 'vue'

import conversation from './components/Conversation'

Vue.component(
    'message',
    require('./components/Message.vue').default
);
Vue.component(
    'input-message',
    require('./components/InputMessage.vue').default
);

import VueRouter from 'vue-router'
Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/conversation',
            name: 'conversation',
            component: conversation
        }
    ],
});

// App definition
const app = new Vue({
    el: '#app',
    router,

    data() {
        return {
            modals: [],
        }
    },

    mounted() {

    },

    methods: {
        shuffle(array) {
            var tmp, current, top = array.length;

            if(top) while(--top) {
                current = Math.floor(Math.random() * (top + 1));
                tmp = array[current];
                array[current] = array[top];
                array[top] = tmp;
            }

            return array;
        }
    }
});