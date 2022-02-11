
import { boot } from 'quasar/wrappers'
import VueChartkick from 'vue-chartkick'
import 'chartkick/chart.js'

VueChartkick.options = {
  thousands: ",",
  decimal: ".",
}

export default boot(({ app }) => {
  app.use(VueChartkick)
})
