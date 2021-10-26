import { mount, createLocalVue } from "@vue/test-utils";
import Password from "@/components/popup/Password.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('Password.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    const submit = jest.fn()
    test('render popup password', () => {
        const wrapper = mount(Password, {
            localVue,
            vuetify,
            data() {
                return {
                    dialog: false,
                    valid: true,
                    pass1: false,
                    pass2: false,
                    pass3: false,
                    rules: [
                        (v) => !!v || 'Konfirmasi Kata Sandi Baru wajib diisi!',
                        (v) => (v && v === this.params.password) || 'Konfirmasi Kata Sandi Baru tidak sama!',
                    ],
                    params: {},
                };
            },
        });
        expect(wrapper).toMatchSnapshot();
    })
    test('triggers options save click', async () => {
        const wrapper = mount(Password)
        await wrapper.trigger('click', { submit: 0 })
    })
})
