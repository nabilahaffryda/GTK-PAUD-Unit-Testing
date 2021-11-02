import { mount, createLocalVue } from "@vue/test-utils";
import Notifikasi from "@/components/popup/Notifikasi.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Notifikasi.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Notifikasi, {
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
    test('call onClose method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onClose = jest.fn();
        wrapper.vm.onClose();
        expect(wrapper.vm.onClose.mock.calls.length).toBe(1);
    })
    test('call onAction method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAction = jest.fn();
        wrapper.vm.onAction();
        expect(wrapper.vm.onAction.mock.calls.length).toBe(1);
    })
})
