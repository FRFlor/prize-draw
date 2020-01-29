import './bootstrap';
import Vue from 'vue';
import RaffleBox from './components/RaffleBox.vue';
import RegistrationForm from './components/RegistrationForm.vue';

new Vue({
    el: '#app',
    components: {RaffleBox, RegistrationForm}
});
