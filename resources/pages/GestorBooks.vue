<template>
  <div class="container mt-5">
    <h1 class="mb-4 text-center">Gestor de Libros 📚</h1>

    <!-- Botón para mostrar formulario -->
    <button class="btn btn-primary mb-3" @click="mostrarFormulario = true">
      + Agregar libro
    </button>
    <router-link to="/autores" class="btn btn-secondary mb-3 ms-2">
      Gestionar Autores
    </router-link>

    <!-- Mensajes de éxito o error -->
    <div v-if="mensajeSuccess" class="alert alert-success">{{ mensajeSuccess }}</div>
    <div v-if="mensajeError" class="alert alert-danger">{{ mensajeError }}</div>

    <!-- Formulario para agregar o editar libro -->
    <div v-if="mostrarFormulario" class="card card-body mb-4 shadow-sm">
      <form @submit.prevent="editando ? actualizarLibro() : agregarLibro()">
        <div class="row g-3">
          <!-- Título -->
          <div class="col-md-6">
            <label for="titulo" class="form-label">Título</label>
            <input
              id="titulo"
              type="text"
              v-model.trim="nuevoLibro.titulo"
              class="form-control"
              required
              placeholder="Ej: Cien años de soledad"
            />
          </div>

          <!-- Año de publicación -->
          <div class="col-md-6">
            <label for="anio_publicacion" class="form-label">Año de Publicación</label>
            <input
              id="anio_publicacion"
              type="number"
              v-model.number="nuevoLibro.anio_publicacion"
              class="form-control"
              required
              placeholder="Ej: 1967"
            />
          </div>

          <!-- Editorial -->
          <div class="col-md-6">
            <label for="editorial" class="form-label">Editorial</label>
            <input
              id="editorial"
              type="text"
              v-model.trim="nuevoLibro.editorial"
              class="form-control"
              placeholder="Ej: Sudamericana"
            />
          </div>

          <!-- Autor -->
            <div class="col-md-6">
  <label for="autor" class="form-label">Autores</label>
  <select
    id="autor"
    v-model="nuevoLibro.autor_ids"
    class="form-select"
    multiple
    required>
    <option
      v-for="autor in autores"
      :key="autor.id"
      :value="autor.id">
      {{ autor.nombre }} {{ autor.apellido }}
    </option>
  </select>
</div>

          <!-- Descripción -->
          <div class="col-12">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea
              id="descripcion"
              v-model.trim="nuevoLibro.descripcion"
              class="form-control"
              rows="3"
              placeholder="Describe brevemente el contenido del libro"
            ></textarea>
          </div>
        </div>

        <!-- Botones guardar y cancelar -->
        <div class="mt-3">
          <button type="submit" class="btn btn-success">
            {{ editando ? 'Actualizar' : 'Guardar' }}
          </button>
          <button type="button" class="btn btn-secondary ms-2" @click="cancelarFormulario">
            Cancelar
          </button>
        </div>
      </form>
    </div>

    <!-- Tabla que lista los libros existentes -->
    <table class="table table-bordered table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Editorial</th>
          <th>Año de Publicación</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="libro in libros" :key="libro.id">
          <td>{{ libro.id }}</td>
          <td>{{ libro.titulo }}</td>
          <td>{{ libro.editorial || 'No especificada' }}</td>
          <td>{{ libro.anio_publicacion }}</td>
          <td>
            <button class="btn btn-info btn-sm me-1" @click="verDetalles(libro)">🔍</button>
            <button class="btn btn-warning btn-sm me-1" @click="editarLibro(libro)">✏️</button>
            <button class="btn btn-danger btn-sm" @click="eliminarLibro(libro.id)">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal para mostrar detalles del libro -->
    <div
      v-if="libroSeleccionado"
      class="modal fade show d-block"
      tabindex="-1"
      style="background-color: rgba(0,0,0,0.5);"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detalles del Libro</h5>
            <button type="button" class="btn-close" @click="cerrarDetalles"></button>
          </div>
          <div class="modal-body">
            <p><strong>Título:</strong> {{ libroSeleccionado.titulo }}</p>
            <p>
              <strong>Autor/es: </strong>
                  <ul>
    <li v-for="autor in libroSeleccionado.autores" :key="autor.id">
      {{ autor.nombre }} {{ autor.apellido }} ({{ autor.pais }})
    </li>
    <li v-if="!libroSeleccionado.autores || libroSeleccionado.autores.length === 0">Sin autores</li>
  </ul>
            </p>
            <p><strong>Año de Publicación:</strong> {{ libroSeleccionado.anio_publicacion }}</p>
            <p><strong>Editorial:</strong> {{ libroSeleccionado.editorial || 'No especificada' }}</p>
            <p><strong>Descripción:</strong> {{ libroSeleccionado.descripcion || 'Sin descripción' }}</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="cerrarDetalles">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      // Listas y datos
      libros: [], // lista de libros desde API
      autores: [], // lista de autores desde API
      libroSeleccionado: null, // libro a mostrar detalles

      // Control formulario
      mostrarFormulario: false, // muestra u oculta el formulario
      editando: false, // indica si estamos editando o creando

      // Modelo para formulario libro nuevo/editar
      nuevoLibro: {
        titulo: "",
        anio_publicacion: null,
        editorial: "",
        descripcion: "",
        autor_ids: [],
      },

      // Mensajes UI
      mensajeSuccess: "",
      mensajeError: "",
    };
  },
  methods: {
    // Obtiene libros desde la API
    obtenerLibros() {
      axios
        .get("/api/libros")
        .then((res) => {
          this.libros = res.data;
        })
        .catch((err) => {
          this.mensajeError = "Error al obtener libros.";
          console.error(err);
        });
    },

    // Obtiene autores desde la API
    obtenerAutores() {
      axios
        .get("/api/autores")
        .then((res) => {
          this.autores = res.data;
        })
        .catch((err) => {
          this.mensajeError = "Error al obtener autores.";
          console.error(err);
        });
    },

    // Función para agregar un libro
    agregarLibro() {
      axios
        .post("/api/libros", this.nuevoLibro)
        .then(() => {
          this.mensajeSuccess = "Libro creado correctamente.";
          this.obtenerLibros();
          this.cancelarFormulario();
        })
        .catch((err) => {
          this.mensajeError = "Error al crear libro.";
          console.error(err);
        });
    },

    // Función para actualizar libro existente
    actualizarLibro() {
      axios
        .put(`/api/libros/${this.nuevoLibro.id}`, this.nuevoLibro)
        .then(() => {
          this.mensajeSuccess = "Libro actualizado correctamente.";
          this.obtenerLibros();
          this.cancelarFormulario();
        })
        .catch((err) => {
          this.mensajeError = "Error al actualizar libro.";
          console.error(err);
        });
    },

    // Muestra el formulario para editar con los datos cargados
    editarLibro(libro) {
      this.editando = true;
      // Copiamos el libro para edición
     this.nuevoLibro = {
  ...libro,
  autor_ids: libro.autores?.map(a => a.id) || []
};
      this.mostrarFormulario = true;
    },

    // Elimina un libro después de confirmar
    eliminarLibro(id) {
      if (confirm("¿Estás seguro de eliminar este libro?")) {
        axios
          .delete(`/api/libros/${id}`)
          .then(() => {
            this.mensajeSuccess = "Libro eliminado correctamente.";
            this.obtenerLibros();
          })
          .catch((err) => {
            this.mensajeError = "Error al eliminar libro.";
            console.error(err);
          });
      }
    },

    // Muestra modal con detalles del libro
    verDetalles(libro) {
      // Opcional: podrías hacer petición para traer detalles más completos
      this.libroSeleccionado = libro;
    },

    // Cierra modal de detalles
    cerrarDetalles() {
      this.libroSeleccionado = null;
    },

    // Cancela el formulario, limpia datos y oculta formulario
    cancelarFormulario() {
      this.mostrarFormulario = false;
      this.editando = false;
      this.nuevoLibro = {
        titulo: "",
        anio_publicacion: null,
        editorial: "",
        descripcion: "",
        autor_ids: [],
      };
      this.mensajeError = "";
      this.mensajeSuccess = "";
    },
  },

  // Al montar el componente, carga libros y autores
  mounted() {
    this.obtenerLibros();
    this.obtenerAutores();
  },
};
</script>

<style scoped>
/* Fondo oscuro para modal */
.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
}

/* Estilo para el modal de detalles */
.modal-detalles {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}
</style>
