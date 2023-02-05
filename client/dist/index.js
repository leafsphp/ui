
'use strict'

if (process.env.NODE_ENV === 'production') {
  module.exports = require('./ui.cjs.production.min.js')
} else {
  module.exports = require('./ui.cjs.development.js')
}
