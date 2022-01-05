import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import Laporan from "@/views/instansi/kelas/Laporan.vue";
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
                getListPesertaLaporan() {
                    return true
                },
                getDetailLuring() {
                    return true
                },
                getDetailPesertaNilai() {
                    return true
                },
                LaporanAction() {
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
        $downloadFile(sUrl) {
            // Creating new link node.
            const link = document.createElement('a');
            link.href = sUrl;

            // Dispatching click event.
            // if (document.createEvent) {
            //   const e = document.createEvent('MouseEvents')
            //   e.initEvent('click', true, true)
            //   link.dispatchEvent(e)
            //   return true
            // }
            window.open(sUrl);
            return true;
        },
    }
})

describe('Laporan.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Laporan, {
            localVue,
            store,
            vuetify,
            router,
            components: {
                BaseModalFull
            },
        })
    }

    test('test unggah laporan button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-upload').exists()).toBe(true)
        const btn = wrapper.find('.mdi-upload')
        btn.trigger('click')
        wrapper.vm.onUploadLaporan()
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
        wrapper.setData({ keyword: 'Joh Lenon' })
        wrapper.vm.$nextTick()
        const search = wrapper.find('.mdi-magnify')
        search.trigger('click')
        wrapper.vm.onSearch()
        expect(wrapper.vm.keyword).toBe('Joh Lenon')
    })

    test('test download button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onDownload = jest.fn();
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        wrapper.vm.onDownload()
    })
})
