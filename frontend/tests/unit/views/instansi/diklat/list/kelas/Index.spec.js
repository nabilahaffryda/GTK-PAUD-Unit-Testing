import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import Index from "@/views/instansi/diklat/list/kelas/Index.vue";
import { getDeepObj, localDate, isObject } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

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
        diklatKelas: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                create() {
                    return true
                },
                update() {
                    return true
                },
                action() {
                    return true
                },
                getDetail() {
                    return true
                },
                getMapels() {
                    return true
                },
                downloadList() {
                    return true
                },
            }
        },
        diklat: {
            namespaced: true,
            actions: {
                getDiklat() {
                    return true
                },
            }
        }
    }
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $allow() {
            return true
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
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
            components: {
                BaseModalFull
            }
        })
    }

    test('test reload button', () => {
        const wrapper = wrapperFactory();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const reload = wrapper.find('.mdi-reload')
        reload.trigger('click')
        wrapper.vm.onReload();
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

    test('test save button', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onSave = jest.fn()
        expect(wrapper.findComponent({ ref: 'modal' }).exists()).toBe(true);
        const save = wrapper.findComponent({ ref: 'modal' })
        save.trigger('click')
        wrapper.vm.onSave();
    })

    test('test filter button', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onFilter = jest.fn()
        expect(wrapper.findComponent({ ref: 'filter' }).exists()).toBe(true);
        const filter = wrapper.findComponent({ ref: 'filter' })
        filter.trigger('click')
        wrapper.vm.onFilter();
    })

})
