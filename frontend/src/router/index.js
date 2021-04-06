import Vue from 'vue';
import Router from 'vue-router';
import store from '@store';
import { isObject } from '@utils/format';
import routesAuth from './routes/auth';
import MenusGtk from '@configs/menus.gtk';
import MenusIns from '@configs/menus.instansi';
import { duration, getDeepObj } from '@/utils/format';

const roleMenus = {
  gtk: MenusGtk,
  instansi: MenusIns,
};

Vue.use(Router);

const createRouter = () =>
  new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [...routesAuth],
  });

const router = createRouter();

export function resetRouter(newModules) {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
  router.addRoutes(newModules);
}

let initRoute = false;
// Before each route evaluates
router.beforeEach((to, from, next) => {
  // Semua bisa akses, karena page yang dituju tidak perlu login
  if (to.meta?.public) return next();

  // Page yang perlu login
  // perlu pengecekan user login
  async function isUserLogin() {
    const logon = await store.dispatch('auth/checkUser');
    return logon;
  }

  async function getPreferensi(id) {
    const preferensi = await store.dispatch('auth/userPreferensi', id);
    return preferensi;
  }

  function getMenuGtk(preferensi) {
    // Belum register
    const isKasek = isObject(preferensi?.kasek ?? {});
    const isRegistered = isKasek ? isObject(preferensi?.peserta ?? {}) : isObject(preferensi?.instruktur ?? {});
    const syarat = preferensi?.peserta_status?.syarat ?? {};

    if (!isRegistered || (isKasek && !syarat?.jenjang)) return [];

    // Sudah
    const tahap = isKasek
      ? preferensi.peserta && Number(preferensi.peserta.lulus_tahap) === 1
        ? 2
        : 1
      : preferensi?.instruktur?.gelombang ?? 1;

    const roleMenu = ((roleMenus['gtk'] && roleMenus['gtk'][tahap]) || []).filter((item) => {
      return item.key !== (!isKasek ? 'tbs' : '');
    });

    const menus = roleMenu.map((item) => {
      let temp = Object.assign({}, item);
      const isTutup = isKasek
        ? preferensi?.peserta_status.tutup ?? false
        : preferensi?.instruktur_status?.tutup ?? false;
      const kVerval = isKasek
        ? getDeepObj(preferensi, 'peserta.k_verval_psp')
        : getDeepObj(preferensi, 'instruktur.k_verval_psp');

      if (temp.akses && ['cv', 'esai'].includes(item.key)) {
        temp['desc'] = item.desc + (isTutup ? ` - <span class="info--text">${isTutup}</span>` : '');
      }

      // atur menu tbs dan tbs-demo
      const prefTbs = preferensi?.peserta?.psp_profil_tbses ?? [];

      if (prefTbs.length) {
        const configTbs = prefTbs.filter((item) => !!Number(item.is_demo) === false)[0] || {};
        const configTbsDemo = prefTbs.filter((item) => !!Number(item.is_demo) === true)[0] || {};
        const isBukaTbsDemo = configTbsDemo.is_berlangsung;
        const isBukaTbs = configTbs.is_berlangsung;
        const timezone = 'WIB';
        const vervalTolak = kVerval === 4;

        if (temp.akses && !vervalTolak && ['tbs-demo'].includes(item.key)) temp['disable'] = !isBukaTbsDemo;
        if (temp.akses && !vervalTolak && ['tbs'].includes(item.key)) temp['disable'] = !isBukaTbs;

        if (['tbs-demo'].includes(item.key)) {
          temp.subtitle = isObject(configTbsDemo)
            ? duration(configTbsDemo.wkt_mulai, configTbsDemo.wkt_selesai)
            : 'Belum ada Jadwal';

          temp.desc = isObject(configTbsDemo)
            ? `
            Pelaksanaan <b>uji coba</b> Tes Bakat Skolastik ini akan tersedia pada tanggal
            <b>
            ${duration(configTbs.wkt_mulai, configTbs.wkt_selesai, {
              useTime: true,
              timeSeparator: ' s.d ',
              timeprefix: 'mulai',
              zone: timezone,
            })}
            </b>
          `
            : 'Pelaksanaan <b>uji coba</b> Tes Bakat Skolastik ini akan menunggu jadwal dari Pusat';
        }

        if (['tbs'].includes(item.key) && isObject(configTbs)) {
          temp.desc = `
            Tes ini akan tersedia pada tanggal
            <b>
            ${duration(configTbs.wkt_mulai, configTbs.wkt_selesai, {
              useTime: true,
              timeSeparator: ' s.d ',
              timeprefix: 'mulai',
              zone: timezone,
            })}</b>. Informasi akan kami berikan melalui surel/email
            `;
        }
      } else {
        if (['tbs-demo'].includes(item.key)) temp['akses'] = false;
      }

      if (temp && temp.href) {
        temp.href = temp.href.replace(
          /#PORTAL_URL#/g,
          'https://sekolah.penggerak.kemdikbud.go.id/programsekolahpenggerak/'
        );
        temp.href = temp.href.replace(/#SIM_PKB_URL#/g, preferensi?.simpkb);
      }

      if (temp && temp.key === 'microteaching') {
        temp.disable = Number(preferensi.peserta.k_proses_simulasi_psp) < 3;
        const wktSimulasi = duration(
          getDeepObj(preferensi, `config.peserta.simulasi.${preferensi?.peserta?.gelombang ?? 1}.tgl_buka`),
          getDeepObj(preferensi, `config.peserta.simulasi.${preferensi?.peserta?.gelombang ?? 1}.tgl_tutup`)
        );
        temp.desc = `Tes ini akan berlangsung pada tanggal <b>${wktSimulasi || 'menunggu jadwal dari Pusat'}</b>`;
      }

      return temp;
    });
    return menus;
  }

  function getMenuIns(preferensi) {
    const roleMenu = roleMenus['instansi'];

    const menus = roleMenu.map((item) => {
      let temp = Object.assign({}, item);
      if (temp && temp.href) {
        temp.href = temp.href.replace(
          /#PORTAL_URL#/g,
          'https://sekolah.penggerak.kemdikbud.go.id/programsekolahpenggerak/'
        );
        temp.href = temp.href.replace(/#SIM_PKB_URL#/g, preferensi?.simpkb);
      }

      return temp;
    });
    return menus;
  }

  async function setMenus(preferensi) {
    if (store.state.menus && store.state.menus.length) return store.state.menus;

    // get menu by role dan tahap
    const role = store.state.auth.role;
    const menus = role === 'gtk' ? getMenuGtk(preferensi) : getMenuIns(preferensi);

    // store menus
    await store.commit('SET_MENUS', menus);
    return menus;
  }

  function getObjMenu(menus, name) {
    if (!name || !menus.length) return {};

    const parent = menus.filter((item) => item.key === name);
    if (parent.length) return parent && parent[0];

    let children = [];
    let i = 0;
    for (i; i < menus.length; i++) {
      if (menus[i]?.children) children.push(menus[i]?.children);
    }
    if (children.length) return children.filter((item) => item.key === name)[0] ?? {};
  }

  function redirectToLogin() {
    window.location.href = process.env.VUE_APP_API_URL + `/auth/login`;
  }

  isUserLogin()
    .then(async () => {
      const role = store.state.auth.role;
      // register route
      if (!initRoute) {
        const modules = await import(`./routes/${role}`);
        resetRouter(modules.default);
        initRoute = true;
      }

      // to akses tidak perlu id
      if (to.name === 'access') return next();

      // normalisasi path setelah login
      const path = to.redirectedFrom ?? to.fullPath;
      const routeId = (to.params && to.params.id) || (path.match(/\d+/g) && path.match(/\d+/g)[0]) || '';
      const id = routeId || store.getters['auth/instansiId'];

      const preferensi = await getPreferensi(id);
      const menus = await setMenus(preferensi);
      const currMenu = getObjMenu(menus, to.name);

      const params = role === 'instansi' ? { id } : {};
      // cek akses menu yang dituju
      if (role === 'instansi' && !routeId) return next({ name: 'home', params });
      if (isObject(currMenu) && (!currMenu?.akses || currMenu?.disable)) return next({ name: 'home', params });
      return to.name ? next() : ['/', `/i/${id}/home`].includes(path) ? next({ name: 'home', params }) : next(path);
    })
    .catch(() => {
      redirectToLogin();
    });
});

router.beforeResolve(async (to, from, next) => {
  // Create a `beforeResolve` hook, which fires whenever
  // `beforeRouteEnter` and `beforeRouteUpdate` would. This
  // allows us to ensure data is fetched even when params change,
  // but the resolved route does not. We put it in `meta` to
  // indicate that it's a hook we created, rather than part of
  // Vue Router (yet?).
  try {
    // For each matched route...
    for (const route of to.matched) {
      await new Promise((resolve, reject) => {
        // If a `beforeResolve` hook is defined, call it with
        // the same arguments as the `beforeEnter` hook.
        if (route.meta && route.meta.beforeResolve) {
          route.meta.beforeResolve(to, from, (...args) => {
            // If the user chose to redirect...
            if (args.length) {
              // If redirecting to the same route we're coming from...
              if (from.name === args[0].name) {
                // Complete the animation of the route progress bar.
              }
              // Complete the redirect.
              next(...args);
              reject(new Error('Redirected'));
            } else {
              resolve();
            }
          });
        } else {
          // Otherwise, continue resolving the route.
          resolve();
        }
      });
    }
    // If a `beforeResolve` hook chose to redirect, just return.
  } catch (error) {
    return;
  }

  // If we reach this point, continue resolving the route.
  next();
});

// When each route is finished evaluating...
router.afterEach(() => {
  // Complete the animation of the route progress bar.
  store.commit('SET_SPLASH', false);
});

export default router;
