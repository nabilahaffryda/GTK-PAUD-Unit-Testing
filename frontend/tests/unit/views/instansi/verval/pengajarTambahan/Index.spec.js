import { createLocalVue, mount, } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import Vuex from "vuex";
import { getDeepObj, isObject, isArray, arrayToObject, } from '@utils/format';
import Index from "@/views/instansi/verval/pengajarTambahan/Index.vue";

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

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
        $getDeepObj(obj, desc) {
            return getDeepObj(obj, desc);
        },
        $allow() {
            return true;
        },
        $mapForMaster(data, text = false) {
            // cek type data
            let temp = [];
            if (data && isObject(data)) {
                for (let key in data) {
                    temp.push({
                        text: data[key],
                        value: text ? data[key] : Number(key),
                    });
                }
            } else if (data && isArray(data)) {
                temp = data.map((value, idx) => {
                    return {
                        text: isObject(value) ? value?.keterangan ?? value.text : value,
                        value: isObject(value) ? value[text] ?? value?.value ?? idx : value,
                    };
                });
            }
            return temp;
        },
        $isObject(data) {
            return isObject(data || {});
        },
        $isArray(data) {
            return isArray(data);
        },
        $arrToObj(array, key) {
            if (!array || !array.length) return {};
            return arrayToObject(array, key);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
    }
})
jest.mock('axios');
describe('Index.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
        const responseGet = {
            data: {
                policies: {
                    'petugas-verval-kunci.update': true,
                }
            },
        }
        axios.get.mockResolvedValue(responseGet);
    })
    afterEach(() => {
        jest.resetModules()
        jest.clearAllMocks()
    })
    function wrapperFactory({ } = {}) {
        return mount(Index, {
            localVue,
            vuetify,
            router,
            store,
            stubs: {
                BaseModalFull: true,
                PopupPreviewDetail: true,
            },
            data() {
                return {
                    items: [
                        { tab: 'Pengajar Tambahan (Guru)', params: { k_unsur_pengajar_paud: 1 } },
                        { tab: 'Pengajar Tambahan (Dosen)', params: { k_unsur_pengajar_paud: 2 } },
                    ],
                }
            }
        })
    }
    test('initial tab 1', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            items: [
                {
                    tab: 'Pengajar Tambahan (Guru)', params: { k_unsur_pengajar_paud: 1 }
                }
            ]
        })
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="tab-item"]').exists()).toBe(true);
        const button = wrapper.find('[data-testid="tab-item"]')
        button.trigger('click')
    })
    test('initial tab 2', async () => {
        const wrapper = wrapperFactory();
        wrapper.setData({
            items: [
                {
                    tab: 'Pengajar Tambahan (Dosen)', params: { k_unsur_pengajar_paud: 2 },
                }
            ]
        })
        await wrapper.vm.$nextTick();
        expect(wrapper.find('[data-testid="tab-item"]').exists()).toBe(true);
        const button = wrapper.find('[data-testid="tab-item"]')
        button.trigger('click')
    })
})