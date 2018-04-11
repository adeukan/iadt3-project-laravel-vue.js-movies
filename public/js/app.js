/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 12);
/******/ })
/************************************************************************/
/******/ ({

/***/ 12:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(13);
module.exports = __webpack_require__(60);


/***/ }),

/***/ 13:
/***/ (function(module, __webpack_exports__) {

"use strict";
throw new Error("Module build failed: SyntaxError: E:/Github/movies/resources/assets/js/app.js: Unexpected token, expected , (41:8)\n\n\u001b[0m \u001b[90m 39 | \u001b[39m    el\u001b[33m:\u001b[39m \u001b[32m'#app'\u001b[39m\u001b[33m,\u001b[39m\t\t\t\t\u001b[90m// <app> is the html element associated with Vue object\u001b[39m\n \u001b[90m 40 | \u001b[39m    components\u001b[33m:\u001b[39m { \t\u001b[33mApp\u001b[39m \n\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m 41 | \u001b[39m    \t\t\t\t\u001b[33mSlick\u001b[39m\u001b[33m:\u001b[39m \u001b[33mSlick\u001b[39m}\u001b[33m,\u001b[39m\t\u001b[90m// here we can register many components for <app> element\u001b[39m\n \u001b[90m    | \u001b[39m    \t\t\t\t\u001b[31m\u001b[1m^\u001b[22m\u001b[39m\n \u001b[90m 42 | \u001b[39m    slick\u001b[33m,\u001b[39m\t\t\t\t\t\u001b[90m// slick\u001b[39m\n \u001b[90m 43 | \u001b[39m    router\t\t\t\t\t\u001b[90m// Vue router\u001b[39m\n \u001b[90m 44 | \u001b[39m})\u001b[33m;\u001b[39m\u001b[0m\n");

/***/ }),

/***/ 60:
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })

/******/ });