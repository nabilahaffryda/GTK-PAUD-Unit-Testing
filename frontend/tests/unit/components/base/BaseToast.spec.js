import { mount, createLocalVue } from "@vue/test-utils";
import BaseToast from "@/components/base/BaseToast.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseToast.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseToast, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call reset method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);
    })
    test('call close method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.close = jest.fn();
        wrapper.vm.close();
        expect(wrapper.vm.close.mock.calls.length).toBe(1);
    })
    test('call show method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.show = jest.fn();
        wrapper.vm.show();
        expect(wrapper.vm.show.mock.calls.length).toBe(1);
    })
    test('call dismiss method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.dismiss = jest.fn();
        wrapper.vm.dismiss();
        expect(wrapper.vm.dismiss.mock.calls.length).toBe(1);
    })
})
