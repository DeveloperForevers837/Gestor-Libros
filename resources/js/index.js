import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Libros from '../js/components/Books.vue' 

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/libros', name: 'libros', component: Libros },  
  { path: '/libros/create', name: 'create', component: LibroForm },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router