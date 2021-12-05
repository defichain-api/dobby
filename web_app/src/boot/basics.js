// Delivers first entry of an array
Object.defineProperty(Array.prototype, 'first', {
  value() {
    return this.find(e => true)     // or this.find(Boolean)
  }
})
