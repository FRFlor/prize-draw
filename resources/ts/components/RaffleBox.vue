<template>
    <div class="raffle-box">
        <button v-show="!raffleInterval" class="start-raffling" @click="startRaffling">Start Raffling!</button>

        <div class="winner-box" v-show="raffleInterval" v-text="winnerName"/>

        <div class="running-list">
            <h2>In The Running</h2>
            <div class="applicant-name"
                 v-for="(applicant, index) in applicants"
                 :key="`running-${index}`">
                {{applicant}}
            </div>
        </div>

        <div class="eliminated-list">
            <h2>Eliminated</h2>
            <div class="applicant-name"
                 v-for="(applicant, index) in eliminatedApplicants"
                 :key="`eliminated-${index}`"
                 v-text="applicant">
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';

    @Component
    export default class RaffleBox extends Vue {
        applicants: string[] = [];
        eliminatedApplicants: string[] = [];
        raffleInterval;
        isShowingWinner: boolean = false;
        winnerName: string = "";

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
                const [pickedApplicant] = this.applicants.splice(Math.floor(Math.random() * this.applicants.length), 1);

                if (pickedApplicant) {
                    this.eliminatedApplicants.unshift(pickedApplicant);
                }

                if (this.applicants.length === 0) {
                    clearInterval(this.raffleInterval);
                    this.winnerName = this.eliminatedApplicants[0];
                    setTimeout(() => this.isShowingWinner = true, 1000);
                }
            }, 500);
        }
    }
</script>
