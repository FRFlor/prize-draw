<template>
    <div class="raffle-box">
        <button v-if="true"  :disabled="raffleInterval" class="start-raffling mb-4" @click="startRaffling">Start Raffling!</button>
        <div v-else>Please wait for the raffle!</div>

        <div class="row">
            <div class="applicant-name col-sm-4 my-3"
                 v-for="(applicant, index) in applicants"
                 :key="index">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{applicant}}</h5>
                    </div>
                </div>
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
            const response = await axios.get<string[]>('/webstorm/applicants');
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

<style lang="scss" scoped>
    h5 {
        margin: 0;
    }

    .winner-box {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: hsla(0, 0, 0, 0.5);
        color: deepskyblue;
        font-size: 5rem;
        text-shadow: 2px 2px #1b1e21;
        font-weight: bolder;
    }
</style>
