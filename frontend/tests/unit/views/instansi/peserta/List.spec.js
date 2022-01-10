import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import List from "@/views/instansi/peserta/List.vue";
import { getDeepObj, isObject } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

document.body.setAttribute('data-app', true)
config.showDeprecationWarnings = false;

localVue.use(VueRouter)
const router = new VueRouter();

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
    }
})

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        master: {
            namespaced: true,
            actions: {
                masters() {
                    return true
                },
            }
        },
        master: {
            namespaced: true,
            actions: {
                getMasters() {
                    return true
                },
            }
        },
        peserta: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                },
                create() {
                    return true
                },
                update() {
                    return true
                },
                delete() {
                    return true
                },
            }
        },
    },
})

describe('List.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(List, {
            localVue,
            vuetify,
            router,
            store,
            stubs: {
                FormPeserta: true
            },
            components: {
                BaseModalFull
            },
            methods: {
                onAdd() {
                    return true
                },
            }
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
        wrapper.setData({ keyword: 'Jojo' })
        wrapper.vm.$nextTick()
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
        expect(wrapper.vm.keyword).toBe('Jojo')
    })

    test('test add button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-plus').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const add = wrapper.find('.mdi-plus')
        add.trigger('click')
        wrapper.vm.onAdd()
    })

    test('test total of data', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            total: 1
        })
        expect(wrapper.find('[id="total"]').exists()).toBe(true)
        expect(wrapper.vm.total).toBe(1)
    })
})
