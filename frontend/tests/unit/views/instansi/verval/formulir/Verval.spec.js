import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import Verval from "@/views/instansi/verval/formulir/Verval.vue";
import { getDeepObj, } from '@utils/format';
import BaseModalFull from "@/components/base/BaseModalFull.vue";

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

config.showDeprecationWarnings = false;

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        luringLaporanVerval: {
            namespaced: true,
            actions: {
                getListKelas() {
                    return true
                },
                getDetailPesertaNilai() {
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

describe('Verval', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(Verval, {
            localVue,
            vuetify,
            router,
            store,
            stubs: {
                Viewer: true,
                PopupPreviewDetail: true
            },
            components: {
                BaseModalFull
            },
            methods: {
                onPreview() {
                    return true
                }
            },
            data() {
                return {
                    tabItems: [
                        { value: 'peserta', kPetugas: 0, text: 'Peserta' },
                        { value: 'admin', kPetugas: 4, text: 'Admin Kelas' },
                        { value: 'pembimbing-praktik', kPetugas: 3, text: 'Pembimbing Praktik' },
                        { value: 'pengajar', kPetugas: 1, text: 'Pengajar' },
                        { value: 'pengajar-tambahan', kPetugas: 2, text: 'Pengajar Tambahan' },
                    ],
                }
            }
        })
    }

    test('test initial tab Peserta', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: {
                value: 'peserta', kPetugas: 0, text: 'Peserta'
            }
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        const tab0 = wrapper.find('[id="tab"]')
        tab0.trigger('click')
    })

    test('test initial tab Admin Kelas', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: {
                value: 'admin', kPetugas: 4, text: 'Admin Kelas'
            }
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        const tab4 = wrapper.find('[id="tab"]')
        tab4.trigger('click')
    })

    test('test initial tab Pembimbing Praktik', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: {
                value: 'pembimbing-praktik', kPetugas: 3, text: 'Pembimbing Praktik'
            }
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        const tab3 = wrapper.find('[id="tab"]')
        tab3.trigger('click')
    })

    test('test initial tab Pengajar', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: {
                value: 'pengajar', kPetugas: 1, text: 'Pengajar'
            }
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        const tab1 = wrapper.find('[id="tab"]')
        tab1.trigger('click')
    })

    test('test initial tab Pengajar Tambahan', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: {
                value: 'pengajar-tambahan', kPetugas: 2, text: 'Pengajar Tambahan'
            }
        })
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        const tab2 = wrapper.find('[id="tab"]')
        tab2.trigger('click')
    })

    test('test preview button', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        const preview = wrapper.find('.mdi-eye')
        preview.trigger('click')
        wrapper.vm.onPreview()
    })

    test('test download button', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn()
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        const download = wrapper.find('.mdi-download')
        download.trigger('click')
    })
})
