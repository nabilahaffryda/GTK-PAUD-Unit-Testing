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
                BaseModalFull
            },
            propsData: {
                detail:
                {
                    nama: "Diklat Berjenjang Tingkat Dasar Moda Daring Kombinasi - Jaksel",
                    type: "paud_diklat",
                    instansi: {
                        data: {
                            nama: "BPSDM Provinsi Jakarta",
                            type: "instansi",
                            id: 720009
                        }
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
            }
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

})
