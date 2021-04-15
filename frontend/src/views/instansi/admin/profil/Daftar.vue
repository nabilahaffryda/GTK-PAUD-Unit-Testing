<template>
  <div>
    <v-expansion-panels v-model="panel" multiple>
      <v-expansion-panel v-for="(item, i) in contents" :key="i">
        <v-expansion-panel-header>
          <span class="text-h6">{{ item.title }}</span>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <template v-if="i === 'berkas'">
            <div class="mb-4">
              <div class="subtitle-1 font-weight-bold">Berkas Unggahan</div>
              <span>
                Lengkapi data persyaratan sesuai kebutuhan sistem, Silakan <b>tekan tombol/icon pensil</b> di sebelah
                kanan untuk melakukan edit
              </span>
            </div>
            <berkas
              v-for="(item, b) in berkases"
              :key="b"
              :berkas="item"
              :type="item.type"
              :valid="item.valid"
              :with-action="item.withAction"
              @upload="$emit('upload', item.type)"
            />
          </template>
          <template v-else>
            <component :is="item.component" @edit="$emit('edit')" />
          </template>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </div>
</template>
<script>
import Berkas from '../formulir/Berkas';
import Profil from '../formulir/Profil';
export default {
  props: {
    contents: {
      type: Object,
      default: () => {},
    },
    berkases: {
      type: Array,
      default: () => [],
    },
  },
  components: { Berkas, Profil },
  data() {
    return {
      panel: [0, 1],
    };
  },
};
</script>
