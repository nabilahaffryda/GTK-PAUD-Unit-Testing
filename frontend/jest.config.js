module.exports = {
  transform: {
    "^.+\\.js$": "babel-jest",
    "^.+\\.vue$": "vue-jest"
  },
  preset: '@vue/cli-plugin-unit-jest',
  moduleFileExtensions: [
    'js',
    'vue'
  ],
  moduleNameMapper: {
    '^vue$': 'vue/dist/vue.common.js',
    '^src/components': '<rootDir>/src/components',
    'assets/(.*)': '<rootDir>/src/assets',
    '^@/(.*)$': '<rootDir>/src/$1',
    "~(.*)$": "<rootDir>/$1"
  },
  transformIgnorePatterns: ['/node_modules/(?!lib-to-transform|other-lib)']
}