import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import InfoPeserta from "@/views/instansi/peserta/formulir/InfoPeserta.vue";
import { getDeepObj, assetsUrl, localDate } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

config.showDeprecationWarnings = false;

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
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

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        preferensi: {
            namespaced: true,
            actions: {
                instansi() {
                    return true
                },
            }
        },
    },
})

describe('InfoPeserta.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(InfoPeserta, {
            localVue,
            vuetify,
            router,
            store,
            data() {
                return {
                    preview: {
                        title: "Sertifikat / Ijazah",
                        url: "https://upload.dev.siap.id/gpo/paud/peserta-nonptk/2/4-sertifikat-211111011617.png"
                    }
                }
            },
            propsData: {
                initValue: {
                    k_diklat_paud: 3,
                }
            },
            stubs: {
                Viewer: true,
                PopupPreviewDetail: true
            },
            methods: {
                onDetilBerkas() {
                    return true
                }
            }
        })
    }

    test('test detail button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        const detail = wrapper.find('.mdi-eye')
        detail.trigger('click')
        wrapper.vm.onDetilBerkas()
    })

    test('test download button', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn()
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        const download = wrapper.find('.mdi-download')
        download.trigger('click')
    })

})
