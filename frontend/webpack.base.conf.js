module.exports = {
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': path.join(__dirname, '..', 'src'),
            'components': path.join(__dirname, '..', 'src', 'components')
        }
    }
}