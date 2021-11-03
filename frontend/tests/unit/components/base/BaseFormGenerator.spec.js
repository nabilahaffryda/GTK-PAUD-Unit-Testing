import { mount, createLocalVue } from "@vue/test-utils";
import BaseFormGenerator from "@/components/base/BaseFormGenerator.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseFormGenerator.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseFormGenerator, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call updateForm method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.updateForm = jest.fn();
        wrapper.vm.updateForm();
        expect(wrapper.vm.updateForm.mock.calls.length).toBe(1);
    })
    test('call updateFormCascade method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.updateFormCascade = jest.fn();
        wrapper.vm.updateFormCascade();
        expect(wrapper.vm.updateFormCascade.mock.calls.length).toBe(1);
    })
    test('call validate method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.validate = jest.fn();
        wrapper.vm.validate();
        expect(wrapper.vm.validate.mock.calls.length).toBe(1);
    })
})