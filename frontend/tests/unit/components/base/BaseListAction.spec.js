import { mount, createLocalVue } from "@vue/test-utils";
import BaseListAction from "@/components/base/BaseListAction.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import axios from 'axios'

Vue.use(Vuetify)
const localVue = createLocalVue();

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
            stubs: {
                VMenu,
            },
        });
        expect(wrapper).toMatchSnapshot();
        wrapper.vm.title('Edit Institusi')
        expect(axios.get).toHaveBeenCalledTimes(1)
        expect(axios.get).toBeCalledWith(expect.stringMatching(/Edit Institusi/))

    })
})