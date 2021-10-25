import { mount, createLocalVue } from "@vue/test-utils";
import GotoModul from "@/components/popup/GotoModul.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import PopupNotifikasi from "@/components/popup/Notifikasi"

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('GotoModul.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render GotoModul', () => {
        const wrapper = mount(GotoModul, {
            localVue,
            vuetify,
            data() {
                return {
                    isChecked: false,
                    isMulai: false,
                    isDemo: false,
                    key: '',
                    action: '',
                };
            },
            stubs: {
                PopupNotifikasi
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
