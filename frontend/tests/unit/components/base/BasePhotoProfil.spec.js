import { mount, createLocalVue } from "@vue/test-utils";
import BasePhotoProfil from "@/components/base/BasePhotoProfil.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BasePhotoProfil.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render photo profil', () => {
        const VPP = {
            props: [
                'isEdit',
                'aspectRatio',
                'useBase64',
                'photo',
                'photodef',
                'uploadUrl',
                'useTrigger',
                'useBtn'
            ],
            template: '<div><slot :uploadUrl="uploadUrl" /></div>'
        }

        const wrapper = mount(BasePhotoProfil, {
            localVue,
            vuetify,
            stubs: {
                VPP
            },
            propsData: {
                photo: 'avatar.png'
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})