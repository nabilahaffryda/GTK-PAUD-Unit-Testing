import { mount, createLocalVue } from "@vue/test-utils";
import BaseListPopup from "@/components/base/BaseListPopup.vue";
import Vuetify from 'vuetify';
import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(Vuetify)
const localVue = createLocalVue();
localVue.use(VueRouter)
const router = new VueRouter();

describe('BaseListPopup.vue', () => {
    let vuetify
    beforeEach(() => {
        vuetify = new Vuetify()
    })
    test('render list popup', () => {
        const VPopup = {
            props: [
                'api', 'title', 'id', 'multiselect'
            ],
            template: '<div><slot :pageTotal="pageTotal" /></div>'
        }
        const wrapper = mount(BaseListPopup, {
            localVue,
            vuetify,
            router,
            stubs: {
                VPopup,
            },
            computed: {
                selected() {
                    return true
                }
            },
        });

        expect(wrapper).toMatchSnapshot();
    })
})