// Delivers first entry of an array
Object.defineProperty(Array.prototype, 'first', {
  value() {
    return this.find(e => true)     // or this.find(Boolean)
  }
})
/*
// define a new console
var console = (function (oldCons) {
  return {
    log: function (text) {
      if (process.env.DEV) {
        oldCons.log(text)
      }
    },
    info: function (text) {
      if (process.env.DEV) {
        oldCons.info(text)
      }
    },
    warn: function (text) {
      if (process.env.DEV) {
        oldCons.warn(text)
      }
    },
    error: function (text) {
      if (process.env.DEV) {
        oldCons.error(text)
      }
    }
  }
}(window.console))

// redefine the default console
window.console = console
*/
