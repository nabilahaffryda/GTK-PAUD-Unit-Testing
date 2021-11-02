import { createLocalVue, mount } from "@vue/test-utils";
import ResetPendaftaran from "@/components/popup/ResetPendaftaran.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('ResetPendaftaran.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(ResetPendaftaran, {
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
    test('call close method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.close = jest.fn();
        wrapper.vm.close();
        expect(wrapper.vm.close.mock.calls.length).toBe(1);
    })
    test('call onReset method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReset = jest.fn();
        wrapper.vm.onReset();
        expect(wrapper.vm.onReset.mock.calls.length).toBe(1);
    })
})
