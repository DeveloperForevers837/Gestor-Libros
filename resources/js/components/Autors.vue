<template>
  <div class="container mt-5">
    <h1 class="mb-4 text-center">Gestor de Autores ✍️</h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
      <button class="btn btn-primary" @click="toggleFormulario">
        {{ mostrarFormulario ? 'Cancelar' : (editando ? 'Cancelar Edición' : '+ Agregar Autor') }}
      </button>
      
      <router-link to="/gestor-libros" class="btn btn-secondary">
        ← Volver a Gestor de Libros
      </router-link>
    </div>

    <!-- Formulario para agregar/editar autor -->
    <div v-if="mostrarFormulario" class="card card-body mb-4 shadow-sm">
      <form @submit.prevent="editando ? actualizarAutor() : agregarAutor()">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="nombre" class="form-label">Nombre</label>
            <input
              id="nombre"
              type="text"
              v-model.trim="nuevoAutor.nombre"
              class="form-control"
              required
              placeholder="Ej: Juan"
            />
          </div>
          <div class="col-md-4">
            <label for="apellido" class="form-label">Apellido</label>
            <input
              id="apellido"
              type="text"
              v-model.trim="nuevoAutor.apellido"
              class="form-control"
              required
              placeholder="Ej: Pérez"
            />
          </div>
          <div class="col-md-4">
            <label for="pais" class="form-label">País</label>
            <input
              id="pais"
              type="text"
              v-model.trim="nuevoAutor.pais"
              class="form-control"
              required
              placeholder="Ej: República Dominicana"
            />
          </div>
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-success">
            {{ editando ? 'Actualizar' : 'Guardar' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Mensajes -->
    <div v-if="mensajeSuccess" class="alert alert-success" role="alert">
      {{ mensajeSuccess }}
    </div>
    <div v-if="mensajeError" class="alert alert-danger" role="alert">
      {{ mensajeError }}
    </div>

    <!-- Tabla de autores -->
    <table class="table table-bordered table-hover text-center shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nombre Completo</th>
          <th>País</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="autor in autores" :key="autor.id">
          <td>{{ autor.id }}</td>
          <td>{{ autor.nombre }} {{ autor.apellido }}</td>
          <td>{{ autor.pais }}</td>
          <td>
            <button class="btn btn-blue btn-sm me-2" @click="verDetalles(autor)">🔍</button>
            <button class="btn btn-warning btn-sm me-2" @click="editarAutor(autor)">✏️</button>
            <button class="btn btn-danger btn-sm" @click="eliminarAutor(autor.id)">🗑️</button>
          </td>
        </tr>
        <tr v-if="autores.length === 0">
          <td colspan="4">No hay autores disponibles.</td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- Modal Detalles del Autor -->
<div v-if="autorSeleccionado" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del Autor</h5>
        <button type="button" class="btn-close" @click="autorSeleccionado = null"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nombre:</strong> {{ autorSeleccionado.nombre }}</p>
        <p><strong>Apellido:</strong> {{ autorSeleccionado.apellido }}</p>
        <p><strong>País:</strong> {{ autorSeleccionado.pais }}</p>
        <p><strong>Fecha de Registro:</strong> {{ autorSeleccionado.created_at }}</p>
        <p><strong>Cantidad de Libros:</strong> {{ autorSeleccionado.libros?.length || 0 }}</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" @click="autorSeleccionado = null">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import axios from 'axios';

export default {
  name: 'GestorAutores',
  data() {
    return {
      autores: [],
       autorSeleccionado: null,
      mostrarFormulario: false,
      editando: false,
      autorEditandoId: null,
      nuevoAutor: {
        nombre: '',
        apellido: '',
        pais: '',
      },
      mensajeSuccess: '',
      mensajeError: '',
    };
  },
  mounted() {
    this.obtenerAutores();
  },
  methods: {
    
    toggleFormulario() {
      this.mostrarFormulario = !this.mostrarFormulario;
      this.mensajeError = '';
      this.mensajeSuccess = '';
      if (!this.mostrarFormulario) this.limpiarFormulario();
      if (this.editando) this.cancelarEdicion();
    },
    verDetalles(autor) {
  this.autorSeleccionado = autor;
},
    async obtenerAutores() {
      try {
        // Se espera que backend filtre autores "soft deleted"
        const res = await axios.get('/api/autores');
        this.autores = res.data;
      } catch (error) {
        this.mensajeError = 'Error al obtener autores. Intente más tarde.';
        console.error(error);
      }
    },
    limpiarFormulario() {
      this.nuevoAutor = { nombre: '', apellido: '', pais: '' };
    },
    async agregarAutor() {
      this.mensajeError = '';
      this.mensajeSuccess = '';
      try {
        await axios.post('/api/autores', this.nuevoAutor);
        this.mensajeSuccess = 'Autor agregado correctamente.';
        this.limpiarFormulario();
        this.obtenerAutores();
        this.mostrarFormulario = false;
        // Ocultar mensaje después de 3 segundos
    setTimeout(() => {
      this.mensajeSuccess = '';
    }, 3000);
      } catch (error) {
        this.mensajeError = 'Error al agregar autor. Verifica los datos.';
        console.error(error);
      }
    },
    editarAutor(autor) {
      this.editando = true;
      this.autorEditandoId = autor.id;
      this.nuevoAutor = {
        nombre: autor.nombre,
        apellido: autor.apellido,
        pais: autor.pais,
      };
      this.mostrarFormulario = true;
      this.mensajeError = '';
      this.mensajeSuccess = '';
    },
    cancelarEdicion() {
      this.editando = false;
      this.autorEditandoId = null;
      this.limpiarFormulario();
    },
    async actualizarAutor() {
      this.mensajeError = '';
      this.mensajeSuccess = '';
      try {
        await axios.put(`/api/autores/${this.autorEditandoId}`, this.nuevoAutor);
        this.mensajeSuccess = 'Autor actualizado correctamente.';
        this.obtenerAutores();
        this.cancelarEdicion();
        this.mostrarFormulario = false;
        // Ocultar mensaje después de 3 segundos
    setTimeout(() => {
      this.mensajeSuccess = '';
    }, 3000);
      } catch (error) {
        this.mensajeError = 'Error al actualizar autor. Verifica los datos.';
        console.error(error);
      }
    },
    async eliminarAutor(id) {
      if (!confirm('¿Estás seguro de eliminar este autor? Esta acción es irreversible.')) return;
      this.mensajeError = '';
      this.mensajeSuccess = '';
      try {
        await axios.delete(`/api/autores/${id}`); // Suponiendo soft delete en backend
        this.mensajeSuccess = 'Autor eliminado correctamente.';
        this.obtenerAutores();
        // Ocultar mensaje después de 3 segundos
    setTimeout(() => {
      this.mensajeSuccess = '';
    }, 3000);
      } catch (error) {
        this.mensajeError = 'Error al eliminar autor.';
        console.error(error);
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 700px;
}
.table {
  border-radius: 6px;
}
.card-body {
  border-radius: 6px;
}
/* Estilo del fondo oscuro del modal */
.modal.fade.show.d-block {
  background-color: rgba(0, 0, 0, 0.5);
  position: fixed;
  inset: 0;
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Estilo del contenido del modal */
.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 600px;
  width: 100%;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

</style>
