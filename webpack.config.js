const Encore = require('@symfony/webpack-encore');

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
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    .addEntry('collection', './public/js/collection.js')
    .addEntry('select2', './public/js/select2.js')
    .addEntry('offresecteurs', './public/js/offresecteurs.js')
    .addEntry('usersecteurs', './public/js/usersecteurs.js')
    .addEntry('secteurs', './public/js/secteurs.js')
    .addEntry('mce', './public/js/tinymce.js')
    .addEntry('loadbtn', './public/js/loadbtn.js')
    .addEntry('favoris', './public/js/favoris.js')
    .addEntry('desablebtn', './public/js/desablebtn.js')
    .addEntry('annonceimg', './public/js/annonceimg.js')
    .addEntry('annoncecategorie', './public/js/annoncecategorie.js')
    .addEntry('articleimg', './public/js/articleimg.js')
    .addEntry('postimg', './public/js/postimg.js')
    .addEntry('hideflash', './public/js/hideflash.js')
    .addEntry('avis', './public/js/avis.js')
    .addEntry('desactive', './public/js/desactive.js')
    .addEntry('googlemap', './public/js/googlemap.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .addStyleEntry('responsive', './assets/styles/responsive.css')

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

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
