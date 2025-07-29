import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/pages/Home.vue';
import Libros from '@/js/components/Books.vue';
import GestorLibros from '@/pages/GestorBooks.vue';
import Autores from '@/js/components/Autors.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/libros',
    name: 'libros',
    component: Libros,
  },
  {
    path: '/gestor-libros',
    name: 'gestor-libros',
    component: GestorLibros,
  },
  {
    path: '/Autores',
    name: 'Listado-Autores',
    component: Autores,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
