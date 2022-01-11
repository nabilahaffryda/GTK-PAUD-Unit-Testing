import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Index from "@/views/instansi/verval/laporanPelaksanaan/Index.vue";
import { getDeepObj, isObject, isArray } from '@utils/format';
import BaseModalFull from "@/components/base/BaseModalFull.vue";

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
                masters() {
                    return true
                },
            }
        },
        preferensi: {
            namespaced: true,
            actions: {
                akun() {
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
        luringLaporanVerval: {
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
            }
        },
    },
})

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $allow() {
            return true;
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
        $isObject(data) {
            return isObject(data || {});
        },
        $isArray(data) {
            return isArray(data);
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
            vuetify,
            router,
            store,
            stubs: {
                Viewer: true
            },
            components: {
                BaseModalFull
            },
        })
    }

    test('test reload button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-reload').exists()).toBe(true)
        const reload = wrapper.find('.mdi-reload')
        reload.trigger('click')
        wrapper.vm.onReload()
    })

    test('test filter button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-filter-variant').exists()).toBe(true)
        const filter = wrapper.find('.mdi-filter-variant')
        filter.trigger('click')
        wrapper.vm.onFilter()
    })

    test('Enter search text and check the value of keyword', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-magnify').exists()).toBe(true)
        wrapper.setData({ keyword: 'Diklat seri GTK PAUD - Kelas Diklat Dasar Angkatan 1 LPD Dummy' })
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
        expect(wrapper.vm.keyword).toBe('Diklat seri GTK PAUD - Kelas Diklat Dasar Angkatan 1 LPD Dummy')
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
