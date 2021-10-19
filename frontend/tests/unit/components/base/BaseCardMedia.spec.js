import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardMedia from "@/components/base/BaseCardMedia.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';

Vue.use(Vuetify)
const localVue = createLocalVue();

describe('BaseCardMedia.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('should show card media', () => {
        const VCard = {
            props: ['noPadding', 'tile', 'avatarColor', 'color', 'size', 'title', 'desc', 'url'],
            template: '<div><slot name="status" /></div>'
        }

        const wrapper = mount(BaseCardMedia, {
            localVue,
            vuetify,
            stubs: {
                VCard
            }
        });
        expect(wrapper).toMatchSnapshot();
    })
})
