const Encore = require('@symfony/webpack-encore');

Encore
    // katalog, gdzie będą zapisywane pliki wynikowe
    .setOutputPath(Encore.isProduction() ? 'public/build/' : 'public/build/dev/')
    // ścieżka publiczna używana w znacznikach <script src="">
    .setPublicPath(Encore.isProduction() ? '/build' : '/build/dev')

    // główne pliki wejściowe
    .addEntry('app', './assets/app.js')       // frontend (jeśli używasz)
    .addEntry('admin', './assets/admin.js')   // panel administracyjny

    // pojedynczy runtime (dla optymalizacji)
    .enableSingleRuntimeChunk()

    // pozwala korzystać z Stimulus, jeśli używasz
    //.enableStimulusBridge('./assets/controllers.json')

    // SASS/SCSS
    .enableSassLoader()
    .enablePostCssLoader()

     // ułatwia debugowanie
    .enableSourceMaps(!Encore.isProduction())
    
    // wersjonowanie plików (np. app.abc123.js)
    .enableVersioning(Encore.isProduction())

    // czyści katalog public/build przed każdym buildem
    .cleanupOutputBeforeBuild()

    // pokazuje notyfikacje systemowe o błędach
    .enableBuildNotifications()

    // wsparcie dla Babel (nowoczesny JS)
    .configureBabel((config) => {
//        config.presets.push(['@babel/preset-env']);
    })

    // automatyczne dodawanie polyfilli
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

module.exports = Encore.getWebpackConfig();