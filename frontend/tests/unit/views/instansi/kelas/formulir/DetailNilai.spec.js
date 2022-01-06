import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import DetailNilai from "@/views/instansi/kelas/Formulir/DetailNilai.vue";
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

describe('DetailNilai.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(DetailNilai, {
            localVue,
            vuetify,
            router,
            data() {
                return {
                    items: ['Pendalaman Materi', 'Pelaksanaan Tugas Mandiri'],
                }
            },
        })
    }

    test('test tab 1', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.setData({
            tab: {
                items: ['Pendalaman Materi']
            }
        })
        const tab1 = wrapper.find('[id="tab"]')
        tab1.trigger('click')
    })

    test('test tab 2', () => {
        const wrapper = wrapperFactory()
        expect(wrapper.find('[id="tab"]').exists()).toBe(true)
        wrapper.setData({
            tab: {
                items: ['Pelaksanaan Tugas Mandiri']
            }
        })
        const tab2 = wrapper.find('[id="tab"]')
        tab2.trigger('click')
    })

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

})