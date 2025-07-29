import './bootstrap';
import { createApp } from 'vue';
import App from './app.vue';
import router from './router';
import Books from './components/Books.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

const app = createApp(App);

app.use(router);
app.component('books-list', Books);

app.mount('#app');