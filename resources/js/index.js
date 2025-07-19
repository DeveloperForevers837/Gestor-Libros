import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import LibroForm from '../pages/LibroForm.vue'

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/libros/create', name: 'create', component: LibroForm },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router