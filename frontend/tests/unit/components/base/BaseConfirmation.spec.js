import { mount, createLocalVue } from "@vue/test-utils";
import BaseConfirmation from "@/components/base/BaseConfirmation.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseConfirmation.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseConfirmation, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call open method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })
    test('call agree method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.agree = jest.fn();
        wrapper.vm.agree();
        expect(wrapper.vm.agree.mock.calls.length).toBe(1);
    })
    test('call cancel method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.cancel = jest.fn();
        wrapper.vm.cancel();
        expect(wrapper.vm.cancel.mock.calls.length).toBe(1);
    })
})
