import Vue from 'vue';
import Router from 'vue-router';
import store from '@store';
import { isObject } from '@utils/format';
import routesAuth from './routes/auth';
import MenusGtk from '@configs/menus.gtk';
import MenusIns from '@configs/menus.instansi';

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

// Page yang perlu login
// perlu pengecekan user login
async function isUserLogin() {
  return await store.dispatch('auth/checkUser');
}

async function getPreferensi(id) {
  return await store.dispatch('auth/userPreferensi', id);
}

function getMenuGtk(preferensi) {
  const roleMenu = roleMenus['gtk'];

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

let initRoute = false;
// Before each route evaluates
router.beforeEach((to, from, next) => {
  // Semua bisa akses, karena page yang dituju tidak perlu login
  if (to.meta?.public) return next();

  isUserLogin().then(async () => {
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

    const preferensi = await getPreferensi(id || '');
    console.log(preferensi);

    const menus = await setMenus(preferensi);
    const currMenu = getObjMenu(menus, to.name);

    const params = role === 'instansi' ? { id } : {};
    // cek akses menu yang dituju
    if (role === 'instansi' && !routeId) return next({ name: 'home', params });
    if (isObject(currMenu) && (!currMenu?.akses || currMenu?.disable)) return next({ name: 'home', params });
    return to.name ? next() : ['/', `/i/${id}/home`].includes(path) ? next({ name: 'home', params }) : next(path);
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
