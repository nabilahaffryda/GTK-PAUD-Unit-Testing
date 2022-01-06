import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import UploadLaporan from "@/views/instansi/kelas/formulir/UploadLaporan.vue";

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

describe('UploadLaporan.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(UploadLaporan, {
            localVue,
            vuetify,
            router,
        })
    }
    test('test download template laporan button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('.mdi-file').exists()).toBe(true)
        const btn = wrapper.find('.mdi-file')
        btn.trigger('click')
    })

    test('test upload laporan button', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.findComponent({ ref: 'formulir' }).exists()).toBe(true)
        const btn = wrapper.findComponent({ ref: 'formulir' })
        btn.trigger('click')
    })
})
