import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import Vuex from "vuex"
import CollectionBerkas from "@/views/instansi/verval/formulir/CollectionBerkas.vue"

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

describe('CollectionBerkas.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(CollectionBerkas, {
            localVue,
            vuetify,
            router,
            store,
            propsData: {
                type: "paud_instansi",
                berkas: {
                    nama: "clipboard-202104211134-agte8.png",
                    file: "lpd-720004/buku-rekening-720004-210524090408.png",
                    url: "https://upload.dev.siap.id/gpo/paud/lpd-berkas/lpd-720004/buku-rekening-720004-210524090408.png",
                    type: "paud_instansi_berkas",
                    id: 31
                },
                withAction: true
            }
        })
    }
    test('test detail button', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-eye').exists()).toBe(true)
        const btn = wrapper.find('.mdi-eye')
        btn.trigger('click')
        wrapper.vm.onDetil()
    })
    test('test download button', async () => {
        const wrapper = wrapperFactory()
        window.open = jest.fn();
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-download').exists()).toBe(true)
        const button = wrapper.find('.mdi-download')
        button.trigger('click')
        wrapper.vm.onView()
    })
    test('test unggah file', async () => {
        const wrapper = wrapperFactory()
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[id="unggah"]').exists()).toBe(true)
        const button = wrapper.find('[id="unggah"]')
        button.trigger('click')
        wrapper.vm.onUpload()
    })

})
