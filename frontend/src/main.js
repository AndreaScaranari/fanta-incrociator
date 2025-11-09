import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.min.css'  // Bootstrap CSS compilato
import 'bootstrap'                             // Bootstrap JS
import './assets/style/style.scss'
import App from './App.vue'
import router from './router'

const app = createApp(App)
app.use(router)
app.mount('#app')