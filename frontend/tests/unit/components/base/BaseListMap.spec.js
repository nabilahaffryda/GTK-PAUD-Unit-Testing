import { mount, createLocalVue } from "@vue/test-utils";
import BaseListMap from "@/components/base/BaseListMap.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListMap.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseListMap, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call onInfoWindow method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onInfoWindow = jest.fn();
        wrapper.vm.onInfoWindow();
        expect(wrapper.vm.onInfoWindow.mock.calls.length).toBe(1);
    })
})