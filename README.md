# Gestor de Libros 📚

Aplicación web para gestionar libros y autores con relación muchos a muchos.  
Permite agregar, editar, eliminar y listar libros y autores, vinculando varios autores a un libro y viceversa.

---

## Tecnologías usadas

- **Frontend:** Vue.js 3, Bootstrap 5, Axios, Vite  
- **Backend:** Laravel 10 (PHP)  
- **Base de datos:** MySQL  
- **Control de versiones:** Git

---

## Uso básico

- Acceder al frontend en el navegador (`http://localhost:8080` o la URL que use tu servidor Vue).  
- Crear autores con nombre, apellido y país.  
- Crear libros y seleccionar uno o más autores desde un selector múltiple.  
- Ver detalles de libros y sus autores vinculados.  
- Editar y eliminar libros o autores según sea necesario.
- Rutas: Gestor de Libros (`http://localhost:8080` + '/Gestor-Libros'), Gestor de Autores (`http://localhost:8080` + '/Autores')

---

## Errores comunes y soluciones aplicadas

### 1. Error 500 al eliminar un libro

- **Problema:** El backend lanzaba un error 500 por restricciones en la base de datos al borrar libros con autores relacionados.  
- **Solución:** Modifiqué el controlador para que primero desvincule los autores (elimine relaciones en la tabla pivot) antes de eliminar el libro, evitando errores de llave foránea.

### 2. Error 422 al crear un autor

- **Problema:** Fallaba la validación por campos mal tipados (por ejemplo, apellido como entero).  
- **Solución:** Corregí las reglas de validación en el controlador para que `apellido` sea un string y ajusté el frontend para enviar datos compatibles.

### 3. No se mostraban múltiples autores en detalles del libro

- **Problema:** El modal sólo mostraba un autor aunque el libro tuviera varios.  
- **Solución:** Usé eager loading con `with('autores')` en el backend para cargar la relación muchos a muchos y luego en el frontend usé `v-for` para mostrar todos los autores.

---

## Versionamiento y subida a GitHub

1. Verificar estado de archivos:  
```bash
git status
