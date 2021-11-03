import { mount, createLocalVue } from "@vue/test-utils";
import BaseTransfer from "@/components/base/BaseTransfer.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import BaseListTable from "@/components/base/BaseListTable.vue";
import BaseTableFooter from "@/components/base/BaseTableFooter.vue";

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseTransfer.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(BaseTransfer, {
            localVue,
            vuetify,
            router,
            props: {
                title: 'transfer',
                keyAttr: 'key1',
            },
            stubs: {
                BaseListTable, BaseTableFooter
            }
        });
    }
    test('call fetchData method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.fetchData = jest.fn();
        wrapper.vm.fetchData();
        expect(wrapper.vm.fetchData.mock.calls.length).toBe(1);
    })
    test('call pickAll method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.pickAll = jest.fn();
        wrapper.vm.pickAll();
        expect(wrapper.vm.pickAll.mock.calls.length).toBe(1);
    })
    test('call deleteAll method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.deleteAll = jest.fn();
        wrapper.vm.deleteAll();
        expect(wrapper.vm.deleteAll.mock.calls.length).toBe(1);
    })
    test('call onChangePageData method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onChangePageData = jest.fn();
        wrapper.vm.onChangePageData();
        expect(wrapper.vm.onChangePageData.mock.calls.length).toBe(1);
    })
    test('call onChangePageTarget method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onChangePageTarget = jest.fn();
        wrapper.vm.onChangePageTarget();
        expect(wrapper.vm.onChangePageTarget.mock.calls.length).toBe(1);
    })
    test('call onSelect method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSelect = jest.fn();
        wrapper.vm.onSelect();
        expect(wrapper.vm.onSelect.mock.calls.length).toBe(1);
    })
    test('call onDelete method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onDelete = jest.fn();
        wrapper.vm.onDelete();
        expect(wrapper.vm.onDelete.mock.calls.length).toBe(1);
    })
})
