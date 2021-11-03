import { mount, createLocalVue } from "@vue/test-utils";
import BaseDatePicker from "@/components/base/BaseDatePicker.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseDatePicker.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseDatePicker, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call mutate method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.mutate = jest.fn();
        wrapper.vm.mutate();
        expect(wrapper.vm.mutate.mock.calls.length).toBe(1);
    })
    test('call setForm method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.setForm = jest.fn();
        wrapper.vm.setForm();
        expect(wrapper.vm.setForm.mock.calls.length).toBe(1);
    })
})