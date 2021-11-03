import { mount, createLocalVue } from "@vue/test-utils";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseModalFull.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseModalFull, {
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
    test('call reset method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);
    })
    test('call showError method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.showError = jest.fn();
        wrapper.vm.showError();
        expect(wrapper.vm.showError.mock.calls.length).toBe(1);
    })
    test('call setError method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.setError = jest.fn();
        wrapper.vm.setError();
        expect(wrapper.vm.setError.mock.calls.length).toBe(1);
    })
    test('call save method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.save = jest.fn();
        wrapper.vm.save();
        expect(wrapper.vm.save.mock.calls.length).toBe(1);
    })
    test('call changeTabs method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.changeTabs = jest.fn();
        wrapper.vm.changeTabs();
        expect(wrapper.vm.changeTabs.mock.calls.length).toBe(1);
    })
    test('call onValidate method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onValidate = jest.fn();
        wrapper.vm.onValidate();
        expect(wrapper.vm.onValidate.mock.calls.length).toBe(1);
    })
})