const Encore = require('@symfony/webpack-encore');
const path = require('path')

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.ts')
    /* Base files */
    .addEntry('script/user', './assets/scripts/context/user/main.user.ts')
    .addEntry('script/watch/rfc', './assets/scripts/context/watch/main.rfc.ts')
    .addEntry('script/watch/version', './assets/scripts/context/watch/main.version.ts')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    .enableBuildNotifications(false)

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // enable TypeScript
    .enableTypeScriptLoader()

    // Additional  Plugins
    .autoProvideVariables({
        // 'window.jQuery': 'jquery',
        // 'window.$': 'jquery',
    })
    .addAliases({
        '@common': path.resolve(__dirname, 'assets/scripts/common'),
        '@core': path.resolve(__dirname, 'assets/scripts/common/core'),
        '@component': path.resolve(__dirname, 'assets/scripts/common/component'),
        '@web-component': path.resolve(__dirname, 'assets/scripts/common/web-component'),
        '@context': path.resolve(__dirname, 'assets/scripts/context'),
        '@shared-context': path.resolve(__dirname, 'assets/scripts/context/shared'),
    })

// Export
module.exports = Encore.getWebpackConfig();
