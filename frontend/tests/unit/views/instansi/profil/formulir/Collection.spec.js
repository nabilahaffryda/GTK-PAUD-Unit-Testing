import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex";
import { getDeepObj } from '@utils/format';
import Collection from "@/views/instansi/profil/formulir/Collection.vue"

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

describe('Collection.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    function wrapperFactory({ } = {}) {
        return mount(Collection, {
            localVue,
            router,
            store,
            vuetify,
            propsData: {
                diklat: {
                    akun_id: "17209679",
                    nama: "Diklat Berjenjang",
                    nama_file: "Admin kelas.png",
                    url: "https://upload.dev.siap.id/gpo/paud/petugas-berkas/2021/1/17209679-diklat-1-210812044059.png",
                    tahun_diklat: 2018
                }
            }
        })
    }
    test('test download button', () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn();
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        const btn = wrapper.find('.mdi-download')
        btn.trigger('click')
        wrapper.vm.onView()
    })
    test('test detail button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        const button = wrapper.find('.mdi-eye')
        button.trigger('click')
        wrapper.vm.onDetil()
    })
})
