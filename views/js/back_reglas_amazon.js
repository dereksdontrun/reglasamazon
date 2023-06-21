/**
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

// Utilizaremos javascript para generar nuevas líneas de formulario cuando el usuario quiera crear la configuración para un nuevo marketplace.
// si generamos el script de javascript dinámicamente en configure.tpl por acceder directamente desde acceso rápido (no carga el js desde reglasamazon.php) hay que llamar a la función start() directamente, si cargamos normalmente se usa lo de addeventlistener. Esto muestra un error en la consola pero parece que no afecta al funcionamiento.
document.addEventListener('DOMContentLoaded', start);
start();

function start() {
    //añadimos event listener al botón de Añadir marketplace
    const nuevoMarketplace = document.querySelector('#nuevo_marketplace'); 
    nuevoMarketplace.addEventListener('click', mostrarFormulario);

    //añadimos event listener a los botones de eliminar
    const botones_eliminar = document.querySelectorAll('.boton_eliminar');    
    botones_eliminar.forEach( item => {
        item.addEventListener('click', function(event){
            //event.preventDefault();
            eliminarMarketplace(event);
        });
    });

    //añadimos event listener al botón de guardar para comprobar los valores antes de hacer submit
    const botonGuardar = document.querySelector('#guarda_reglas'); 
    botonGuardar.addEventListener('click', function(event){
        comprobarFormulario(event);
    });


    function mostrarFormulario() {
        console.log('Nuevo Marketplace');
        //generamos una nueva línea de formulario para un nuevo marketplace
        //primero comprobamos si ya existe una línea nueva sin guardar (se pulsa el botón de Añadir varias veces) para ponerle el id acorde (1 ,2 ..)
        let id;
        if (document.contains(document.querySelector('div[id^="nuevo_"]'))) {
            console.log('ya existe'+document.querySelector("#contenedor_marketplaces").lastChild.id);
            //hay un child con ese comienzo de id, sacamos el número y al nuevo le ponemos id nuevo_ +1
            id = parseInt(document.querySelector("#contenedor_marketplaces").lastChild.id.split('_')[1]) + 1;

        } else {
            console.log('no existe');
            id = 1;
        } 

        console.log(id);

        const nuevo_marketplace = document.createElement('div');
        nuevo_marketplace.classList.add('row','form-group'); 
        nuevo_marketplace.id = `nuevo_${id}`;       
        nuevo_marketplace.innerHTML =  `
        <div class="form-group col-xs-10">
            <div class="form-group col-xs-2 div_input">
                <label for="pais_nuevo_${id}">País</label><br>
                <div class="input-group">
					<span class="input-group-addon"><i class="icon icon-globe"></i></span>
                    <input type="text" name="pais_nuevo_${id}" id="pais_nuevo_${id}" value="" class="form-control texto pais" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="codigo_nuevo_${id}">Código</label>
                <div class="input-group">
					<span class="input-group-addon"><i class="icon icon-code"></i></span>
                    <input type="text" name="codigo_nuevo_${id}" id="codigo_nuevo_${id}" value="" class="form-control texto codigo" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="moneda_nuevo_${id}">Moneda</label>
                <div class="input-group">
					<span class="input-group-addon"><i class="icon icon-money"></i></span>
                    <input type="text" name="moneda_nuevo_${id}" id="moneda_nuevo_${id}" value="" class="form-control texto" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="cambio_nuevo_${id}">Cambio</label>
                <div class="input-group">
					<span class="input-group-addon"><i class="icon icon-exchange"></i></span>
                    <input type="text" name="cambio_nuevo_${id}" id="cambio_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="margen_minimo_nuevo_${id}">Margen Mínimo</label>
                <div class="input-group">
					<span class="input-group-addon">%</span>
                    <input type="text" name="margen_minimo_nuevo_${id}" id="margen_minimo_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="margen_minimo_c_nuevo_${id}">Mínimo C</label>
                <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="text" name="margen_minimo_c_nuevo_${id}" id="margen_minimo_c_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="margen_minimo_outlet_nuevo_${id}">Mínimo Outlet</label>
                <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="text" name="margen_minimo_outlet_nuevo_${id}" id="margen_minimo_outlet_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="margen_minimo_sin_stock_nuevo_${id}">Mínimo Sin Stock</label>
                <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="text" name="margen_minimo_sin_stock_nuevo_${id}" id="margen_minimo_sin_stock_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="coste_track_nuevo_${id}">Coste Tracked</label>
                <div class="input-group">
					<span class="input-group-addon"><i class="icon icon-euro"></i></span>
                    <input type="text" name="coste_track_nuevo_${id}" id="coste_track_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
            <div class="form-group col-xs-1 div_input">
                <label for="coste_sign_nuevo_${id}">Coste Signed</label>
                <div class="input-group">
					<span class="input-group-addon"><i class="icon icon-euro"></i></span>
                    <input type="text" name="coste_sign_nuevo_${id}" id="coste_sign_nuevo_${id}" value="" class="form-control numerico" required>
                </div>
            </div>
        </div>
        <div class="form-group col-xs-2">
            <div class="form-group col-xs-4 div_input">
                <label for="accion_nuevo_${id}">Acción</label>
                <div class="select-group">
                    <span class="select-group-addon"><i class="icon icon-wrench"></i> </span>
                    <select id="accion_nuevo_${id}" name="accion_nuevo_${id}" class="form-control">
                        <option value="start"> start</option>
                        <option value="stop"> stop</option>								
                    </select>							
                </div>					
            </div>

            <div class="form-group col-xs-3 div_input">
                <button type="submit" value="nuevo_${id}"  id="exportar_nuevo_${id}" name="exportar_nuevo_${id}" class="btn btn-default">            
                    <i class="process-icon-download icon-download"></i>Exportar      
                </button>
            </div>

            <div class="form-group col-xs-3 div_input">
                <button type="submit" value="nuevo_${id}"  id="eliminar_nuevo_${id}" name="eliminar_nuevo_${id}" class="btn btn-default boton_eliminar">            
                    <i class="process-icon-cancel icon-cancel"></i>Eliminar       
                </button>
            </div>
        </div>
            <hr>`;
        
        document.querySelector('#contenedor_marketplaces').appendChild(nuevo_marketplace);

        //añadimos event listener al botón de eliminar
        document.querySelector(`#eliminar_nuevo_${id}`).addEventListener('click', function(event){
            event.preventDefault();
            eliminarMarketplace(event);
        });


    }

    function eliminarMarketplace(event) {
        console.log('Eliminar Marketplace');
        //hay que saber si el botón de eliminar pertenece a un marketplace existente en la tabla frik_amazon_reglas, en cuyo caso hay que eliminar la entrada de la tabla, o si es un nuevo marketplace agregado en esta ejecución del módulo y por tanto solo hay que eliminar el child que se añadió a contenedor_marketplace. Lo hacemos comprobando el id del div, si contiene "nuevo", se elimina el div, parando la ejecución del submit, si no, seguimos con el submit para eliminar la entrada de la tabla.
        
        console.log(event.currentTarget.id);
        if (event.currentTarget.id.includes("nuevo")) {
            event.preventDefault();
            console.log("borrar");
            //sacamos el id del div a eliminar
            const id_eliminar = event.currentTarget.id.replace("eliminar_","");
            document.querySelector('#'+id_eliminar).remove();
        } else {
            console.log("base de datos");
            //vamos a reglasamazon.php con el submit eliminar, eliminamos el marketplace de base de datos, y recarga la página
            
        }        

    }

    function comprobarFormulario(event) {
        console.log('Comprobar Marketplace');
        //con required en cada input ya es obligatorio que se introduzca algo
        let error = 0;
        let texto_error = '';
        //showErrorMessage('Vamos');
        //comprobamos que dentro de los input con class 'numerico' hay un número y no tiene más de dos decimales     
        const pattern_dos_decimales = /^\d*(\.\d{0,2})?$/; 
        const inputs_numericos = document.querySelectorAll('.numerico'); 
        inputs_numericos.forEach( item => {
            if (isNaN(item.value)) {
                error = 1;
                //sacamos el texto del label del input
                texto_error += 'Has introducido texto en '+document.querySelector('#'+item.id).labels[0].textContent+'\n';
            } else if (!pattern_dos_decimales.test(item.value)) {
                error = 1;
                texto_error += 'No se admiten más de dos decimales en '+document.querySelector('#'+item.id).labels[0].textContent+'\n';
            }
        });

        //comprobamos que dentro de los input con class 'texto' hay texto        
        // const inputs_texto = document.querySelectorAll('.texto'); 
        // inputs_texto.forEach( item => {
        //     if (isNaN(item.value)) {
        //         error = 1;
        //         texto_error += 'Has introducido texto en lugar de números\n';
        //     }
        // });

        //comprobamos que no se repite ningún código de país
        let codigos = [];
        const inputs_codigos = document.querySelectorAll('.codigo'); 
        inputs_codigos.forEach( item => {
           //metemos cada código (pasado aminúsculas) en un array y convertimos el array en SET (elimina duplicados).Si length(array, en set es size) es diferente había duplicado
            codigos.push(item.value.toLowerCase());
        });   

        let set_codigos = new Set(codigos);        
        if (set_codigos.size != codigos.length) {
            error = 1;            
            texto_error += 'Has introducido algún código ISO duplicado\n';
        }

        //comprobamos que no se repite ningún nombre de país
        let paises = [];
        const inputs_paises = document.querySelectorAll('.pais'); 
        inputs_paises.forEach( item => {
           //metemos cada pais (pasado aminúsculas) en un array y convertimos el array en SET (elimina duplicados).Si length(array, en set es size) es diferente había duplicado
            paises.push(item.value.toLowerCase());
        });   

        let set_paises = new Set(paises);        
        if (set_paises.size != paises.length) {
            error = 1;            
            texto_error += 'Has introducido algún nombre de país duplicado\n';
        }

        if (!error) {
            console.log('Formulario sin errores');
            //ponemos value a los input hidden que almacenan los ids de la tabla a updatear y los ids para los insert. Se usará en amazonreglas.php para obtener el nombre de los input en el $_POST. Sacamos los values de cada botón de Eliminar, será un número si viene de la tabla, o nuevo_numero si es creado ahora
            let ids_tabla = [];
            let ids_nuevos = [];
            const ids_eliminar = document.querySelectorAll('.boton_eliminar'); 
            ids_eliminar.forEach( item => {
            //metemos cada id en un array según sea solo número o nuevo_numero
                if (item.value.includes('nuevo')){
                    ids_nuevos.push(item.value);
                } else {
                    ids_tabla.push(item.value);
                }                
            }); 

            //ponemos value al input hidden hiddenids con un join de los ids para sacar luego cuales hay que hacer update
            document.querySelector('#hiddenids').value = ids_tabla.join('-');
            //ponemos value al input hidden hiddennuevosids con un join de los ids para sacar luego cuales hay que hacer insert
            document.querySelector('#hiddennuevosids').value = ids_nuevos.join('-');
            //continuamos a reglasamazon.php
        } else {
            console.log('Formulario CON errores');
            event.preventDefault();
            alert(texto_error);
        }

    }
    
}