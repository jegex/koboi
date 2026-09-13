import Nova from './nova.js'
import * as NovaUtil from './util/index.js'

window.LaravelNovaUtil = NovaUtil
window.createNovaApp = config => new Nova(config)