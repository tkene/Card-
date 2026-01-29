import { createApp } from 'vue'
import { Quasar } from 'quasar'
import quasarLang from 'quasar/lang/fr'
import router from './router'
import App from './App.vue'

// Import Quasar styles
import 'quasar/dist/quasar.css'
import '@quasar/extras/material-icons/material-icons.css'

// Import Tailwind CSS
import './styles/main.css'
// Import common styles
import './styles/common.css'

const app = createApp(App)

app.use(Quasar, {
  plugins: {},
  lang: quasarLang,
})

app.use(router)

app.mount('#app')

