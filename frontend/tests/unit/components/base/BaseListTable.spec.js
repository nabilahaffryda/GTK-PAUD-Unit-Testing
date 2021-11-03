import { mount, createLocalVue } from "@vue/test-utils";
import BaseListTable from "@/components/base/BaseListTable.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListTable.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseListTable, {
            localVue,
            vuetify,
            router,
        });
    }
    test('call fetch method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.fetch = jest.fn();
        wrapper.vm.fetch();
        expect(wrapper.vm.fetch.mock.calls.length).toBe(1);
    })
})
