import './bootstrap';
import Vue from 'vue';
import RaffleBox from './components/RaffleBox.vue';
import ApplicantsManager from './components/ApplicantsManager.vue';
import JoinRaffle from './components/JoinRaffle.vue';

new Vue({
    el: '#app',
    components: {RaffleBox, ApplicantsManager, JoinRaffle}
});
