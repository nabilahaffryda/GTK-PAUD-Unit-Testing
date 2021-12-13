import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Index from "@/views/instansi/petugas/Index.vue";
import { getDeepObj, isObject } from '@utils/format';

document.body.setAttribute('data-app', true)

Vue.use(Vuetify)
const localVue = createLocalVue()

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
        $allow() {
            return true;
        },
        $isObject(data) {
            return isObject(data || {});
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

describe('Index.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Index, {
            localVue,
            store,
            vuetify,
            router,
            stubs: {
                Viewer: true,
                ListView: true
            },
            data() {
                return {
                    items: [
                        { tab: 'PPM', params: { k_petugas_paud: 1 }, jenis: 'pengajar' },
                        { tab: 'PPTM', params: { k_petugas_paud: 3 }, jenis: 'pengajar' },
                    ],
                    tab: [],
                }
            },

        })
    }
    test('test initial tab 1', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    tab: 'PPM', params: { k_petugas_paud: 1 }, jenis: 'pengajar'
                }
            ]
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const tab1 = wrapper.find('[id="tab"]')
        tab1.trigger('click')
    })

    test('test initial tab 2', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    tab: 'PPTM', params: { k_petugas_paud: 3 }, jenis: 'pengajar'
                }
            ]
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const tab2 = wrapper.find('[id="tab"]')
        tab2.trigger('click')
    })

})
