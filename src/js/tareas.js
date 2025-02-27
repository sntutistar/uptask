(function () {

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    //boton mostrar el modal
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function () {
        mostrarFormulario();
    });

    //filtros de busquedas
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach( radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e){

        const filtro = e.target.value;

        if(filtro !== ''){
            filtradas = tareas.filter(tarea => tarea.estado === filtro)
        } else{
            filtradas = [];
        }

        mostrarTareas();
    }

    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?url=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function totalpendientes(){
        const totalpendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector('#pendientes');

        if(totalpendientes.length === 0){
            pendientesRadio.disabled = true;
        } else{
            pendientesRadio.disabled = false;
        }
    }

    function totalcomplentadas(){
        const totalpendientes = tareas.filter(tarea => tarea.estado === "1");
        const pendientesRadio = document.querySelector('#completadas');

        if(totalpendientes.length === 0){
            pendientesRadio.disabled = true;
        } else{
            pendientesRadio.disabled = false;
        }
    }

    function mostrarTareas() {
        limpiarHtml();
        totalpendientes();
        totalcomplentadas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if (arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textocontenedorTareas = document.createElement('li');
            textocontenedorTareas.textContent = 'No hay tareas';
            textocontenedorTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textocontenedorTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completada'
        };

        arrayTareas.forEach(tarea => {

            const contenedorTarea = document.createElement('li');
            contenedorTarea.dataset.tareaid = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('p');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function () {
                mostrarFormulario(editar = true, {...tarea});
            };

            const opcionesDiv = document.createElement('div');
            opcionesDiv.classList.add('opciones');

            const btnEstadoTarea = document.createElement('button');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function () {
                cambiandoEstadoTarea({ ...tarea });
            };

            const btnEliminarTarea = document.createElement('button');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function () {
                eliminandoTarea({ ...tarea });
            };

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTarea = document.querySelector('#listado-tareas');
            listadoTarea.appendChild(contenedorTarea);
        });
    }

    function mostrarFormulario(editar = false, tarea = {}) {

        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea" action="">

                <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>

                <div class="campo">

                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder=" ${tarea.nombre ? 'Editar la tarea' : 'Añadir una nueva tarea'}" id="tarea" value="${tarea.nombre ? tarea.nombre : ''}"/>

                </div>

                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Guardar cambios' : 'Añadir tarea'}" />
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>

            </form>
        
        
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar')
        }, 0);

        modal.addEventListener('click', function (e) {

            e.preventDefault();

            if (e.target.classList.contains('cerrar-modal')) {

                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar')

                setTimeout(() => {
                    modal.remove();
                }, 500);

            }

            if (e.target.classList.contains('submit-nueva-tarea')) {
                const nombreTarea = document.querySelector('#tarea').value.trim();

                if (nombreTarea === '') {
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario .campo'));
                    return;
                }

                if(editar){
                    tarea.nombre =nombreTarea;
                    actualizarTarea(tarea);
                }else{
                    agregarTarea(nombreTarea);
                }
            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function mostrarAlerta(mensaje, tipo, referencia) {
        const alertaPrevia = document.querySelector('.alerta');

        if (alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('div');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        referencia.parentElement.insertBefore(alerta, referencia);

        setTimeout(() => {
            alerta.remove();
        }, 1000);

    }

    async function agregarTarea(tarea) {
        //Construir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoid', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tareas';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario .campo'));

            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1200);

                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoid: resultado.proyectoid
                };

                tareas = [...tareas, tareaObj];
                mostrarTareas();

                
            }

        } catch (error) {

        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams);
        return proyecto.url;
    }

    function limpiarHtml() {
        const listadoTarea = document.querySelector('#listado-tareas');
        while (listadoTarea.firstChild) {
            listadoTarea.removeChild(listadoTarea.firstChild);
        }
    }

    function cambiandoEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);

    }

    async function actualizarTarea(tarea) {
        
        const { estado, id, nombre, proyectoid } = tarea;
        const datos = new FormData();

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerProyecto());

        try {

            const url = 'http://localhost:3000/api/tareas/actualizar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            if (resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje,
                    'Tarea actualizada',
                    'success'
                );

                const modal = document.querySelector('.modal');
                if(modal){
                    modal.remove();
                }

                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function eliminandoTarea(tarea) {
        Swal.fire({
            title: "¿Esta seguro de eliminar la tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }

    async function eliminarTarea(tarea) {

        const { estado, id, nombre } = tarea;
        const datos = new FormData();

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoid', obtenerProyecto());

        try {

            const url = 'http://localhost:3000/api/tareas/eliminar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            if (resultado.respuesta.tipo === 'error') {

                Swal.fire('Tarea eliminada!', resultado.mensaje, 'success');

                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }
})();


