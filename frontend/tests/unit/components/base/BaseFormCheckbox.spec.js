import { mount, createLocalVue } from "@vue/test-utils";
import BaseFormCheckbox from "@/components/base/BaseFormCheckbox.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseFormCheckbox.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render form checkbox', () => {
        const VCheckBox = {
            props: [
                'label',
                'labelColor',
                'required',
                'items',
                'value',
                'disabled',
                'row',
                'margin',
                'fontSize',
                'weight',
                'errorMessages',
                'itemGrid'],
            template: '<div><slot :label="item.text" /></div>'
        }

        const wrapper = mount(BaseFormCheckbox, {
            localVue,
            vuetify,
            stubs: {
                VCheckBox
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})