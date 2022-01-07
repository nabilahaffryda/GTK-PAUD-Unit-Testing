import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Form from "@/views/instansi/kelas/formulir/Form.vue";
import { getDeepObj, } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();

localVue.use(VueRouter)
const router = new VueRouter();

localVue.mixin({
    methods: {
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
    }
})

describe('Form.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Form, {
            localVue,
            vuetify,
            router,
        })
    }

    test('test nama peserta', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            peserta: {
                nama: 'NUR HASANAH'
            }
        })
        expect(wrapper.find('[id="nama"]').exists()).toBe(true)
        expect(wrapper.vm.peserta.nama).toBe('NUR HASANAH')
    })

    test('test email', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            peserta: {
                email: '202000538956@guruku.id'
            }
        })
        expect(wrapper.find('[id="email"]').exists()).toBe(true)
        expect(wrapper.vm.peserta.email).toBe('202000538956@guruku.id')
    })

    test('test tahap', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            peserta: {
                is_pptm: 'pendalaman materi'
            }
        })
        expect(wrapper.find('[id="tahap"]').exists()).toBe(true)
        expect(wrapper.vm.peserta.is_pptm).toBe('pendalaman materi')
    })

    test('test total of data', () => {
        const wrapper = wrapperFactory()
        wrapper.setData({
            peserta: {
                total: '100'
            }
        })
        expect(wrapper.find('[id="total"]').exists()).toBe(true)
        expect(wrapper.vm.peserta.total).toBe('100')
    })
})
