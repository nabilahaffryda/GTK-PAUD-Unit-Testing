import { mount, createLocalVue } from "@vue/test-utils";
import BaseRender from "@/components/base/BaseRender.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseRender.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseRender, {
            localVue,
            vuetify,
            router,
            render: () => { }
        });
    }
    test('test base render', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.render = jest.fn();
        wrapper.vm.render();
        expect(wrapper.vm.render.mock.calls.length).toBe(1);
    })

})