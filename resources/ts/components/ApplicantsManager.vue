<template>
    <form @submit.prevent>
        <ul class="w-full px-8 lg:mx-auto lg:max-w-5xl">
            <li class="applicant py-2 px-4 my-2 border-orange-700 border-2 rounded">
                <input @keyup.enter="debounce(addNewApplicant)"
                       id="new-applicant-input"
                       aria-label="new applicant name"
                       placeholder="Enter new applicant name here..."
                       class="w-full truncate mr-2 flex-1 text-orange-900"
                       v-model="newApplicantName">
            </li>
            <li v-for="applicant in applicants"
                class="applicant py-2 px-4 my-2 border-orange-700 border-2 rounded flex justify-between">
                <span class="sr-only" v-text="applicant.name"/>
                <input :aria-label="`Applicant ${applicant.id} name input`"
                       :id="`applicant-input-${applicant.id}`"
                       @blur="updateApplicant(applicant)"
                       @input="debounce(() => updateApplicant(applicant))"
                       type="text"
                       class="truncate mr-2 flex-1 text-orange-900"
                       v-model="applicant.name"/>
                <section class="controls flex">
                    <button tabindex="-1" class="sr-only"/> <!-- TODO: Figure out why pressing enter while editing causes participant to be deleted without this button here -->
                    <button class="mx-1 w-6 border-red-300 border text-red-600 hover:text-white hover:bg-red-500"
                            :id="`delete-applicant-${applicant.id}`"
                            @click="deleteApplicant(applicant)">
                        <i class="fa fa-trash-o"
                           aria-label="delete" aria-hidden="true"/>
                    </button>
                </section>
            </li>
        </ul>
    </form>
</template>

<script lang=ts>
    import {Component, Vue} from 'vue-property-decorator';
    import {IApplicant} from '../types';
    import axios from 'axios';
    import debounce from 'lodash.debounce';

    @Component({
        methods: {
            debounce: debounce(cb => cb(), 150)
        }
    })
    export default class ApplicantsManager extends Vue {
        applicants: IApplicant[] = [];
        newApplicantName: string = '';

        async created() {
            const response = await axios.get<IApplicant[]>('/applicants');
            this.applicants = response.data;
        }

        async addNewApplicant() {
            const response = await axios.post<IApplicant>('/applicants', {name: this.newApplicantName});
            this.applicants.unshift(response.data);
            this.newApplicantName = '';
        }

        async updateApplicant(applicant: IApplicant) {
            await axios.put(`/applicants/${applicant.id}`, {'name': applicant.name});
        }

        async deleteApplicant(target: IApplicant) {
            await axios.delete(`/applicants/${target.id}`);
            this.applicants = this.applicants.filter((applicant) => applicant.id != target.id);
        }
    }
</script>

<style lang="scss" scoped>
</style>
