import { mount, createLocalVue } from "@vue/test-utils";
import BaseTimePicker from "@/components/base/BaseTimePicker.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseTimePicker.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render time picker', () => {
        const VSelect = {
            props: ['value', 'label', 'min', 'max', 'errors', 'disabled', 'useicon', 'dense', 'singleLine', 'outlined'],
            template: '<div><slot :dense="dense" /></div>'
        }

        const wrapper = mount(BaseTimePicker, {
            localVue,
            vuetify,
            stubs: {
                VSelect
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
