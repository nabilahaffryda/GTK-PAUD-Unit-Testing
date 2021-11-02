import { createLocalVue, mount } from "@vue/test-utils";
import Selector from "@/components/popup/Selector.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Selector.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(Selector, {
            localVue,
            vuetify,
            router,
            props: {
                fetch: 'string',
                valueId: 'value',
            },
        });
    }
    test('call close method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.close = jest.fn();
        wrapper.vm.close();
        expect(wrapper.vm.close.mock.calls.length).toBe(1);
    })
    test('call selectAll method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.selectAll = jest.fn();
        wrapper.vm.selectAll();
        expect(wrapper.vm.selectAll.mock.calls.length).toBe(1);
    })
    test('call select method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.select = jest.fn();
        wrapper.vm.select();
        expect(wrapper.vm.select.mock.calls.length).toBe(1);
    })
    test('call fetchData method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.fetchData = jest.fn();
        wrapper.vm.fetchData();
        expect(wrapper.vm.fetchData.mock.calls.length).toBe(1);
    })
    test('call onReload method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.onReload();
        expect(wrapper.vm.onReload.mock.calls.length).toBe(1);
    })
    test('call onSearch method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSearch = jest.fn();
        wrapper.vm.onSearch();
        expect(wrapper.vm.onSearch.mock.calls.length).toBe(1);
    })
    test('call onChangePage method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onChangePage = jest.fn();
        wrapper.vm.onChangePage();
        expect(wrapper.vm.onChangePage.mock.calls.length).toBe(1);
    })
    test('call onSave method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSave = jest.fn();
        wrapper.vm.onSave();
        expect(wrapper.vm.onSave.mock.calls.length).toBe(1);
    })
})
