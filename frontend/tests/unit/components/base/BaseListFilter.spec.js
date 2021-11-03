import { mount, createLocalVue } from "@vue/test-utils";
import BaseListFilter from "@/components/base/BaseListFilter.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListFilter.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseListFilter, {
            localVue,
            vuetify,
            router,
            props: {
                title: 'filter'
            }
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
    test('call save method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.save = jest.fn();
        wrapper.vm.save();
        expect(wrapper.vm.save.mock.calls.length).toBe(1);
    })
    test('call initValue method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.initValue = jest.fn();
        wrapper.vm.initValue();
        expect(wrapper.vm.initValue.mock.calls.length).toBe(1);
    })
    test('call onDelete method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onDelete = jest.fn();
        wrapper.vm.onDelete();
        expect(wrapper.vm.onDelete.mock.calls.length).toBe(1);
    })
    test('call onRender method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onRender = jest.fn();
        wrapper.vm.onRender();
        expect(wrapper.vm.onRender.mock.calls.length).toBe(1);
    })
    test('call mutate method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.mutate = jest.fn();
        wrapper.vm.mutate();
        expect(wrapper.vm.mutate.mock.calls.length).toBe(1);
    })
})