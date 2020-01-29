<template>
    <div class="raffle-box">
        <div class="applicant-name"
             v-for="(applicant, index) in applicants"
             :key="index"
             v-text="applicant"/>

        <button class="start-raffling" @click="startRaffling">Start Raffling!</button>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from 'vue-property-decorator';
    import axios from 'axios';

    @Component
    export default class RaffleBox extends Vue {
        applicants: string[] = [];
        raffleInterval;

        async mounted() {
            const response = await axios.get<string[]>('/phpstorm/applicants');
            this.applicants =  response.data;
        }

        beforeDestroy() {
            if (this.raffleInterval) {
                clearInterval(this.raffleInterval);
            }
        }

        startRaffling() {
            this.raffleInterval = setInterval(() => {
                this.applicants.splice(0, 1);

                if (this.applicants.length <= 1) {
                    clearInterval(this.raffleInterval);
                }
            }, 500);
        }



    }
</script>

<style lang="scss" scoped>
</style>
