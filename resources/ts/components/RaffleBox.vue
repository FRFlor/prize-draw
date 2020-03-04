<template>
    <div class="raffle-box">
        <button :disabled="raffleInterval" class="start-raffling" @click="startRaffling">Start Raffling!</button>

        <div class="">
            <div class="applicant-name"
                 v-for="(applicant, index) in applicants"
                 :key="index">
                    <h5 class="">{{applicant}}</h5>
            </div>
        </div>

        <div class="winner-box" v-show="isShowingWinner">
            Congratulations! {{applicants[0]}}, you won!
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';

    @Component
    export default class RaffleBox extends Vue {
        applicants: string[] = [];
        raffleInterval;
        isShowingWinner: boolean = false;

        async mounted() {
            const response = await axios.get<string[]>('/applicants');
            this.applicants = response.data;
        }

        beforeDestroy() {
            if (this.raffleInterval) {
                clearInterval(this.raffleInterval);
            }
        }

        startRaffling() {
            this.raffleInterval = setInterval(() => {
                this.applicants.splice(Math.floor(Math.random() * this.applicants.length), 1);

                if (this.applicants.length <= 1) {
                    clearInterval(this.raffleInterval);
                    setTimeout(() => this.isShowingWinner = true, 1000);
                }
            }, 500);
        }
    }
</script>
