import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import Detail from "@/views/instansi/kelas/Detail.vue";
import { getDeepObj, isObject, } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

document.body.setAttribute('data-app', true)

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
        master: {
            namespaced: true,
            actions: {
                masters() {
                    return true
                },
            }
        },
        petugasKelas: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                },
                getPeserta() {
                    return true
                },
                action() {
                    return true
                },
                fetchPeserta() {
                    return true
                },
                getDetailLuring() {
                    return true
                },
                getDetailPesertaNilai() {
                    return true
                },
                action() {
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
        $isObject(data) {
            return isObject(data || {});
        },
        $allow() {
            return true;
        },
    }
})

describe('Detail.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Detail, {
            localVue,
            store,
            vuetify,
            router,
            components: {
                BaseModalFull
            },
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

    test('test filter button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.findComponent({ ref: 'filter' }).exists()).toBe(true)
        wrapper.vm.$nextTick()
        const filter = wrapper.findComponent({ ref: 'filter' })
        filter.trigger('click')
        wrapper.vm.onFilter()
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
})