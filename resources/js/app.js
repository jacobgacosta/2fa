require('./bootstrap');


import UserCreation from './components/UserCreation.vue'

window.Event = new Vue();

const app = new Vue({
    el: '#app',
    components: {
        UserCreation
    }
});
