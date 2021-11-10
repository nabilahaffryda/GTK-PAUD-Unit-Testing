import { createLocalVue, mount } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Jadwal from "@/views/instansi/diklat/formulir/Jadwal.vue";
import { getDeepObj } from '@utils/format';

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
describe('Detail.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(Jadwal, {
            localVue,
            vuetify,
            router,
            stubs: {
                BaseFormGenerator: true
            },
            computed: {
                schema() {
                    return true
                }
            },
            data() {
                return {
                    data: this.jadwals,
                }
            },
        })
    }
    test('call reset method', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.reset = jest.fn();
        wrapper.vm.reset();
        expect(wrapper.vm.reset.mock.calls.length).toBe(1);
    })
    test('calls onAddJadwal when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            data: [
                {
                    nama: "Tahap 2",
                    tgl_mulai: "2021-12-30",
                    tgl_selesai: "2022-01-03"
                }
            ],
        })
        wrapper.vm.onAddJadwal = jest.fn();
        wrapper.vm.onAddJadwal();
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-plus-circle').exists()).toBe(true);
        const button = wrapper.find('.mdi-plus-circle')
        button.trigger('click')
    })
    test('calls onDeleteJadwal when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.setProps({
            isEdit: false
        })
        wrapper.setData({
            jadwals: [
                {
                    nama: "Tahap 2",
                    tgl_mulai: "2021-12-30",
                    tgl_selesai: "2022-01-03"
                },
                {
                    nama: "Angkatan 2",
                    tgl_mulai: "2021-02-15",
                    tgl_selesai: "2021-02-26"
                }
            ]
        })
        wrapper.vm.onDeleteJadwal = jest.fn();
        wrapper.vm.onDeleteJadwal();
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-close').exists()).toBe(true);
        const button = wrapper.find('.mdi-close')
        button.trigger('click')
    })
})