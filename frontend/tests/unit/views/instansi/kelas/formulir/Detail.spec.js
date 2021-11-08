import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Detail from "@/views/instansi/kelas/formulir/Detail.vue";
import { getDeepObj, localDate, } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        petugasKelas: {
            namespaced: true,
            actions: {
                getListKelas() {
                    return true
                },
            }
        }
    },
})
localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
    }
})
describe('Detail.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Detail, {
            localVue,
            vuetify,
            router,
            store,
            data() {
                return {
                    Datatables: false
                }
            }
        })
    }
    test('call fetch method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.fetch = jest.fn();
        wrapper.vm.fetch();
        expect(wrapper.vm.fetch.mock.calls.length).toBe(1);
    })
    test('call onReload method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReload = jest.fn();
        wrapper.vm.onReload();
        expect(wrapper.vm.onReload.mock.calls.length).toBe(1);
    })
    test('call reset method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);
    })
    test('render data tables', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({ Datatables: true });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="detail-data"]').exists()).toBe(true);
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('[data-testid="search-detail"]')
        wrapper.setData({ search: 'ERLINA' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.search).toBe('ERLINA')
    })
    test('click search button and clear input', () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify').trigger("click");
        const textInput = wrapper.find('[data-testid="search-detail"]')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.search).toBe('')
    })
})