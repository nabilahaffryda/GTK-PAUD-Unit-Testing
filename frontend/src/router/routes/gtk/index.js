const moduleCache = {};
let root = [];
(function updateModules() {
  // Allow us to dynamically require all Vuex module files.
  // https://webpack.js.org/guides/dependency-management/#require-context
  const requireModule = require.context(
    // Search for files in the current directory.
    '.',
    // Search for files in subdirectories.
    true,
    // Include any .js files that are not this file or a unit test.
    /^((?!index|\.unit\.).)*\.js$/
  );

  // For every Vuex module...
  requireModule.keys().forEach((fileName) => {
    const moduleDefinition = requireModule(fileName);
    // Skip the module during hot reload if it refers to the
    // same module definition as the one we have cached.
    if (moduleCache[fileName] === moduleDefinition) return;

    // Update the module cache, for efficient hot reloading.
    moduleCache[fileName] = moduleDefinition;

    // Get the module path as an array.

    root = [...root, ...moduleDefinition.default];
  });

  // If the environment supports hot reloading...
  if (module.hot) {
    // Whenever any Vuex module is updated...
    module.hot.accept(requireModule.id, () => {
      // Update `root` with the latest definitions.
      updateModules();
      // Trigger a hot update in the store.
      require('../../index').default.hotUpdate(root);
    });
  }
})();
export default root;
