import { mount, createLocalVue } from "@vue/test-utils";
import Vuetify from "vuetify";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex"
import Berkas from "@/views/instansi/verval/formulir/Berkas.vue";
import { getDeepObj, arrayToObject } from '@utils/format'

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
        verval: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                getDetail() {
                    return true
                },
                action() {
                    return true
                },
                downloadList() {
                    return true
                },
                getKinerja() {
                    return true
                },
                getTimVerval() {
                    return true
                },
            }
        },
        preferensi: {
            namespaced: true
        }
    },
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $mapForMaster(data, text = false) {
            // cek type data
            let temp = [];
            if (data && isObject(data)) {
                for (let key in data) {
                    temp.push({
                        text: data[key],
                        value: text ? data[key] : Number(key),
                    });
                }
            } else if (data && isArray(data)) {
                temp = data.map((value, idx) => {
                    return {
                        text: isObject(value) ? value?.keterangan ?? value.text : value,
                        value: isObject(value) ? value[text] ?? value?.value ?? idx : value,
                    };
                });
            }
            return temp;
        },
        $arrToObj(array, key) {
            if (!array || !array.length) return {};
            return arrayToObject(array, key);
        },
    }
})

describe('Berkas.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Berkas, {
            localVue,
            vuetify,
            router,
            store,
            stubs: {
                Viewer: true
            },
        })
    }
    test('test SETUJU button', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-check').exists()).toBe(true)
        const btn = wrapper.find('.mdi-check')
        btn.trigger('click')
        wrapper.vm.onSelected()
    })
    test('test PERBAIKAN button', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-pencil').exists()).toBe(true)
        const button = wrapper.find('.mdi-pencil')
        button.trigger('click')
        wrapper.vm.onSelected()
    })
    test('test TOLAK button', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-close').exists()).toBe(true)
        const btn = wrapper.find('.mdi-close')
        btn.trigger('click')
        wrapper.vm.onSelected()
    })


})
