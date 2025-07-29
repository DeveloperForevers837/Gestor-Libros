<template>
  <div class="container my-5">
    <h2 class="text-center mb-4 fw-bold text-dark">Libros Disponibles</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div v-for="libro in libros" :key="libro.id" class="col">
        <div class="card h-100 border-0 shadow-sm rounded-3">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-primary fw-semibold">{{ libro.titulo }}</h5>
            <p class="card-text text-secondary mb-2">
  Autor(es):
  <span v-if="libro.autores && libro.autores.length > 0">
    {{ libro.autores.map(a => a.nombre + ' ' + a.apellido).join(', ') }}
  </span>
  <span v-else>No registrado</span>
</p>
            <div class="mt-auto">
              <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">
                Año: {{ libro.anio_publicacion }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <p v-if="libros.length === 0" class="text-center text-muted mt-5">
      No hay libros disponibles.
    </p>
  </div>
</template>





<script setup>
import { ref, onMounted } from 'vue';

const libros = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('/api/libros');
    if (!response.ok) throw new Error('Error al obtener libros');
    libros.value = await response.json();
  } catch (error) {
    console.error('Error al obtener libros:', error);
  }
});
</script>