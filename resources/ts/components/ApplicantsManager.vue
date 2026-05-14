<template>
    <form @submit.prevent="$emit('submit')">
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
                <div class="flex flex-col flex-1">
                    <span class="sr-only" v-text="applicant.name"/>
                    <input :aria-label="`Applicant ${applicant.id} name input`"
                           :id="`applicant-input-${applicant.id}`"
                           @blur="updateApplicant(applicant)"
                           @input="debounce(() => updateApplicant(applicant))"
                           type="text"
                           class="truncate mr-2 flex-1 text-orange-900"
                           v-model="applicant.name"/>
                    <p class="sr-only" v-text="applicant.email || '<No email given>'"/>
                    <input :aria-label="`Applicant ${applicant.id} email input`"
                           :id="`applicant-email-input-${applicant.id}`"
                           @blur="updateApplicant(applicant)"
                           @input="debounce(() => updateApplicant(applicant))"
                           type="text"
                           class="truncate mr-2 flex-1 text-orange-700"
                           placeholder="<No email given>"
                           v-model="applicant.email"/>
                </div>
                <button type="button"
                        class="mx-1 w-12 border-red-300 border text-red-600 hover:text-white hover:bg-red-500"
                        :id="`delete-applicant-${applicant.id}`"
                        @click="deleteApplicant(applicant)">
                    <i class="fa fa-trash-o"
                       aria-label="delete" aria-hidden="true"/>
                </button>
            </li>
        </ul>
    </form>
</template>

<script setup lang="ts">
    import {ref} from 'vue';
    import {IApplicant} from '../types';
    import axios from 'axios';
    import lodashDebounce from 'lodash.debounce';

    const applicants = ref<IApplicant[]>([]);
    const newApplicantName = ref('');
    const debounce = lodashDebounce((cb: () => void) => cb(), 150);

    axios.get<IApplicant[]>('/applicants').then((response) => {
        applicants.value = response.data;
    });

    async function addNewApplicant() {
        const response = await axios.post<IApplicant>('/applicants', {name: newApplicantName.value});
        applicants.value.unshift(response.data);
        newApplicantName.value = '';
    }

    async function updateApplicant(applicant: IApplicant) {
        await axios.put(`/applicants/${applicant.id}`, {name: applicant.name, email: applicant.email});
    }

    async function deleteApplicant(target: IApplicant) {
        await axios.delete(`/applicants/${target.id}`);
        applicants.value = applicants.value.filter((applicant) => applicant.id != target.id);
    }
</script>
