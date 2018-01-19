/**
 * Bootstrapping
 */
require('es5-shim')
window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.Tether = require('tether')
window.Waves = require('node-waves')
window.NProgress = require('nprogress')
window.Lazy = require('jquery-lazy')

window.moment = require('moment')
window.bootstrapMaterialDatePicker = require('../../../node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker')

window.swal = require('sweetalert2')

require('bootstrap')
require('../../../node_modules/mdbootstrap/js/mdb.js')
require('./vendor/collapsible')

require('./own.js')

