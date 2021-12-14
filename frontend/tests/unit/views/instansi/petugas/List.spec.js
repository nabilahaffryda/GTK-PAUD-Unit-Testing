import { mount, createLocalVue, config } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import List from "@/views/instansi/petugas/List.vue";
import { getDeepObj, isObject, localDate } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue()

document.body.setAttribute('data-app', true)
config.showDeprecationWarnings = false;

localVue.use(VueRouter)
const router = new VueRouter()

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        master: {
            namespaced: true,
            actions: {
                getMasters() {
                    return true
                },
            }
        },
        petugas: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                }
            }
        }
    }
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $isObject(data) {
            return isObject(data || {});
        },
        $allow() {
            return true;
        },
        $fGender(data) {
            return data === 'L' ? 'Laki - laki' : data === 'P' ? 'Perempuan' : '-';
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
    }
})

describe('List.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(List, {
            localVue,
            store,
            vuetify,
            router,
            propsData: {
                title: 'List',
                jenis: 'pengajar',
                actions: [
                    {
                        icon: 'mdi-account',
                        title: 'Data Profil',
                        event: 'onDetail'
                    }
                ],
            },
            stubs: {
                Viewer: true,
            },
            methods: {
                fetchData() {
                    return true
                }
            },
            components: {
                BaseModalFull
            }
        })
    }
    test('test filter button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.findComponent({ ref: 'filter' }).exists()).toBe(true)
        wrapper.vm.$nextTick()
        const filter = wrapper.findComponent({ ref: 'filter' })
        filter.trigger('click')
        wrapper.vm.onFilter()
    })

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
        wrapper.setData({ keyword: 'Bahar' })
        wrapper.vm.$nextTick()
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
        expect(wrapper.vm.keyword).toBe('Bahar')
    })

})
