import { createLocalVue, mount } from "@vue/test-utils";
import Switch from "@/components/popup/Switch.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('Switch.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    const wrapper = mount(Switch, {
        localVue,
        vuetify,
        router,
    });

    test('call open method', () => {
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })

    test('call select method', () => {
        wrapper.vm.select = jest.fn();
        wrapper.vm.select();
        expect(wrapper.vm.select.mock.calls.length).toBe(1);
    })

    test('call fetchData function', () => {
        wrapper.vm.fetchData = jest.fn();
        wrapper.vm.fetchData();
        expect(wrapper.vm.fetchData.mock.calls.length).toBe(1);
    })

    expect(wrapper).toMatchSnapshot();
})