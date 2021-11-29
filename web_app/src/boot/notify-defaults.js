import { Notify } from 'quasar'

Notify.setDefaults({
  position: 'top',
  timeout: 2500,
  textColor: 'white',
  color: 'blue',
  actions: [{ icon: 'fad fa-times-circle', color: 'white' }],
  progress: true,
})

Notify.registerType('positive', {
  icon: 'fas fa-check',
  color: 'accent',
})
