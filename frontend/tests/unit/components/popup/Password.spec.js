import { mount, createLocalVue } from "@vue/test-utils";
import Password from "@/components/popup/Password.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Password.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(Password, {
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
    test('call submit method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.submit = jest.fn();
        wrapper.vm.submit();
        expect(wrapper.vm.submit.mock.calls.length).toBe(1);
    })
    test('call clear method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.clear = jest.fn();
        wrapper.vm.clear();
        expect(wrapper.vm.clear.mock.calls.length).toBe(1);
    })
})
