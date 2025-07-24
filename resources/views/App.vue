<template>
  <div class="container mt-5">
    <h1 class="mb-4 text-center">Gestor de Libros 📚</h1>

    <button class="btn btn-primary mb-3" @click="abrirModalAgregar">+ Agregar libro</button>

    <table class="table table-bordered table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="libro in libros" :key="libro.id">
          <td>{{ libro.id }}</td>
          <td>{{ libro.titulo }}</td>
          <td>{{ libro.autor }}</td>
          <td>
            <button class="btn btn-sm btn-warning me-2" @click="abrirModalEditar(libro)">Editar</button>
            <button class="btn btn-sm btn-danger" @click="eliminarLibro(libro.id)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="libroModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="guardarLibro">
            <div class="modal-header">
              <h5 class="modal-title">{{ editando ? 'Editar Libro' : 'Agregar Libro' }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" class="form-control" v-model="form.titulo" />
                <div class="text-danger" v-if="errores.titulo">{{ errores.titulo }}</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Autor</label>
                <input type="text" class="form-control" v-model="form.autor" />
                <div class="text-danger" v-if="errores.autor">{{ errores.autor }}</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Guardar</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const libros = ref([])
const editando = ref(false)
const form = ref({ id: null, titulo: '', autor: '' })
const errores = ref({})

const obtenerLibros = async () => {
  const res = await axios.get('/api/libros')
  libros.value = res.data
}

const abrirModalAgregar = () => {
  editando.value = false
  form.value = { id: null, titulo: '', autor: '' }
  errores.value = {}
  mostrarModal()
}

const abrirModalEditar = (libro) => {
  editando.value = true
  form.value = { ...libro }
  errores.value = {}
  mostrarModal()
}

const guardarLibro = async () => {
  errores.value = {}

  if (!form.value.titulo) errores.value.titulo = 'El título es obligatorio.'
  if (!form.value.autor) errores.value.autor = 'El autor es obligatorio.'

  if (Object.keys(errores.value).length > 0) return

  try {
    if (editando.value) {
      await axios.put(`/api/libros/${form.value.id}`, form.value)
    } else {
      await axios.post('/api/libros', form.value)
    }
    ocultarModal()
    obtenerLibros()
  } catch (e) {
    console.error('Error al guardar:', e)
  }
}

const eliminarLibro = async (id) => {
  if (confirm('¿Estás seguro de que quieres eliminar este libro?')) {
    await axios.delete(`/api/libros/${id}`)
    obtenerLibros()
  }
}

const mostrarModal = () => {
  const modal = new bootstrap.Modal(document.getElementById('libroModal'))
  modal.show()
}

const ocultarModal = () => {
  const modalEl = document.getElementById('libroModal')
  const modal = bootstrap.Modal.getInstance(modalEl)
  modal.hide()
}

onMounted(obtenerLibros)
</script>

<style scoped>
h1 {
  color: #3a3a3a;
}
</style>
