const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  // directory where compiled assets will be stored
  .setOutputPath('public/build/')
  // public path used by the web server to access the output path
  .setPublicPath('/build')

  .addEntry('app', './assets/app.js')

  .splitEntryChunks()

  .enableSingleRuntimeChunk()

  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())

  .enableVersioning(Encore.isProduction())

  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = '3.23';
  })

  .enableSassLoader()

  .copyFiles({
    from: './assets/images',

    to: 'images/[path][name].[ext]',
  })
;

module.exports = Encore.getWebpackConfig();
