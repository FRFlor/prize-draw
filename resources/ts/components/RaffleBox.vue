<template>
    <div class="raffle-box">
        <div class="upper mb-20">
            <button v-show="!isRaffling && !isShowingWinner" class="start-raffling" @click="startRaffling">Start
                Raffling!
            </button>
            <h2 v-show="isRaffling || isShowingWinner" class="winner-header">Winner</h2>
            <div class="winner-box" v-show="isRaffling || isShowingWinner" v-text="winnerName || '???'"/>
        </div>

        <div class="lists">
            <div class="running-list" v-show="! isShowingWinner">
                <h2 class="font-bold mb-6">In The Running</h2>
                <transition-group name="running-applicants" tag="div">
                    <div class="applicant-name"
                         v-for="applicant in applicants"
                         :key="applicant.id">
                        {{applicant.name}}
                    </div>
                </transition-group>
            </div>

            <div class="drawn-names-list">
                <h2 class="font-bold mb-6">Names Drawn</h2>
                <transition-group name="drawn-applicants" tag="div">
                    <div class="applicant-name"
                         :style="`font-size: ${getFontSize(index)}rem;`"
                         v-for="(applicant, index) in eliminatedApplicants"
                         :key="applicant.id">
                        {{applicants.length + index + 1}}. {{applicant.name}}
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import {ref, computed, onMounted} from 'vue';
    import axios from 'axios';
    import {getWaitTime} from '../classes/Timer';
    import {IApplicant} from '../types';

    const applicants = ref<IApplicant[]>([]);
    const eliminatedApplicants = ref<IApplicant[]>([]);
    const isRaffling = ref(false);
    const winnerName = ref('');

    const isShowingWinner = computed(() => !!winnerName.value);

    onMounted(async () => {
        const response = await axios.get<IApplicant[]>('/applicants');
        applicants.value = response.data;
    });

    function pullAnotherName() {
        setTimeout(() => {
            const [pickedApplicant] = applicants.value.splice(Math.floor(Math.random() * applicants.value.length), 1);

            if (pickedApplicant) {
                eliminatedApplicants.value.unshift(pickedApplicant);
            }

            if (applicants.value.length > 0) {
                pullAnotherName();
                return;
            }

            if (applicants.value.length === 0) {
                setTimeout(() => {
                    winnerName.value = eliminatedApplicants.value[0].name;
                    isRaffling.value = false;
                }, 1000);
            }
        }, getWaitTime(eliminatedApplicants.value.length, eliminatedApplicants.value.length + applicants.value.length));
    }

    function startRaffling() {
        isRaffling.value = true;
        pullAnotherName();
    }

    function getFontSize(index: number) {
        const cutOutPoint = 5;
        const max = window.innerWidth >= 750 ? 3 : 1.5;
        const min = window.innerWidth >= 750 ? 1 : 0.5;
        const power = 2;

        if (index >= cutOutPoint) {
            return min;
        }
        return max - (max - min) * Math.pow(index, power) / Math.pow(cutOutPoint, power);
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
        margin-bottom: 2rem;

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
