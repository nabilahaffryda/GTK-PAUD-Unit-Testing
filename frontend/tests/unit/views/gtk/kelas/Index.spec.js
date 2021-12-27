import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Index from "@/views/gtk/kelas/Index.vue";
import { getDeepObj, isObject, } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        diklat: {
            namespaced: true,
            actions: {
                getListKelas() {
                    return true
                },
                getHasil() {
                    return true
                },
            }
        },
    },
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $isObject(data) {
            return isObject(data || {});
        },
    }
})

describe('Index.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Index, {
            localVue,
            vuetify,
            router,
            store,
        })
    }

    test('test reload button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-reload').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const reload = wrapper.find('.mdi-reload')
        reload.trigger('click')
        wrapper.vm.onReload()
    })

    test('Enter search text and check the value of keyword', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-magnify').exists()).toBe(true)
        wrapper.setData({ keyword: 'Kelas A' })
        wrapper.vm.$nextTick()
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
        expect(wrapper.vm.keyword).toBe('Kelas A')
    })

    test('test Jadwal Pelaksanaan field', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({ keyword: '15 - 26 Februari 2021' })
        expect(wrapper.vm.keyword).toBe('15 - 26 Februari 2021')
    })

    test('test Nama Kelas field', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({ keyword: 'Kelas A' })
        expect(wrapper.vm.keyword).toBe('Kelas A')
    })
})
