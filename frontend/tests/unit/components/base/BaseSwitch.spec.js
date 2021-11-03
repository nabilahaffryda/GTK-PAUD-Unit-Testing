import { mount, createLocalVue } from "@vue/test-utils";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseSwitch.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseSwitch, {
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
    test('call select method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.select = jest.fn();
        wrapper.vm.select();
        expect(wrapper.vm.select.mock.calls.length).toBe(1);
    })
})