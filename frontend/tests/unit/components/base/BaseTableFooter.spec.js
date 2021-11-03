import { mount, createLocalVue } from "@vue/test-utils";
import BaseTableFooter from "@/components/base/BaseTableFooter.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseTableFooter.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseTableFooter, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call next method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.next = jest.fn();
        wrapper.vm.next();
        expect(wrapper.vm.next.mock.calls.length).toBe(1);
    })
    test('call prev method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.prev = jest.fn();
        wrapper.vm.prev();
        expect(wrapper.vm.prev.mock.calls.length).toBe(1);
    })
    test('call changePage method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.changePage = jest.fn();
        wrapper.vm.changePage();
        expect(wrapper.vm.changePage.mock.calls.length).toBe(1);
    })
})
