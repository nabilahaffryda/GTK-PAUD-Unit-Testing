const path = require('path');

module.exports = {
  transpileDependencies: ['vuetify'],
  devServer: {
    host: process.env.VUE_APP_HOST || null,
    port: process.env.VUE_APP_PORT || null,
    proxy: {
      ['^' + process.env.VUE_APP_API_URL]: {
        target: process.env.VUE_APP_PROXY_API_URL,
        ws: true,
        changeOrigin: true,
        pathRewrite: {
          ['^' + process.env.VUE_APP_API_URL]: '',
        },
      },
      '^/__clockwork': {
        target: process.env.VUE_APP_PROXY_API_URL + '__clockwork',
        ws: true,
        changeOrigin: true,
        pathRewrite: {
          '/__clockwork': '',
        },
      },
    },
  },
  configureWebpack: {
    // We provide the app's title in Webpack's name field, so that
    // it can be accessed in index.html to inject the correct title.
    name: process.env.VUE_APP_TITLE,
    // Set up all the aliases we use in our app.
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'),
        '@router': path.resolve(__dirname, 'src/router'),
        '@views': path.resolve(__dirname, 'src/views'),
        '@layouts': path.resolve(__dirname, 'src/layouts'),
        '@components': path.resolve(__dirname, 'src/components'),
        '@assets': path.resolve(__dirname, 'src/assets'),
        '@utils': path.resolve(__dirname, 'src/utils'),
        '@plugins': path.resolve(__dirname, 'src/plugins'),
        '@store': path.resolve(__dirname, 'src/store'),
        '@mixins': path.resolve(__dirname, 'src/mixins'),
        '@configs': path.resolve(__dirname, 'src/configs'),
      },
    },
  },
};
