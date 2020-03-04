import {shallowMount, Wrapper} from '@vue/test-utils';
import RegistrationForm from './RegistrationForm.vue';
import MockAdapter from 'axios-mock-adapter';
import axios from 'axios';
import flushPromises from 'flush-promises';

describe('Registration', () => {
    let wrapper: Wrapper<RegistrationForm>;
    let mockBackend: MockAdapter;

    beforeEach(async () => {
        wrapper = shallowMount(RegistrationForm);
        wrapper.setMethods({redirectTo: jest.fn()});
        mockBackend = new MockAdapter(axios);
        mockBackend.onPost('/register').reply(201);

        wrapper.find('#name').setValue('Nick');
        wrapper.find('form').trigger('submit');
        await flushPromises();
    });

    it('Allows a user to register', () => {
        expect(JSON.parse(mockBackend.history.post[0].data)).toEqual({name: 'Nick'});
    });

    it('Redirects to raffle list page upon successful register', () => {
        expect(wrapper.vm['redirectTo']).toHaveBeenCalledWith('/raffle');
    });
});
