import './bootstrap';
import { createApp } from 'vue';
import RaffleBox from './components/RaffleBox.vue';
import ApplicantsManager from './components/ApplicantsManager.vue';
import JoinRaffle from './components/JoinRaffle.vue';

createApp({})
    .component('raffle-box', RaffleBox)
    .component('applicants-manager', ApplicantsManager)
    .component('join-raffle', JoinRaffle)
    .mount('#app');
