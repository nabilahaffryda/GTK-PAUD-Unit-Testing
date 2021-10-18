import { mount, createLocalVue } from "@vue/test-utils";
import BaseCardInfo from "@/components/base/BaseCardInfo.vue";
import Vuetify from 'vuetify';

describe('BaseCardInfo.vue', () => {
    test('should show card info', () => {
        const VCard = {
            props: ['size', 'icon', 'title', 'info'],
            template: '<div><slot :size="size" /></div>'
        }
        const localVue = createLocalVue();
        localVue.use(Vuetify)
        localVue.use(BaseCardInfo)

        const wrapper = mount(BaseCardInfo, {
            localVue: localVue,
            stubs: {
                VCard
            }
        });

        // mount(BaseCardInfo, {
        //     stubs: {
        //         VCard
        //     }
        // })
        // expect(true).toBe(true);
    })
})
