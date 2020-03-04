<template>
    <div class="raffle-box">
        <div class="upper">
            <button v-show="!isRaffling && !isShowingWinner" class="start-raffling" @click="startRaffling">Start Raffling!</button>
            <h2 v-show="isRaffling || isShowingWinner" class="winner-header">Winner</h2>
            <div class="winner-box" v-show="isRaffling || isShowingWinner" v-text="winnerName || '???'"/>
        </div>

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
                <h2>Names Drawn</h2>
                <div class="applicant-name"
                     v-for="(applicant, index) in eliminatedApplicants"
                     :key="`eliminated-${index}`">
                    {{applicants.length + index + 1}}. {{applicant}}
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';
    import {getWaitTime} from '../classes/Timer';

    @Component
    export default class RaffleBox extends Vue {
        applicants: string[] = [];
        eliminatedApplicants: string[] = [];
        isRaffling: boolean = false;
        winnerName: string = '';

        async mounted() {
            const response = await axios.get<string[]>('/applicants');
            this.applicants = response.data;
        }

        pullAnotherName() {
            setTimeout(() => {
                const [pickedApplicant] = this.applicants.splice(Math.floor(Math.random() * this.applicants.length), 1);

                if (pickedApplicant) {
                    this.eliminatedApplicants.unshift(pickedApplicant);
                }

                if(this.applicants.length > 0) {
                    this.pullAnotherName();
                    return;
                }

                if (this.applicants.length === 0) {
                    this.winnerName = this.eliminatedApplicants[0];
                    this.isRaffling = false;
                }
            }, getWaitTime(this.eliminatedApplicants.length, this.eliminatedApplicants.length + this.applicants.length));
        }

        startRaffling() {
            this.isRaffling = true;
            this.pullAnotherName();
        }

        get isShowingWinner(): boolean {
            return !! this.winnerName;
        }
    }
</script>

<style lang="scss" scoped>
    .raffle-box {
        color: #dd6b20;
        text-align: center;
        font-family: sans-serif;
    }

    .upper {
        min-height: 12rem;
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
        line-height: 1;
    }

    .start-raffling {
        margin-top: 3rem;
        color: #dd6b20;
        &:hover {
            background-color:#dd6b20;
            color: white;
        }
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

        .eliminated-list {
            .applicant-name{
                &:first-child {
                    font-size: 2rem;
                }
            }
        }
    }


</style>
