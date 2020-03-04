<template>
    <div class="raffle-box">
        <div class="upper">
            <button v-show="!isRaffling && !isShowingWinner" class="start-raffling" @click="startRaffling">Start
                Raffling!
            </button>
            <h2 v-show="isRaffling || isShowingWinner" class="winner-header">Winner</h2>
            <div class="winner-box" v-show="isRaffling || isShowingWinner" v-text="winnerName || '???'"/>
        </div>

        <transition-group name="lists" class="lists">
            <div key="running-list" class="running-list" v-show="applicants.length > 0">
                <h2>In The Running</h2>
                <transition-group name="running-applicants">
                    <div class="applicant-name"
                         v-for="applicant in applicants"
                         :key="applicant.id">
                        {{applicant.name}}
                    </div>
                </transition-group>
            </div>

            <div key="drawn-names-list" class="drawn-names-list">
                <h2>Names Drawn</h2>
                <transition-group name="drawn-applicants">
                    <div class="applicant-name"
                         :style="`font-size: ${getFontSize(index)}rem;`"
                         v-for="(applicant, index) in eliminatedApplicants"
                         :key="applicant.id">
                        {{applicants.length + index + 1}}. {{applicant.name}}
                    </div>
                </transition-group>

            </div>
        </transition-group>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';
    import {getWaitTime} from '../classes/Timer';
    import {IApplicant} from '../types';

    @Component
    export default class RaffleBox extends Vue {
        applicants: IApplicant[] = [];
        eliminatedApplicants: IApplicant[] = [];
        isRaffling: boolean = false;
        winnerName: string = '';

        async mounted() {
            const response = await axios.get<IApplicant[]>('/applicants');
            this.applicants = response.data;
        }

        pullAnotherName() {
            setTimeout(() => {
                const [pickedApplicant] = this.applicants.splice(Math.floor(Math.random() * this.applicants.length), 1);

                if (pickedApplicant) {
                    this.eliminatedApplicants.unshift(pickedApplicant);
                }

                if (this.applicants.length > 0) {
                    this.pullAnotherName();
                    return;
                }

                if (this.applicants.length === 0) {
                    this.winnerName = this.eliminatedApplicants[0].name;
                    this.isRaffling = false;
                }
            }, getWaitTime(this.eliminatedApplicants.length, this.eliminatedApplicants.length + this.applicants.length));
        }

        startRaffling() {
            this.isRaffling = true;
            this.pullAnotherName();
        }

        getFontSize(index: number) {
            const cutOutPoint: number = 5;
            const max: number = window.innerWidth >= 750 ? 3 : 1.5;
            const min: number = window.innerWidth >= 750 ? 1 : 0.5;
            const power: number = 2;

            if (index >= cutOutPoint) {
                return min;
            }
            return max - (max - min) * Math.pow(index, power) / Math.pow(cutOutPoint, power);
        }

        get isShowingWinner(): boolean {
            return !!this.winnerName;
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
        width: 80%;
        max-width: 40rem;
        line-height: 1;
    }

    .start-raffling {
        margin-top: 3rem;
        color: #dd6b20;

        &:hover {
            background-color: #dd6b20;
            color: white;
        }
    }

    .start-raffling {
        font-size: 1.5rem;
    }

    .winner-box {
        font-size: 1rem;
    }

    .start-raffling, .winner-box {
        text-transform: uppercase;
        letter-spacing: 0.2rem;
        background: white;
        border: 4px solid #dd6b20;
        font-weight: 800;
        padding: 0.8rem;
    }

    .lists {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column-reverse;

        .running-list, .drawn-names-list {
            width: 100%;
            overflow: hidden;
            line-height: 1.5;
            letter-spacing: 0.2rem;

            h2 {
                font-size: 2rem;
                text-transform: uppercase;
            }
        }
    }

    .running-applicants {
        opacity: 1;
    }

    .running-applicants-leave-to {
        transition: all 2s;
        transform: translateX(50%);
        opacity: 0;
    }

    @media (min-width: 750px) {
        .lists {
            flex-direction: row;
            align-items: start;

            .running-list, .drawn-names-list {
                width: 50%;
                overflow: visible;
            }
        }

        .start-raffling, .winner-box {
            font-size: 2rem;
            padding: 0.8rem 2.4rem;
        }
    }
</style>
