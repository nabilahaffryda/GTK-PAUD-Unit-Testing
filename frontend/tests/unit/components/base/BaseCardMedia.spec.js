import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardMedia from "@/components/base/BaseCardMedia.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseCardMedia.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    function wrapperFactory({ } = {}) {
        return mount(BaseCardMedia, {
            localVue,
            vuetify,
            router,
            props: {
                noPadding: true,
                tile: true,
                avatarColor: 'yellow',
                color: 'blue',
                size: 95,
                title: 'paud',
                desc: 'sim paud',
                url: 'https://penggerak-cdn.siap.id/s3/sekolahpenggerak/'
            }
        });
    }
    test('test base card media', () => {
        const wrapper = wrapperFactory();
        wrapper.vm.props = jest.fn();
        wrapper.vm.props();
        expect(wrapper.vm.props.mock.calls.length).toBe(1);
    })
})
