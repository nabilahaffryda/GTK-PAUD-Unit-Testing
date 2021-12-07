import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Detail from "@/views/instansi/diklat/formulir/Detail.vue";
import { getDeepObj, localDate } from '@utils/format';
import BaseModalFull from "@/components/base/BaseModalFull";

Vue.use(Vuetify)
const localVue = createLocalVue()

document.body.setAttribute('data-app', true)

localVue.use(VueRouter)
const router = new VueRouter()

localVue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        diklatKelas: {
            namespaced: true,
            actions: {
                getListKelas() {
                    return true
                },
                action() {
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
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
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
            stubs: {
                Viewer: true
            },
            components: {
                BaseModalFull,
            },
            computed: {
                berkas() {
                    return {
                        pesan: "<span class='grey--text'><i>* Silakan unduh template Jadwal dasar <a class=\"blue--text\" href=\"https://files1.simpkb.id/berkas/paud/Template_Jadwal Diklat_Dasar_Rev.docx\" target=\"_blank\">UNDUH DISINI</a> </i> </span>",
                        title: "Jadwal Diklat Dasar",
                        url: "https://upload.dev.siap.id/gpo/paud/kelas-jadwal/720003/7-210915041615-94397/sertif-cgp-angkatan1_tk_plb (2).pdf"
                    }
                }
            },
            data() {
                return {
                    tab: [],
                    tabItems: [
                        { value: 'peserta', kPetugas: 0, text: 'Peserta' },
                        { value: 'admin', kPetugas: 4, text: 'Admin Kelas' },
                        { value: 'pembimbing-praktik', kPetugas: 3, text: 'Pembimbing Praktik' },
                        { value: 'pengajar', kPetugas: 1, text: 'Pengajar' },
                        { value: 'pengajar-tambahan', kPetugas: 2, text: 'Pengajar Tambahan' },
                    ],
                }
            },
        })
    }

    test('test initial tab 1', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    value: 'peserta', kPetugas: 0, text: 'Peserta'
                }
            ]
        })
        expect(wrapper.find('[id="tab-item"]').exists()).toBe(true);
        const tab1 = wrapper.find('[id="tab-item"]')
        tab1.trigger('click')
    })

    test('test initial tab 2', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    value: 'admin', kPetugas: 4, text: 'Admin Kelas'
                }
            ]
        })
        expect(wrapper.find('[id="tab-item"]').exists()).toBe(true);
        const tab2 = wrapper.find('[id="tab-item"]')
        tab2.trigger('click')
    })

    test('test initial tab 3', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    value: 'pembimbing-praktik', kPetugas: 3, text: 'Pembimbing Praktik'
                }
            ]
        })
        expect(wrapper.find('[id="tab-item"]').exists()).toBe(true)
        const tab3 = wrapper.find('[id="tab-item"]')
        tab3.trigger('click')
    })

    test('test initial tab 4', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    value: 'pengajar', kPetugas: 1, text: 'Pengajar'
                }
            ]
        })
        expect(wrapper.find('[id="tab-item"]').exists()).toBe(true)
        const tab4 = wrapper.find('[id="tab-item"]')
        tab4.trigger('click')
    })

    test('test initial tab 5', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            tab: [
                {
                    value: 'pengajar-tambahan', kPetugas: 2, text: 'Pengajar Tambahan'
                }
            ]
        })
        expect(wrapper.find('[id="tab-item"]').exists()).toBe(true)
        const tab5 = wrapper.find('[id="tab-item"]')
        tab5.trigger('click')
    })

    test('test unggah jadwal button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="unggah-jadwal"]').exists()).toBe(true)
        wrapper.vm.$nextTick();
        const unggahJwl = wrapper.find('[id="unggah-jadwal"]')
        unggahJwl.trigger('click')
        wrapper.vm.onUpload()
    })

    test('test download button', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn();
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const download = wrapper.find('.mdi-download')
        download.trigger('click')
    })

    test('test detail button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onPreview = jest.fn()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        wrapper.vm.$nextTick()
        const detail = wrapper.find('.mdi-eye')
        detail.trigger('click')
        wrapper.vm.onPreview()
    })

    test('test add button', () => {
        const wrapper = wrapperFactory()
        wrapper.vm.onAddPetugas = jest.fn()
        expect(wrapper.findComponent({ ref: 'popup' }).exists()).toBe(true)
        wrapper.vm.$nextTick()
        const add = wrapper.findComponent({ ref: 'popup' })
        add.trigger('click')
        wrapper.vm.onAddPetugas()
    })

    // test('test trash button', () => {
    //     const wrapper = wrapperFactory()
    //     expect(wrapper.find('.mdi-trash-can').exists()).toBe(true)
    //     wrapper.vm.$nextTick()
    //     const trash = wrapper.find('.mdi-trash-can')
    //     trash.trigger('click')
    //     wrapper.vm.onDelete()
    // })

    // test('Input keyword and check the value of keyword', () => {
    //     const wrapper = wrapperFactory()
    //     expect(wrapper.find('.mdi-magnify').exists()).toBe(true);
    //     wrapper.setData({ keyword: 'WITA' })
    //     wrapper.find('.mdi-magnify').trigger("click");
    //     wrapper.vm.onSearch();
    //     expect(wrapper.vm.keyword).toBe('WITA')
    // })

    // test('Clear input after search button is clicked', () => {
    //     const wrapper = wrapperFactory();
    //     const textInput = wrapper.find('[id="search"]')
    //     expect(textInput.text()).toMatch('')
    //     expect(wrapper.vm.keyword).toBe('')
    // })
})
