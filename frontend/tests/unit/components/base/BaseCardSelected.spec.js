import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardSelected from "@/components/base/BaseCardSelected.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseCardSelected.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseCardSelected, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call onClick method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onClick = jest.fn();
        wrapper.vm.onClick();
        expect(wrapper.vm.onClick.mock.calls.length).toBe(1);
    })
})
