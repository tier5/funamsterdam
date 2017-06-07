'use strict';
module.exports = function(grunt) {

	grunt.initConfig({

		dirs: {
			js: 'js',
			css: 'css'
		},

		// watch for changes and trigger compass, jshint, uglify and livereload
		watch: {
			options: {
				livereload: true,
			},
			js: {
				files: [
					'js/vendor/*.js',
					'js/app/*.js'
				],
				tasks: ['uglify']
			},
			css: {
				files: [
					'<%= dirs.css %>/sass/*.scss',
					'<%= dirs.css %>/vendor/*.css'
				],
				tasks: ['sass', 'concat', 'clean']
			}
		},

		// uglify to concat, minify, and make source maps
		uglify: {
			dist: {
				options: {
					sourceMap: true
				},
				files: {
					'js/vendor.min.js': [ 'js/vendor/*.js' ],
					'js/marketify.min.js': [
						'js/vendor/*.js',
						'js/app/marketify.js'
					],
				}
			}
		},

		sass: {
			dist: {
        files: {
					'css/style.css' : 'css/sass/style.scss'
				}
			}
		},

		concat: {
			initial: {
				files: {
					'css/vendor.css': ['css/vendor/*.css'],
					'css/style.min.css': [ 'css/vendor.css', 'css/style.css']
				}
			},
			header: {
				files: {
					'style.css': ['css/_theme.css', 'css/style.min.css' ]
				}
			}
		},

		clean: {
			dist: {
				src: [
					'css/style.css',
					'css/style.min.css',
					'css/vendor.css',
					'js/vendor.min.js',
					'js/vendor.min.map'
				]
			}
		},

		makepot: {
			theme: {
				options: {
					type: 'wp-theme'
				}
			}
		},

		exec: {
			txpull: {
				cmd: 'tx pull -a --minimum-perc=75'
			},
			txpush_s: {
				cmd: 'tx push -s'
			},
		},

		potomo: {
			dist: {
				options: {
					poDel: false // Set to true if you want to erase the .po
				},
				files: [{
					expand: true,
					cwd: 'languages',
					src: ['*.po'],
					dest: 'languages',
					ext: '.mo',
					nonull: true
				}]
			}
		}
	});

	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-exec' );
	grunt.loadNpmTasks( 'grunt-potomo' );

	// register task
	grunt.registerTask('default', ['watch']);

	grunt.registerTask( 'tx', ['exec:txpull', 'potomo']);
	grunt.registerTask( 'makeandpush', ['makepot', 'exec:txpush_s']);

	grunt.registerTask('build', ['uglify', 'sass', 'concat', 'clean', 'makepot', 'tx', 'makeandpush']);

};
