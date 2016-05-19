'use strict';
module.exports = function(grunt) {
  // Load all tasks
  require('load-grunt-tasks')(grunt);
  // Show elapsed time
  require('time-grunt')(grunt);

  var basePrefix = 'wp-content/themes/lsb-base-theme/';

  var jsBaseFileList = [
    basePrefix + 'assets/vendor/bootstrap/js/transition.js',
    basePrefix + 'assets/vendor/bootstrap/js/alert.js',
    basePrefix + 'assets/vendor/bootstrap/js/button.js',
    basePrefix + 'assets/vendor/bootstrap/js/carousel.js',
    basePrefix + 'assets/vendor/bootstrap/js/collapse.js',
    basePrefix + 'assets/vendor/bootstrap/js/dropdown.js',
    basePrefix + 'assets/vendor/bootstrap/js/modal.js',
    basePrefix + 'assets/vendor/bootstrap/js/tooltip.js',
    basePrefix + 'assets/vendor/bootstrap/js/popover.js',
    basePrefix + 'assets/vendor/bootstrap/js/scrollspy.js',
    basePrefix + 'assets/vendor/bootstrap/js/tab.js',
    basePrefix + 'assets/vendor/bootstrap/js/affix.js',
    basePrefix + 'assets/js/plugins/*.js',
    basePrefix + 'assets/js/_*.js'
  ];

  var lessDevBaseFiles = {};
  lessDevBaseFiles[basePrefix + 'assets/css/main.css'] = [ basePrefix + 'assets/less/main.less'];

  var lessBuildFiles = {};
  lessBuildFiles[basePrefix + 'assets/css/main.min.css'] = [ basePrefix + 'assets/less/main.less'];

  var uglifyFiles = {};
  uglifyFiles[basePrefix + 'assets/js/scripts.min.js'] = [jsBaseFileList];

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        basePrefix + 'assets/js/*.js',
        '!' + basePrefix + 'assets/js/scripts.js',
        '!' + basePrefix + 'assets/**/*.min.*'
      ]
    },
    less: {
      devBase: {
        files: lessDevBaseFiles,
        options: {
          compress: false,
          // LESS source map
          // To enable, set sourceMap to true and update sourceMapRootpath based on your install
          sourceMap: true,
          sourceMapFilename: basePrefix + 'assets/css/main.css.map'
          //sourceMapRootpath: basePrefix + 'assets/css/'
        }
      },
      buildBase: {
        files: lessBuildFiles,
        options: {
          compress: true
        }
      }
    },
    concat: {
      options: {
        separator: ';',
      },
      base: {
        src: jsBaseFileList,
        dest: basePrefix + 'assets/js/scripts.js',
      }
    },
    uglify: {
      dist: {
        files: uglifyFiles
      }
    },
    autoprefixer: {
      options: {
        browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
      },
      devBase: {
        options: {
          map: {
            prev: basePrefix + 'assets/css/'
          }
        },
        src: basePrefix + 'assets/css/main.css'
      },
      buildBase: {
        src: basePrefix + 'assets/css/main.min.css'
      }
    },
    modernizr: {
      base: {
        devFile: basePrefix + 'assets/vendor/modernizr/modernizr.js',
        outputFile: basePrefix + 'assets/js/vendor/modernizr.min.js',
        files: {
          'src': [
            [basePrefix + 'assets/js/scripts.min.js'],
            [basePrefix + 'assets/css/main.min.css']
          ]
        },
        uglify: true,
        parseFiles: true
      }
    },
    version: {
      base: {
        options: {
          format: true,
          length: 32,
          manifest: basePrefix + 'assets/manifest.json',
          querystring: {
            style: 'roots_css',
            script: 'roots_js'
          }
        },
        files: {
         'wp-content/themes/lsb-base-theme/lib/scripts.php': basePrefix + 'assets/{css,js}/{main,scripts}.min.{css,js}'
        }
      }
    },
    watch: {
      less: {
        files: [
          basePrefix + 'assets/less/*.less',
          basePrefix + 'assets/less/**/*.less'
        ],
        tasks: ['less:devBase', 'autoprefixer:devBase',]
      },
      js: {
        files: [
          jsBaseFileList,
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'concat']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          basePrefix + 'assets/css/main.css',
          basePrefix + 'assets/js/scripts.js',
          basePrefix + 'templates/*.php',
          basePrefix + '*.php',
        ]
      }
    }
  });

  // Register tasks
  grunt.registerTask('default', [
    'dev'
  ]);
  grunt.registerTask('dev', [
    'jshint',
    'less:devBase',
    'autoprefixer:devBase',
    'concat:base',
  ]);
  grunt.registerTask('dev-base', [
    'jshint',
    'less:devBase',
    'autoprefixer:devBase',
    'concat:base',
  ]);
  grunt.registerTask('build', [
    'jshint',
    'less:buildBase',
    'autoprefixer:buildBase',
    'uglify',
    'modernizr:base',
    'version'
  ]);
};
