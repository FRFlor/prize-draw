import {shallowMount} from '@vue/test-utils';
import MockAdapter from 'axios-mock-adapter';
import axios from 'axios';
import flushPromises from 'flush-promises';
import JoinRaffle from './JoinRaffle.vue';

describe('Registration', () => {
    let wrapper: ReturnType<typeof shallowMount>;
    let mockBackend: MockAdapter;
    let assignSpy: ReturnType<typeof vi.fn>;

    beforeEach(async () => {
        assignSpy = vi.fn();
        Object.defineProperty(window, 'location', {
            configurable: true,
            value: {assign: assignSpy},
        });
        wrapper = shallowMount(JoinRaffle);
        mockBackend = new MockAdapter(axios);
        mockBackend.onPost('/applicants').reply(201);
    });

    it('Allows a user to join the raffle', async () => {
        const applicantName = 'Nick Down';
        const applicantEmail = 'n.down@vehikl.com';

        await wrapper.find('#name').setValue(applicantName);
        await wrapper.find('#email').setValue(applicantEmail);
        await wrapper.find('form').trigger('submit');
        await flushPromises();

        expect(JSON.parse(mockBackend.history.post[0].data)).toEqual({
            name: applicantName,
            email: applicantEmail,
        });
    });

    it('Redirects to raffle list page upon successful register', async () => {
        const applicantName = 'Nick Down';
        const applicantEmail = 'n.down@vehikl.com';

        await wrapper.find('#name').setValue(applicantName);
        await wrapper.find('#email').setValue(applicantEmail);
        await wrapper.find('form').trigger('submit');
        await flushPromises();

        expect(JSON.parse(mockBackend.history.post[0].data)).toEqual({
            name: applicantName,
            email: applicantEmail,
        });
        expect(assignSpy).toHaveBeenCalledWith('/');
    });

    it('Prevents submission until name and email have been provided', async () => {
        expect(wrapper.find('button[type="submit"]').attributes().disabled).toBeDefined();

        await wrapper.find('#name').setValue('Dan');
        await wrapper.find('#email').setValue('Dan@TheMan.com');

        expect(wrapper.find('button[type="submit"]').attributes().disabled).toBeUndefined();
    });
});
