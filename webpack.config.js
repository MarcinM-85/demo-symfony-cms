const Encore = require('@symfony/webpack-encore');
const { styles } = require( '@ckeditor/ckeditor5-dev-utils' );

Encore
    // katalog, gdzie będą zapisywane pliki wynikowe
    .setOutputPath(Encore.isProduction() ? 'public/webpack/' : 'public/dev/')
    // ścieżka publiczna używana w znacznikach <script src="">
    .setPublicPath(Encore.isProduction() ? '/webpack' : '/dev')

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
    // Use raw-loader for CKEditor 5 SVG files.
    .addRule( {
        test: /\.svg$/,
//        test: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
        loader: 'raw-loader'
    } )
    
    // Configure other image loaders to exclude CKEditor 5 SVG files.
    .configureLoaderRule( 'images', loader => {
        loader.exclude = /\.svg$/;
//        loader.exclude = /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/;
    } )

    // Configure PostCSS loader.
    .addLoader({
        test: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css$/,
        loader: 'postcss-loader',
        options: {
            postcssOptions: styles.getPostCssConfig( {
                themeImporter: {
                    themePath: require.resolve( '@ckeditor/ckeditor5-theme-lark' )
                },
                minify: true
            } )
        }
    } )
;

module.exports = Encore.getWebpackConfig();