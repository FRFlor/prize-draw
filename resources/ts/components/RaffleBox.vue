<template>
    <div class="raffle-box">
        <button v-show="!raffleInterval" class="start-raffling" @click="startRaffling">Start Raffling!</button>

        <h2 v-show="raffleInterval" class="winner-header">Winner</h2>
        <div class="winner-box" v-show="raffleInterval" v-text="winnerName"/>

        <div class="lists">
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
                     v-text="`${applicant}`">
                </div>
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
        raffleInterval = null;
        isShowingWinner: boolean = false;
        winnerName: string = '';

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
                    this.eliminatedApplicants.unshift(`${this.applicants.length + 1} ${pickedApplicant}`);
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

<style lang="scss" scoped>
    .raffle-box {
        color: #dd6b20;
        text-align: center;
        font-family: sans-serif;
    }

    .winner-header {
        font-size: 3rem;
        font-weight: 800;
        margin: 0;
        letter-spacing: 0.2rem;
        text-transform: uppercase;
    }

    .winner-box {
        display: inline-block;
        min-width: 40rem;
        min-height: 3rem;
    }

    .start-raffling {
        color: #dd6b20;
    }

    .start-raffling, .winner-box {
        text-transform: uppercase;
        font-size: 2rem;
        letter-spacing: 0.2rem;
        background: white;
        border: 4px solid #dd6b20;
        font-weight: 800;
        padding: 0.8rem 2.4rem;
    }

    .lists {
        display: flex;

        .running-list, .eliminated-list {
            width: 50%;
            line-height: 1.5;
            font-size: 1.4rem;
            letter-spacing: 0.2rem;

            h2 {
                font-size: 2rem;
                text-transform: uppercase;
            }
        }
    }


</style>
