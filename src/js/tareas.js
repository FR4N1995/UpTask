(function(){


    leerTareas()
    let tareas = [];
    let filtradas = [];

    //Filtros de busqueda
    const filtros = document.querySelectorAll('#filtros input[type="radio"]')
    filtros.forEach(radio => {
      radio.addEventListener('input', filtrarTareas);
    })

    function filtrarTareas(evento){
        const filtro = evento.target.value;

        if (filtro !== '') {
          filtradas = tareas.filter(tarea => tarea.estado === filtro);
        }else{
            filtradas = [];
        }
        mostrarTareas();
    }


     async function leerTareas(){
       try {
         const urlget = obtenerURLPyecto();
         const url = `/api/tareas?url=${urlget}`;
         const respuesta = await fetch(url);
         const resultado = await respuesta.json();
         tareas = resultado.tareas;
        mostrarTareas();
        // console.log(resultado.tareas);
       } catch (error) {
         console.log(error);
       }

     }

     function mostrarTareas(){
        totalPendientes();
        totalCompletas();
        limpiarTareas();
      //si el arreglo de filtradas tiene algo mostraremos el arreglo de filtradas
      //si no mostramos el arreglo de tareas que se encuentra arriba
      const arrayTareas = filtradas.length ? filtradas : tareas;

        if(arrayTareas.length === 0){
        const contenedorTareas =  document.querySelector('#listado-tareas');
        const textoNoTareas = document.createElement('LI');
        textoNoTareas.textContent = 'No hay Tareas';
        textoNoTareas.classList.add('no-tareas');
        contenedorTareas.appendChild(textoNoTareas);
        return;
        
        }
      //El return solo es para no poner un else

        const estados = {
        0: 'Pendiente',
        1: 'Completado'
        }
        arrayTareas.forEach(tarea =>{
        //creamos un li por cada tarea y le asiganmos un
        //id por el arrglo que resicimo de tareContoller
        const contenedorTarea = document.createElement('LI');
        contenedorTarea.dataset.tareaId = tarea.id;
        contenedorTarea.classList.add('tarea');

        const nombreTarea = document.createElement('P');
        nombreTarea.textContent = tarea.nombre

        nombreTarea.ondblclick = function(){
            mostrarformulario(editar = true, {...tarea});
        }

        const opcionesDiv = document.createElement('DIV');
        opcionesDiv.classList.add('opciones');

        //botones de opcionDiv

        const btnEstadoTarea = document.createElement('BUTTON');
        btnEstadoTarea.classList.add('estado-tarea');
        btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
        //mapeamos la variable de estado en tareas creando un objet
        //para que cuando sea o ponga pendiente y 1 Completado
        btnEstadoTarea.textContent = estados[tarea.estado];
        btnEstadoTarea.dataset.estadoTarea = tarea.estado;

        btnEstadoTarea.ondblclick = function(){
         
          cambiarestadoTarea({...tarea});
        }


        //boton para eliminar la tarea
        const btnEliminarTarea = document.createElement('BUTTON');
        btnEliminarTarea.classList.add('eliminar-tarea');
        btnEliminarTarea.dataset.idTarea = tarea.id;
        btnEliminarTarea.textContent = 'Eliminar';

        btnEliminarTarea.onclick = function(){
          confirmarEliminarTarea({...tarea});
        }
        

        opcionesDiv.appendChild(btnEstadoTarea);
        opcionesDiv.appendChild(btnEliminarTarea);

        contenedorTarea.appendChild(nombreTarea);
        contenedorTarea.appendChild(opcionesDiv);

        const listadoTareas = document.querySelector('#listado-tareas');
        listadoTareas.appendChild(contenedorTarea);



        // console.log(btnEstadoTarea);

        });


     }


    //boton para mostrar el modal de agregar tarea
    const nuevaTarea = document.querySelector('#agregar-tarea');
    nuevaTarea.addEventListener('click', function(){
      mostrarformulario();
    });

    function totalPendientes(){
      const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
      const radioPendientes = document.querySelector('#pendientes');

      if (totalPendientes.length === 0) {
        radioPendientes.disabled = true;
      }else{
        radioPendientes.disabled = false;

      }
    }

    function totalCompletas(){
      const totalCompletas = tareas.filter(tarea => tarea.estado === "1");
      const radioCompletas = document.querySelector('#completadas');

      if (totalCompletas.length === 0) {
        radioCompletas.disabled = true;
      }else{
        radioCompletas.disabled = false;

      }
    }


    function mostrarformulario(editar = false , tarea = {}){

        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML= `
          <form class="formulario nueva-tarea">
          <legend>${editar ? 'Editar Tarea' : 'Añade una nueva Tarea'}</legend>
         <div class="campo">
                <label>Tarea</label>
                <input type="text" id="tarea" value="${tarea.nombre ? tarea.nombre : ''}" name="tarea" placeholder="${tarea.nombre ? 'Edita Tarea' : 'Añadir tarea'}" />
         </div>
          <div class="opciones">
                <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Guardar Cambios' : 'Agregar Tarea'}"/>
                <button type="button" class="cerrar-modal">Cancelar</button>
          </div>
          
          
          </form>      
        `;
        setTimeout(() => {
          const formulario = document.querySelector('.formulario');
          formulario.classList.add('animar');
        },0);

        modal.addEventListener('click', function(evento){
          //preventDefault sirve para que cuando demos clic en el boton añadir tarea no se cierre el modal
            evento.preventDefault();
            if (evento.target.classList.contains('cerrar-modal')) {
              const formulario = document.querySelector('.formulario');
              formulario.classList.add('cerrar');
              setTimeout(() => {
                modal.remove();
              }, 500);
            }

            if (evento.target.classList.contains('submit-nueva-tarea')) {
              const nombreTarea = document.querySelector('#tarea').value.trim();

              if(nombreTarea === ''){
               //mostrar Alerta
                mostrarAlerta('El nombre de la Tarea es Obligatorio', 'error', document.querySelector('.formulario legend') );
                return;
              }

              if(editar){
                tarea.nombre = nombreTarea;
                actualizarTarea(tarea)
              }else{
                agregarTarea(nombreTarea);
              }
              
            }
         
        });

        document.querySelector('.dashboard').appendChild(modal);

    }

    


      //muestra un mensaje en la interfaz
      function mostrarAlerta(mensaje, tipo, referencia){

        //previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
          alertaPrevia.remove();
        }
      
      const alerta = document.createElement('Div');
      alerta.classList.add('alerta', tipo);
      alerta.textContent = mensaje;
      // console.log(alerta);
      // referencia.appendChild(alerta);
      referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

      //eliminar la alerta despues de 4 segundos
      setTimeout(() => {
        alerta.remove()
      }, 4000);
      
      }

      //consultar el servidor para añadir una nueva tarea al proyecto
      //async
      async function agregarTarea(tarea){
        //formData
        //construir la peticion
        const datos = new FormData()
        datos.append('nombre', tarea);
        datos.append('proyecto_id', obtenerURLPyecto());
        //creamos una instancia de URLPARAMS     

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            // console.log(resultado);
            //Generamos la alerta que recibimos de TareaController
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend') );

            //Si se ingresa correctamente la tarea
            if (resultado.tipo === 'exito') {
              const modal = document.querySelector('.modal');
              setTimeout(() => {
                modal.remove();
              }, 2000);

              //Agregar el objeto de tarea al global de tareas
              const tareaObj = {
                id: String(resultado.id),
                nombre: tarea,
                estado: "0",
                proyecto_id: resultado.proyecto_id
              };
              
              tareas = [...tareas, tareaObj];
              mostrarTareas();
            //  console.log(tareaObj);
            }


        } catch (error) {
          console.log(error);
        }


      }

      //aqui resivimos la vopia del objeto original(TAREAS global)
      function cambiarestadoTarea(tarea){
        // console.log('hola desde la funcion');
          const nuevoEstado = tarea.estado === "1" ? "0" : "1";
          tarea.estado = nuevoEstado;
          actualizarTarea(tarea);
      }

     async function actualizarTarea(tarea){
            const {estado, id, nombre} = tarea;

            const datos = new FormData();
            datos.append('id' , id);
            datos.append('nombre', nombre);
            datos.append('estado', estado);
            datos.append('proyecto_id', obtenerURLPyecto());

            //forma de comprobar los datos antes de enviar al servidor
            // for(let valor of datos.values()){
            //   console.log(valor);
            // }

            try {
                 const url = 'http://localhost:3000/api/tarea/actualizar';
                 const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                 });
                 const resultado = await respuesta.json();

                 if (resultado.respuesta.tipo === 'exito') {
                    // mostrarAlerta(resultado.respuesta.mensaje, resultado.respuesta.tipo, document.querySelector('.contenedor-nueva-tarea'));
                      swal.fire(resultado.respuesta.mensaje, resultado.respuesta.mensaje, 'success');

                      const modal = document.querySelector('.modal');
                      //este if es para que funcione correctamente al editar pendiente/completada
                      if (modal) {
                        modal.remove();
                      }
                      

                      tareas = tareas.map(tareaMemoria =>{
                        if (tareaMemoria.id === id) {
                            tareaMemoria.estado = estado;
                            tareaMemoria.nombre = nombre;
                        }
                        return tareaMemoria;
                    });

                    mostrarTareas();
                    // console.log(tareas);

                 }

                //  console.log(resultado.respuesta);

            } catch (error) {
              
            }

      }

      function confirmarEliminarTarea(tarea){
             
        Swal.fire({
          title: '¿Eliminar Tarea?',
          showDenyButton: true,
          confirmButtonText: 'Si',
          cancelButtonText: 'No'
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            } 
        })
      }

      async function eliminarTarea(tarea){
        //Aplicamos destroccion a tarea para acceder directamente a las variables
        // y no poner tarea.id ETC.
        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append('id' , id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyecto_id', obtenerURLPyecto());
        try {
          const url = 'http://localhost:3000/api/tarea/eliminar';
          const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
          });
          // console.log(respuesta);
          const resultado = await respuesta.json();
          console.log(resultado);
           if (resultado.resultado) {
            //AlERTA QUE NOSOTROS CREAMOS
            // mostrarAlerta(resultado.mensaje,
            //                 resultado.tipo, 
            //                 document.querySelector('.contenedor-nueva-tarea'));
              Swal.fire('Eliminado!', resultado.mensaje, 'success');
             //filter lo que hace es crear un arreglo nuevo y nops sirve para sacar uno de todos o
             // mantener uno de todos los que estan en el erreglo dependioendo de las condiciones 
              tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id );
              mostrarTareas();
            }
           
        } catch (error) {
          
        }

      }

      function obtenerURLPyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        //  console.log(proyecto);
        return proyecto.url;
      }

      function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');
        //Esa es una forma no muy buena de limpiar las tareas anteriores y que no se repitan
        // listadoTareas.innerHTML = '';
        while(listadoTareas.firstChild){
          listadoTareas.removeChild(listadoTareas.firstChild);
        }
      }



    
})();