import { mount, createLocalVue } from "@vue/test-utils";
import ProfilPicture from "@/components/popup/ProfilPicture.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import AvatarCropper from 'vue-avatar-cropper';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('ProfilPicture.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render popup profil picture', async () => {
        const VProfilPicture = {
            props: ['title', 'trigger', 'uploadUrl',
                'useBase64', 'outputMime', 'nama', 'limit',],
            template: '<div><slot :labels="labels" /></div>'
        }
        const wrapper = mount(ProfilPicture, {
            localVue,
            vuetify,
            stubs: {
                VProfilPicture, AvatarCropper
            },
            propsData: {
                trigger: 'trigger'
            },
            props: {
                useBase64: true
            },
        });
        expect(wrapper).toMatchSnapshot();
    })
})
