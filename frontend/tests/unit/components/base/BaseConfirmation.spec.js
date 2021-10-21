import { mount, createLocalVue } from "@vue/test-utils";
import BaseConfirmation from "@/components/base/BaseConfirmation.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseConfirmation.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render confirmation', () => {
        const wrapper = mount(BaseConfirmation, {
            localVue,
            vuetify,
            data() {
                return {
                    title: null,
                    desc: null,
                    tipe: 'warning',
                    lblCancelButton: 'Tidak',
                    lblConfirmButton: 'Ya',
                    lblConfirmColor: 'blue darken-1',
                    lblCancelColor: 'grey darken-1',
                    items: [],
                    form: '',
                    resolve: null,
                    reject: null,
                    modal: false,
                    confirmation: true,
                    invert: false,
                    icon: '',
                    myIcons: {
                        warning: 'mdi-alert',
                        error: 'mdi-close-circle',
                        info: 'mdi-information',
                        success: 'mdi-check-circle',
                        secondary: 'mdi-alert',
                    },
                    myClass: {
                        warning: '',
                        error: 'white--text',
                        info: ' white--text',
                        success: ' white--text',
                        secondary: 'white--text',
                    },
                }
            }
        });
        wrapper.findAll('#data').exists();
        expect(wrapper).toMatchSnapshot();
    })
})
