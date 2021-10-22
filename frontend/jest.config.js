module.exports = {
  transform: {
    "^.+\\.js$": "babel-jest",
    "^.+\\.vue$": "vue-jest"
  },
  preset: '@vue/cli-plugin-unit-jest',
  moduleFileExtensions: [
    'js',
    'vue',
    'jsx',
    'json',
    'node'
  ],
  moduleNameMapper: {
    '^vue$': 'vue/dist/vue.common.js',
    "^components/(.*)": "<rootDir>/src/components/$1",
    'assets/(.*)': '<rootDir>/src/assets',
    '^@/(.*)$': '<rootDir>/src/$1',
    '^~/(.*)$': '<rootDir>/$1',
    "^@utils/(.*)": "<rootDir>/src/utils/$1",
    "^@mixins/(.*)": "<rootDir>/src/mixins/$1",
    'vuetify/lib(.*)': '<rootDir>/node_modules/vuetify/es5$1',
    "\\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga)$": "<rootDir>/__mocks__/fileMock.js",
    "\\.(css|less|scss|sass)$": "identity-obj-proxy"
  },
  transformIgnorePatterns: [
    '/node_modules/(?!lib-to-transform|other-lib)',
    '<rootDir>/node_modules/(?!(vuetify)/)']
}