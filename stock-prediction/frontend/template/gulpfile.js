const {series, src, dest, parallel, watch} = require("gulp");
const autoprefixer = require("gulp-autoprefixer");
const browsersync = require("browser-sync");
const CleanCSS = require("gulp-clean-css");
const concat = require("gulp-concat");
const fileinclude = require("gulp-file-include");
const del = require("del");
const newer = require("gulp-newer");
const rename = require("gulp-rename");
const sourcemaps = require("gulp-sourcemaps");
const sass = require("gulp-sass")(require("sass"));
const uglify = require("gulp-uglify");
const npmdist = require("gulp-npm-dist");
const rtlcss = require("gulp-rtlcss");

const paths = {
    baseSrc: "public/",                        // source directory
    baseDistAssets: "public/",          // build assets directory
    baseSrcAssets: "public/source/",    // source assets directory

    plugin: {
        styles: [
            "./node_modules/dropzone/dist/min/dropzone.min.css",
            "./node_modules/flatpickr/dist/flatpickr.css",
            "./node_modules/swiper/swiper-bundle.min.css",
            "./node_modules/sweetalert2/dist/sweetalert2.min.css",
            "./node_modules/choices.js/public/assets/styles/choices.min.css",
            "./node_modules/nouislider/dist/nouislider.min.css",
            "./node_modules/multi.js/dist/multi.min.css",
            "./node_modules/quill/dist/quill.core.css",
            "./node_modules/quill/dist/quill.bubble.css",
            "./node_modules/quill/dist/quill.snow.css",
        ],

        scripts: [
            "./node_modules/bootstrap/dist/js/bootstrap.bundle.js",
            "./node_modules/simplebar/dist/simplebar.min.js",
            "./node_modules/gumshoejs/dist/gumshoe.polyfills.js",
            "./node_modules/apexcharts/dist/apexcharts.min.js",
            "./node_modules/prismjs/prism.js",
            "./node_modules/prismjs/plugins/normalize-whitespace/prism-normalize-whitespace.js",
            "./node_modules/toastify-js/src/toastify.js",
            "./node_modules/dragula/dist/dragula.js",
            "./node_modules/vanilla-wizard/dist/js/wizard.min.js",
            "./node_modules/clipboard/dist/clipboard.min.js",
            "./node_modules/moment/moment.js",
            "./node_modules/dropzone/dist/min/dropzone.min.js",
            "./node_modules/flatpickr/dist/flatpickr.js",
            "./node_modules/swiper/swiper-bundle.min.js",
            "./node_modules/rater-js/index.js",
            "./node_modules/sweetalert2/dist/sweetalert2.min.js",
            "./node_modules/inputmask/dist/inputmask.min.js",
            "./node_modules/choices.js/public/assets/scripts/choices.min.js",
            "./node_modules/nouislider/dist/nouislider.min.js",
            "./node_modules/multi.js/dist/multi.min.js",
            "./node_modules/quill/dist/quill.min.js",
            "./node_modules/wnumb/wNumb.min.js",
            "./node_modules/iconify-icon/dist/iconify-icon.min.js",
            "./node_modules/masonry-layout/dist/masonry.pkgd.min.js",
        ]
    },

};

const plugins = function () {

    const outcss = paths.baseDistAssets + "css/";

    src(paths.plugin.styles)
        .pipe(concat("vendor.min.css"))
        .pipe(CleanCSS())
        .pipe(dest(outcss));

    const outjs = paths.baseDistAssets + "js/";

    src(paths.plugin.scripts)
        .pipe(concat("vendor.js"))
        .pipe(dest(outjs))
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(dest(outjs));

    const out = paths.baseDistAssets + "vendor/";
    return src(npmdist(), {base: "./node_modules"})
        .pipe(rename(function (path) {
            path.dirname = path.dirname.replace(/\/dist/, '').replace(/\\dist/, '');
        }))
        .pipe(dest(out));
};


const data = function () {
    const out = paths.baseDistAssets + "data/";
    return src([paths.baseSrcAssets + "data/**/*"])
        .pipe(dest(out));
};

const fonts = function () {
    const out = paths.baseDistAssets + "fonts/";
    return src([paths.baseSrcAssets + "fonts/**/*"])
        .pipe(newer(out))
        .pipe(dest(out));
};

const images = function () {
    var out = paths.baseDistAssets + "images";
    return src(paths.baseSrcAssets + "images/**/*")
        .pipe(newer(out))
        .pipe(dest(out));
};


const javascript = function () {
    const out = paths.baseDistAssets + "js/";

    src([paths.baseSrcAssets + "js/app.js", paths.baseSrcAssets + "js/layout.js"])
        .pipe(concat("app.js"))
        .pipe(dest(out))
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(dest(out))

    src([paths.baseSrcAssets + "js/config.js"])
        .pipe(dest(out))
        .pipe(uglify())
        .pipe(rename({suffix: ".min"}))
        .pipe(dest(out));


    return src([paths.baseSrcAssets + "js/**/*", '!' + paths.baseSrcAssets + "js/app.js", '!' + paths.baseSrcAssets + "js/layout.js", '!' + paths.baseSrcAssets + "js/config.js"])
        .pipe(uglify())
        .pipe(dest(out));

};

const scss = function () {
    const out = paths.baseDistAssets + "css/";

    src(paths.baseSrcAssets + "scss/app.scss")
        .pipe(sourcemaps.init())
        .pipe(sass.sync().on('error', sass.logError)) // scss to css
        .pipe(
            autoprefixer({
                overrideBrowserslist: ["last 2 versions"],
            })
        )
        .pipe(dest(out))
        .pipe(CleanCSS())
        .pipe(rename({suffix: ".min"}))
        .pipe(sourcemaps.write("./"))
        .pipe(dest(out));

    // generate rtl
    return src(paths.baseSrcAssets + "scss/app.scss")
        .pipe(sourcemaps.init())
        .pipe(sass.sync().on('error', sass.logError)) // scss to css
        .pipe(
            autoprefixer({
                overrideBrowserslist: ["last 2 versions"],
            })
        )
        .pipe(rtlcss())
        .pipe(rename({suffix: "-rtl"}))
        .pipe(dest(out))
        .pipe(CleanCSS())
        .pipe(rename({suffix: ".min"}))
        .pipe(sourcemaps.write("./"))
        .pipe(dest(out));
};

const icons = function () {
    const out = paths.baseDistAssets + "css/";
    return src(paths.baseSrcAssets + "scss/icons.scss")
        .pipe(sourcemaps.init())
        .pipe(sass.sync().on('error', sass.logError)) // scss to css
        .pipe(
            autoprefixer({
                overrideBrowserslist: ["last 2 versions"],
            })
        )
        .pipe(dest(out))
        .pipe(CleanCSS())
        .pipe(rename({suffix: ".min"}))
        .pipe(sourcemaps.write("./"))
        .pipe(dest(out));
};


const reloadBrowserSync = function (done) {
    browsersync.reload();
    done();
}

// File Watch Task
function watchFiles() {
    watch(paths.baseSrcAssets + "data/**/*", series(data, reloadBrowserSync));
    watch(paths.baseSrcAssets + "fonts/**/*", series(fonts, reloadBrowserSync));
    watch(paths.baseSrcAssets + "images/**/*", series(images, reloadBrowserSync));
    watch(paths.baseSrcAssets + "js/**/*", series(javascript, reloadBrowserSync));
    watch([paths.baseSrcAssets + "scss/icons/*.scss", paths.baseSrcAssets + "scss/icons.scss"], series(icons, reloadBrowserSync));
    watch([paths.baseSrcAssets + "scss/**/*.scss", "!" + paths.baseSrcAssets + "scss/icons.scss"], series(scss, reloadBrowserSync));
}

// Production Tasks
exports.default = series(
    parallel(plugins, data, fonts, images, javascript, scss, icons),
    parallel(watchFiles)
);

// Build Tasks
exports.build = series(
    plugins,
    parallel(data, fonts, images, javascript, scss, icons)
);
