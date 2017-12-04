// Project-specific Butler configuration.
var overrides = {};

overrides.repo = "git@github.com:MidCamp/Hatter.git";
overrides.develop_tasks = ['sass', 'spress-serve', 'spress-watch', 'watch'];
overrides.scss = ['../../src/sass/*.scss', '../../src/sass/**/*.scss'];
overrides.css = '../../src/content/assets/css/';
overrides.template_files = ['../../src/*.html', '../../src/**/*.html'];
overrides.output_dev = '../../build';
overrides.html_files = ['../../build/*.html', '../../build/**/*.html'];
overrides.output_prod = '../../build/**/*';

module.exports = overrides;
