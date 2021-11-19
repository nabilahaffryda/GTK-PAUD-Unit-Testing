import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue"
import VueRouter from "vue-router"
import Vuetify from "vuetify";
import Vuex from "vuex"
import { getDeepObj, isObject, localDate } from '@utils/format';
import Berkas from "@/views/instansi/profil/formulir/Berkas.vue"

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
        profil: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                update() {
                    return true
                },
                getDiklat() {
                    return true
                },
                getBerkas() {
                    return true
                },
                setBerkas() {
                    return true
                },
                dropBerkas() {
                    return true
                },
                ajuan() {
                    return true
                },
                batalAjuan() {
                    return true
                },
            }
        }
    }
})

localVue.mixin({
    methods: {
        $isObject(data) {
            return isObject(data || {});
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
        $allow() {
            return true;
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
        $fGender(data) {
            return data === 'L' ? 'Laki - laki' : data === 'P' ? 'Perempuan' : '-';
        },
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
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
            router,
            store,
            vuetify,
            data() {
                return {
                    data: this.detail,
                }
            },
            propsData: {
                withAction: true
            }
        })
    }
    test('test onDetil when detail button is clicked', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: [
                {
                    kBerkas: 1,
                    title: "Pakta Integritas",
                    type: "integritas",
                    valid: true,
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        const button = wrapper.find('.mdi-eye')
        button.trigger('click')
        wrapper.vm.onDetil()
    })
    test('test onView when download button is clicked', async () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn();
        wrapper.setData({
            data: [
                {
                    file: "2021/1/10002877-berkas-1-210517111924.png",
                    id: 1,
                    nama: "Pengerjaan Tiket-Alur.png",
                    paud_petugas_berkas_id: 1,
                    paud_petugas_id: 2,
                    tahun: 2021,
                    type: "paud_petugas_berkas",
                    url: "https://upload.dev.siap.id/gpo/paud/petugas-berkas/2021/1/10002877-berkas-1-210517111924.png"
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        const button = wrapper.find('.mdi-download')
        button.trigger('click')
        wrapper.vm.onView()
    })
    test('test onUpload when unggah file button is clicked', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: [
                {
                    file: "2021/1/10002877-berkas-1-210517111924.png",
                    id: 1,
                    nama: "Pengerjaan Tiket-Alur.png",
                    paud_petugas_berkas_id: 1,
                    paud_petugas_id: 2,
                    tahun: 2021,
                    type: "paud_petugas_berkas",
                    url: "https://upload.dev.siap.id/gpo/paud/petugas-berkas/2021/1/10002877-berkas-1-210517111924.png"
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[id="unggah"]').exists()).toBe(true)
        const button = wrapper.find('[id="unggah"]')
        button.trigger('click')
        wrapper.vm.onUpload()
    })

})
