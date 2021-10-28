import { mount, createLocalVue } from "@vue/test-utils";
import BaseListAction from "@/components/base/BaseListAction.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import axios from 'axios'

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.mixin({
    methods: {
        $allow(akses, policy) {
            // cek jika tidak ada akses
            if (!akses || (akses && typeof akses === 'boolean')) return typeof akses === 'boolean' ? akses : true;

            // jika ada ambil preferensi akses
            const akseses = store.getters['preferensi/akseses'] || [];

            let allow = false;
            const pathAkses = akses && typeof akses === 'string' && akses.split('|');
            if (pathAkses.length) {
                pathAkses.forEach((path) => {
                    if (allow) return;
                    if (!allow && policy) {
                        allow =
                          !path || path === 'true' || ((akseses || []).filter((item) => item === path).length > 0 && policy[path]);
                    } else if (!allow && !policy) {
                        allow = !path || path === 'true' || (akseses || []).filter((item) => item === path).length > 0;
                    }
                });
            }

            return allow;
        },
    }
})

jest.mock('axios');

describe('BaseListAction.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify();
        const responseGet = {
            data: {
                policies: [
                    {
                        icon: 'mdi-pencil',
                        title: 'Edit Institusi',
                        event: 'onEdit',
                        akses: 'lpd.update'
                    }
                ]
            }
        }
        axios.get.mockResolvedValue(responseGet);
    })

    afterEach(() => {
        jest.resetModules()
        jest.clearAllMocks()
    })

    test('render list action', () => {
        const VMenu = {
            props: ['data', 'id', 'actions', 'allow', 'menuColor'],
            template: '<div><slot :item="item" /></div>'
        }
        const wrapper = mount(BaseListAction, {
            localVue,
            vuetify,
            props: {
                actions: [
                    {
                        icon: 'mdi-pencil',
                        title: 'Edit Institusi',
                        event: 'onEdit',
                        akses: 'lpd.update'
                    }
                ]
            },
            // stubs: {
            //     VMenu,
            // },
        });

        console.log(wrapper.html());
        expect(wrapper.find('.mdi-dots-vertical').exists()).toBe(true);


        // expect(wrapper).toMatchSnapshot();
        // wrapper.vm.title('Edit Institusi')
        // expect(axios.get).toHaveBeenCalledTimes(1)
        // expect(axios.get).toBeCalledWith(expect.stringMatching(/Edit Institusi/))

    })
})