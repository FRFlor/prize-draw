import {shallowMount, Wrapper} from '@vue/test-utils';
import MockAdapter from 'axios-mock-adapter';
import axios from 'axios';
import flushPromises from 'flush-promises';
import JoinRaffle from './JoinRaffle.vue';

describe('Registration', () => {
    let wrapper: Wrapper<JoinRaffle>;
    let mockBackend: MockAdapter;

    beforeEach(async () => {
        wrapper = shallowMount(JoinRaffle);
        wrapper.setMethods({redirectTo: jest.fn()});
        mockBackend = new MockAdapter(axios);
        mockBackend.onPost('/join').reply(201);
    });

    it('Allows a user to join the raffle', async () => {
        const applicantName = 'Nick Down';
        const applicantEmail = 'n.down@vehikl.com';

        wrapper.find('#name').setValue(applicantName);
        wrapper.find('#email').setValue(applicantEmail);
        wrapper.find('form').trigger('submit');
        await flushPromises();

        expect(JSON.parse(mockBackend.history.post[0].data)).toEqual(
            {
                name: applicantName,
                email: applicantEmail
            });
    });

    it('Redirects to raffle list page upon successful register', async () => {
        const applicantName = 'Nick Down';
        const applicantEmail = 'n.down@vehikl.com';

        wrapper.find('#name').setValue(applicantName);
        wrapper.find('#email').setValue(applicantEmail);
        wrapper.find('form').trigger('submit');
        await flushPromises();

        expect(JSON.parse(mockBackend.history.post[0].data)).toEqual(
            {
                name: applicantName,
                email: applicantEmail
            });
        expect(wrapper.vm['redirectTo']).toHaveBeenCalledWith('/');
    });

    it('Prevents submission until name and email have been provided', async () => {
       expect(wrapper.find('button[type="submit"]').attributes().disabled).toBeTruthy();

        wrapper.find('#name').setValue('Dan');
        wrapper.find('#email').setValue('Dan@TheMan.com');
        await wrapper.vm.$nextTick();

        expect(wrapper.find('button[type="submit"]').attributes().disabled).toBeFalsy();
    });
});
