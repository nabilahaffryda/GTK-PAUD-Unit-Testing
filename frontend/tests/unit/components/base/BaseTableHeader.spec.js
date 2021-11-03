import { mount, createLocalVue } from "@vue/test-utils";
import BaseTableHeader from "@/components/base/BaseTableHeader.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseTableHeader.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseTableHeader, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call search method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.search = jest.fn();
        wrapper.vm.search();
        expect(wrapper.vm.search.mock.calls.length).toBe(1);
    })
    test('call filter method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.filter = jest.fn();
        wrapper.vm.filter();
        expect(wrapper.vm.filter.mock.calls.length).toBe(1);
    })
    test('call reload method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.reload = jest.fn();
        wrapper.vm.reload();
        expect(wrapper.vm.reload.mock.calls.length).toBe(1);
    })
    test('call add method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.add = jest.fn();
        wrapper.vm.add();
        expect(wrapper.vm.add.mock.calls.length).toBe(1);
    })
    test('call download method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.download = jest.fn();
        wrapper.vm.download();
        expect(wrapper.vm.download.mock.calls.length).toBe(1);
    })
    test('call upload method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.upload = jest.fn();
        wrapper.vm.upload();
        expect(wrapper.vm.upload.mock.calls.length).toBe(1);
    })
    test('call mutate method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.mutate = jest.fn();
        wrapper.vm.mutate();
        expect(wrapper.vm.mutate.mock.calls.length).toBe(1);
    })
})
