import {mount} from '@vue/test-utils';
import ExampleComponent from './ExampleComponent.vue';

describe('ExampleComponent', () => {
   it('Works', () => {
      const wrapper = mount(ExampleComponent);
      expect(wrapper.vm).toBeTruthy();
   });
});
