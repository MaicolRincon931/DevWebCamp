(function () {
  const ponentesInput = document.querySelector("#ponente");

  if (ponentesInput) {
    let ponentes = [];
    let ponentesFiltrado = [];
    const listadoPonentes = document.querySelector('#listado-ponentes');
    const ponenteHidden = document.querySelector('[name="ponente_id"]');
    obtenerPonentes();
    ponentesInput.addEventListener("input", buscarPonentes);

    async function obtenerPonentes() {
      const url = `/api/ponentes`;

      const respuesta = await fetch(url);
      const resultado = await respuesta.json();

      formatearPonentes(resultado);
    }

    function formatearPonentes(arrayPonentes = []) {
      ponentes = arrayPonentes.map((ponente) => {
        return {
          nombre: `${ponente.nombre.trim()} ${ponente.apellido}`,
          id: ponente.id,
        };
      });
    }

    function buscarPonentes(e) {
      const busqueda = e.target.value;
      if (busqueda.length > 3) {
        const expresion = new RegExp(busqueda, "i");
        ponentesFiltrado = ponentes.filter((ponente) => {
          if (ponente.nombre.toLowerCase().search(expresion) != -1) {
            return ponente;
          }
        });
        
       
      }else{
        ponentesFiltrado=[];
      }

      mostrarPonentes();
    }

    function mostrarPonentes(){
        while(listadoPonentes.firstChild){
            listadoPonentes.removeChild(listadoPonentes.firstChild);
        }

        if(ponentesFiltrado.length >0){
            ponentesFiltrado.forEach(ponente =>{
                const ponenteHTML = document.createElement('LI');
                ponenteHTML.classList.add('listado-ponentes__ponente');
                ponenteHTML.textContent = ponente.nombre;
                ponenteHTML.dataset.ponenteId = ponente.id

                ponenteHTML.onclick = seleccionarPonente;
    
                //Añadir al DOM
    
                listadoPonentes.appendChild(ponenteHTML);
            })
        }else{
            const noResultados = document.createElement('P');
            noResultados.classList.add('listado-ponentes__no-resultado');
            noResultados.textContent = 'No hay resultado para tu búsqueda';
            listadoPonentes.appendChild(noResultados);
        }
        
    }

    function seleccionarPonente(e){

        //Remover el previo
        const ponentePrevio = document.querySelector('.listado-ponentes__ponente--seleccionado');
        if(ponentePrevio){
            ponentePrevio.classList.remove('listado-ponentes__ponente--seleccionado');
        }
        const ponente = e.target;
        ponente.classList.add('listado-ponentes__ponente--seleccionado');

        ponenteHidden.value = ponente.dataset.ponenteId;
    }
  }
})();
