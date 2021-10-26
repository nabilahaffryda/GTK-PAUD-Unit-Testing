import { mount, createLocalVue } from "@vue/test-utils";
import Notifikasi from "@/components/popup/Notifikasi.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('Notifikasi.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })

    test('render popup notification', () => {
        const VNotif = {
            props: ['notif'],
            template: '<div><slot :notif="notif" /></div>'
        }
        const wrapper = mount(Notifikasi, {
            localVue,
            vuetify,
            stubs: {
                VNotif
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
    test('triggers options onAction click', async () => {
        const wrapper = mount(Notifikasi)
        await wrapper.trigger('click', { onAction: 0 })
    })
})
