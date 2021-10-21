import { mount, createLocalVue } from "@vue/test-utils";
import BaseDatePicker from "@/components/base/BaseDatePicker.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseDatePicker.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render date picker', () => {
        const VDialog = {
            props: ['value',
                'label',
                'typeDate',
                'min',
                'max',
                'errors',
                'errorMessages',
                'disabled',
                'useicon',
                'dense',
                'singleLine',
                'outlined',
                'placeholder',
                'useAppendIcon',],
            template: '<div><slot :label="label" /></div>'
        }

        const wrapper = mount(BaseDatePicker, {
            localVue,
            vuetify,
            stubs: {
                VDialog
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})