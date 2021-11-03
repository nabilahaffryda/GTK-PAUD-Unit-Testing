import { mount, createLocalVue } from "@vue/test-utils";
import BaseListPopup from "@/components/base/BaseListPopup.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListPopup.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseListPopup, {
            localVue,
            vuetify,
            router,
            computed: {
                selected() {
                    return true
                }
            },
        });
    }
    test('call fetchData method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.fetchData = jest.fn();
        wrapper.vm.fetchData();
        expect(wrapper.vm.fetchData.mock.calls.length).toBe(1);
    })
    test('call open method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.open = jest.fn();
        wrapper.vm.open();
        expect(wrapper.vm.open.mock.calls.length).toBe(1);
    })
    test('call onSelect method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSelect = jest.fn();
        wrapper.vm.onSelect();
        expect(wrapper.vm.onSelect.mock.calls.length).toBe(1);
    })
    test('call onSaveSelection method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSaveSelection = jest.fn();
        wrapper.vm.onSaveSelection();
        expect(wrapper.vm.onSaveSelection.mock.calls.length).toBe(1);
    })
})