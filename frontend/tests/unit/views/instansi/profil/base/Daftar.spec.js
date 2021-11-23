import { createLocalVue, mount } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import Daftar from "@/views/instansi/profil/base/Daftar.vue";
import { getDeepObj, isObject } from '@utils/format';

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
        $allow() {
            return true;
        },
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
    }
})

describe('Daftar.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Daftar, {
            localVue,
            router,
            store,
            vuetify,
            data() {
                return {
                    data: this.formulir
                }
            },
            stubs: {
                Viewer: true
            },
        })
    }
    test('call edit() when button is clicked', async () => {
        const wrapper = wrapperFactory()
        wrapper.vm.edit = jest.fn()
        wrapper.setData({
            data: [
                {
                    items: {
                        0: {
                            nama: "Diklat MOT",
                            nama_file: "chaerul yozi.pdf",
                            tahun_diklat: 2010,
                            k_diklat_paud: 3,
                            url: "https://upload.dev.siap.id/gpo/paud/petugas-berkas/2021/1/10002877-diklat-3-210617022117.pdf"
                        },
                        1: {
                            k_diklat_paud: 1,
                            nama: "Diklat Berjenjang",
                            nama_file: "Belajar&Pembelajaran5.pdf",
                            tahun_diklat: 2019,
                            url: "https://upload.dev.siap.id/gpo/paud/petugas-berkas/2021/1/10002877-diklat-1-210701113217.pdf"
                        }
                    },
                    type: "diklat",
                    form: "FormCollection",
                }
            ],
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.findComponent({ ref: 'panel' }).exists()).toBe(true);
        const button = wrapper.findComponent({ ref: 'panel' })
        button.trigger('click')
        wrapper.vm.edit()
    })
    test('test modal open', async () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            data: [
                {
                    berkas: {
                        component: "Berkas",
                        deskripsi: "",
                        form: "FormUnggah",
                        max: 10,
                        optional: true,
                        title: "Berkas Persyaratan Lainnya"
                    },
                    diklat: {
                        component: "Daftar",
                        deskripsi: "Tuliskan pelatihan yang relevan dengan profesi Anda yang pernah anda ikuti",
                        form: "FormCollection",
                        max: 10,
                        optional: true,
                        status: "diklat",
                        title: "Data Pengalaman Diklat"
                    },
                    profil: {
                        component: "Profil",
                        deskripsi: "",
                        form: "FormProfil",
                        max: 10,
                        optional: true,
                        title: "Profil Pengajar"
                    }
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.findComponent({ ref: 'popup' }).exists()).toBe(true);
    })
})
