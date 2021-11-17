import { createLocalVue, mount, config } from "@vue/test-utils";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from "vuex";
import axios from 'axios';
import Main from "@/views/instansi/akun/list/base/Main.vue";
import BaseModalFull from "@/components/base/BaseModalFull.vue";
import { getDeepObj, isObject, assetsUrl, localDate } from '@utils/format';

Vue.use(Vuetify)
const localVue = createLocalVue();
config.showDeprecationWarnings = false;
document.body.setAttribute('data-app', true)

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
        institusi: {
            namespaced: true,
            listInstansis: {
                fetch() {
                    return true
                }
            }
        },
        akun: {
            namespaced: true,
            actions: {
                fetch() {
                    return true
                },
                create() {
                    return true
                },
                update() {
                    return true
                },
                listGroups() {
                    return true
                },
                action() {
                    return true
                },
                lookup() {
                    return true
                },
                getDetail() {
                    return true
                },
                downloadList() {
                    return true
                },
                templateUpload() {
                    return true
                },
                upload() {
                    return true
                },
                setStatus() {
                    return true
                },
            }
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
        $isObject(data) {
            return isObject(data || {});
        },
        $imgUrl: (url) => (url ? assetsUrl(url) : ''),
        $localDate(date, short, withTime, usingNumber, useSeconds) {
            if (!date) return '-';
            return localDate(date, short, withTime, usingNumber, useSeconds);
        },
        $titleCase(kata) {
            return (kata && kata.charAt(0).toUpperCase() + kata.slice(1)) || '';
        },
    }
})

jest.mock('axios');

describe('Main.vue', () => {
    let vuetify;
    beforeEach(() => {
        vuetify = new Vuetify()
        const responseGet = {
            data: {
                policies: {
                    'akun-admin-program-lpd.update': true,
                    'akun-${akses}.create': true
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
        return mount(Main, {
            localVue,
            router,
            store,
            vuetify,
            components: {
                BaseModalFull,
            },
            propsData: {
                actions: [
                    {
                        icon: 'mdi-pencil',
                        title: 'Edit Akun',
                        event: 'onEdit',
                        akses: 'akun-pengajar.update'
                    }
                ],
                jenis: 'pengajar',
                akses: 'pengajar',
                title: 'Pengajar'
            },
            data() {
                return {
                    reload: false,
                }
            }
        })
    }
    test('calls onReload when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onReload = jest.fn();
        wrapper.setData({ reload: true });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-reload').exists()).toBe(true);
        const btn = wrapper.find('.mdi-reload')
        btn.trigger('click')
        wrapper.vm.onReload();
    })
    test('calls onFilter when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onFilter = jest.fn();
        wrapper.setData({
            data: [
                {
                    0: {
                        master: {
                            0: {
                                text: "Aktif",
                                value: "1"
                            },
                            1: {
                                text: "Tidak Aktif",
                                value: "0"
                            }
                        }
                    },
                }
            ]
        });
        await wrapper.vm.$nextTick();
        expect(wrapper.find('.mdi-filter-variant').exists()).toBe(true);
        const button = wrapper.find('.mdi-filter-variant')
        button.trigger('click')
        wrapper.vm.onFilter();
    })
    test('Enter search text and check the value of keyword', async () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify')
        wrapper.setData({ keyword: 'Akun Pengajar 1' })
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.keyword).toBe('Akun Pengajar 1')
    })
    test('click search button and clear input', () => {
        const wrapper = wrapperFactory();
        wrapper.find('.mdi-magnify').trigger("click");
        const textInput = wrapper.find('.mdi-magnify')
        expect(textInput.text()).toMatch('')
        expect(wrapper.vm.keyword).toBe('')
    })
    test('call onAction when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAction = jest.fn();
        wrapper.setData({
            data: [
                {
                    0: {
                        akun_id: "17209679",
                        id: 121,
                        instansi_id: 800006,
                        k_group: 174,
                        paud_admin_id: 121,
                        tahun: 2021,
                        akun: {
                            data: {
                                alamat: "Kapuas Bengkulu Tengah Bengkulu",
                                email: "pengajar1@gmail.com",
                                nama: "Akun Pengajar 1",
                                nik: "1107246808140002",
                                nip: "1231231231111111",
                                no_hp: "08123456782",
                            }
                        }
                    },
                }
            ],
            total: 1,
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-dots-vertical').exists()).toBe(true);
        const button = wrapper.find('.mdi-dots-vertical')
        button.trigger('click')
        wrapper.vm.onAction();
    })
    test('calls onAdd when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onAdd = jest.fn();
        wrapper.setData({
            data: [
                {
                    0: {
                        akun_id: "17209679",
                        id: 121,
                        instansi_id: 800006,
                        k_group: 174,
                        paud_admin_id: 121,
                        tahun: 2021,
                        akun: {
                            data: {
                                alamat: "Kapuas Bengkulu Tengah Bengkulu",
                                email: "pengajar1@gmail.com",
                                nama: "Akun Pengajar 1",
                                nik: "1107246808140002",
                                nip: "1231231231111111",
                                no_hp: "08123456782",
                            }
                        }
                    },
                }
            ],
            total: 1
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-plus').exists()).toBe(true);
        const button = wrapper.find('.mdi-plus')
        button.trigger('click')
        wrapper.vm.onAdd();
    })
    test('calls onDownload when button is clicked', async () => {
        const wrapper = wrapperFactory();
        wrapper.vm.onDownload = jest.fn();
        wrapper.setData({
            data: [
                {
                    0: {
                        akun: {
                            data: {
                                nama: "Azhar Mashurie",
                                nik: "1101070308110002",
                                nip: "123123123123123",
                                no_hp: "111111111",
                                no_telpon: "02865986227",
                            }
                        }
                    }
                }
            ]
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('.mdi-download').exists()).toBe(true);
        expect(!!document.querySelector('modal')).toBe(false)
        const button = wrapper.find('.mdi-download')
        button.trigger('click')
        expect(wrapper.find({ ref: 'modal' }).exists()).toBe(true);
        wrapper.vm.onDownload();
    })
    test('call Pengajar Inti when button set pengajar is clicked', async () => {
        const wrapper = wrapperFactory()
        await wrapper.setProps({
            akses: 'pengajar',
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.find('[id="set-pengajar"]').exists()).toBe(true);
        const button = wrapper.find('[id="set-pengajar"]')
        button.trigger('click')
        wrapper.findAll('[id="set-pengajar"]').at(0).trigger('click');
        wrapper.vm.setMultiInti('inti')
    })
})
