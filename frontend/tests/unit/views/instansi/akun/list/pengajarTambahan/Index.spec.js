import Index from "@/views/instansi/akun/list/pengajarTambahan/Index.vue"
import { mount, createLocalVue } from "@vue/test-utils";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";

Vue.use(Vuetify)
const localVue = createLocalVue()

localVue.use(VueRouter)
const router = new VueRouter()

localVue.mixin({
    methods: {
        $allow() {
            return true;
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
            router,
            vuetify,
            data() {
                return {
                    items: [
                        { tab: 'Pengajar Tambahan (GTK PAUD)', params: { k_unsur_pengajar_paud: 1 } },
                        { tab: 'Pengajar Tambahan (Dosen/Akademisi PAUD)', params: { k_unsur_pengajar_paud: 2 } },
                    ],
                }
            },
            stubs: {
                ListAdmin: true
            }
        })
    }
    test('test initial tab 1', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.setData({
            tab: [
                {
                    tab: 'Pengajar Tambahan (GTK PAUD)', params: { k_unsur_pengajar_paud: 1 }
                }
            ]
        })
        wrapper.vm.$nextTick()
        const tab1 = wrapper.find('[id="tab"]')
        tab1.trigger('click')
    })
    test('test initial tab 2', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.setData({
            tab: [
                {
                    tab: 'Pengajar Tambahan (Dosen/Akademisi PAUD)', params: { k_unsur_pengajar_paud: 2 }
                }
            ]
        })
        wrapper.vm.$nextTick()
        const tab1 = wrapper.find('[id="tab"]')
        tab1.trigger('click')
    })

})
