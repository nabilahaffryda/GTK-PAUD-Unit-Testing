import { mount, createLocalVue } from "@vue/test-utils";
import BaseBreadcrumbs from "@/components/base/BaseBreadcrumbs.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseBreadcrumbs.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseBreadcrumbs, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call toLink method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.toLink = jest.fn();
        wrapper.vm.toLink();
        expect(wrapper.vm.toLink.mock.calls.length).toBe(1);
    })
})
