import { mount, createLocalVue } from "@vue/test-utils";
import BaseListAvatar from "@/components/base/BaseListAvatar.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseListAvatar.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render list avatar', () => {
        const VAvatar = {
            props: [
                'size',
                'tile',
                'useAction',
                'color',
                'caption',
                'title',
                'subtitle',
                'detail'
            ],
            template: '<div><slot :tile="tile" /></div>'
        }

        const wrapper = mount(BaseListAvatar, {
            localVue,
            vuetify,
            stubs: {
                VAvatar
            },
            propsData: {
                title: 'avatar'
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})