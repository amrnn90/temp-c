/* eslint-disable no-undef */

const path = require("path");
const run = require("@amrnn/vuepack");

const options = {
  /** source path */
  srcPath: path.join(__dirname, "frontend"),

  /** output path (Must be absolute) */
  outputAbsPath: path.resolve(__dirname, "public"),

  /** name for js bundle */
  jsBundleName: "admin.js",

  /** name for css bundle */
  cssBundleName: "admin.css",

  /** entry file (relative to srcPath) */
  entry: "app.js",

  /** index template used by HtmlWebPackPlugin (relative to srcPath) */
  indexTemplate: null,

  /** path for files that should just get copied to output (relative to srcPath) */
  staticPath: "static",

  /** proxies: each item is an array of: [from, target] */
  proxies: [["*", "http://localhost:8000"]],

  extendConfig: (config) => {
    config.module.rules = config.module.rules.map((rule) => {
      if (rule.exclude && rule.exclude.toString().match(/node_modules/)) {
        rule.exclude = [/node_modules/, /vue-form/];
      }
      return rule;
    });

    config.resolve.modules = [
      "node_modules",
      path.resolve(__dirname, "node_modules"),
    ];

    return config;
  },
};

run(options);
