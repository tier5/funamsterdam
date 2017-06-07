module.exports = () ->

  @initConfig
    watch:
      options:
        livereload: 12344
      css:
        files: [
          'css/sass/*.scss'
          'css/sass/**/*.scss'
        ]
        tasks: [ 'sass', 'concat:initial', 'cssmin', 'concat:header' ]
      js:
        files: [
          'Gruntfile.*'
          'js/**/*.coffee',
          'js/app/*.js',
          'inc/**/*.coffee'
        ]
        tasks: [ 'coffee', 'uglify' ]

    sass:
      dist:
        options:
          style: 'compressed'
        files:
          'css/style.css': 'css/sass/style.scss'

    concat:
      initial:
        files:
          'css/style.css': [ 'css/vendor/**/*.css', 'js/vendor/**/*.css', 'css/style.css']
      header:
        files:
          'style.css': [ 'css/_theme.css', 'css/style.min.css' ]

    cssmin:
      dist:
        files: 'css/style.min.css': [ 'css/style.css' ]

    coffee:
      dist:
        files:
          'js/download/download.js': 'js/download/download.coffee'
          'js/page-header-video/page-header-video-admin.js': 'js/page-header-video/page-header-video-admin.coffee'
          'js/page-header-video/page-header-video.js': 'js/page-header-video/page-header-video.coffee'
          'js/widgets/featured-popular.js': 'js/widgets/featured-popular.coffee'
          'js/widgets/testimonials.js': 'js/widgets/testimonials.coffee'
          'inc/integrations/facetwp/js/facetwp.js': 'inc/integrations/facetwp/js/facetwp.coffee'

    uglify:
      dist:
        options:
          sourceMap: true
        files:
          'js/marketify.min.js': [
            'js/vendor/**/*.js'
            'js/app/marketify.js'
            'js/widgets/featured-popular.js'
            'js/widgets/testimonials.js'
            'js/page-header-video/page-header-video.js'
            '!js/vendor/salvattore/*.js'
          ]

    makepot:
      theme:
        options:
          type: 'wp-theme'

    exec:
      txpull:
        cmd: 'tx pull -a --minimum-perc=75'
      txpush:
        cmd: 'tx push -s'

    potomo:
      dist:
        options:
          poDel: false 
        files: [
          expand: true
          cwd: 'languages'
          src: ['*.po']
          dest: 'languages'
          ext: '.mo'
          nonull: true
        ]

    checktextdomain:
      dist:
        options:
          text_domain: 'marketify'
          keywords: [
            '__:1,2d'
            '_e:1,2d'
            '_x:1,2c,3d'
            'esc_html__:1,2d'
            'esc_html_e:1,2d'
            'esc_html_x:1,2c,3d'
            'esc_attr__:1,2d'
            'esc_attr_e:1,2d'
            'esc_attr_x:1,2c,3d'
            '_ex:1,2c,3d'
            '_n:1,2,4d'
            '_nx:1,2,4c,5d'
            '_n_noop:1,2,3d'
            '_nx_noop:1,2,3c,4d'
          ]
        files: [{
          src: [ '**/*.php' ]
          expand: true
        }]

  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-coffee'
  @loadNpmTasks 'grunt-contrib-uglify'
  @loadNpmTasks 'grunt-contrib-sass'
  @loadNpmTasks 'grunt-contrib-cssmin'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-wp-i18n'
  @loadNpmTasks 'grunt-exec'
  @loadNpmTasks 'grunt-potomo'
  @loadNpmTasks 'grunt-checktextdomain'

  @registerTask 'default', ['watch']

  @registerTask 'getTranslations', [ 'exec:txpull', 'potomo' ]
  @registerTask 'pushTranslation', [ 'makepot', 'exec:txpush' ]
  @registerTask 'checkTranslation', [ 'checktextdomain' ]

  @registerTask 'build', [ 'uglify', 'coffee', 'sass', 'concat:initial', 'cssmin', 'concat:header', 'getTranslations', 'pushTranslation' ]
