{*
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
*}

{* Por si no cargamos el módulo correctamente sino desde acceso rápido, cargamos css y js aquí también *}
<link rel="stylesheet" href="{$url_css}">


<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Reglas de precios Amazon' mod='reglasamazon'}</h3>
	<p>
		Aquí puedes modificar los parámetros en función a los que se establecerán las reglas de precios para los diferentes marketplaces de Amazon.<br>
		Se muestra la información almacenada en la tabla de reglas de Amazon, puedes editar los valores, añadir un nuevo marketplace o eliminar uno existente.<br>
		El botón guardar almacenará los parámetros actuales. El botón inferior Exportar Reglas generará el documento para importar las reglas en Amazon, con referencia a cada marketplace. Cada botón lateral de Exportar generará un documento para importar en cada marketplace de Amazon que contendrá el PVP y el stock de cada producto con categoría Amazon, y en el caso de los porductos de venta sin stock reflejará un stock de 999 unidades y una latencia de 4 días. Además incluirá cada producto de peso superior a un kg y marketplace no España en la regla de envíos Productos Pesados, y en el caso del marketplace España, incluirá los productos de venta sin stock en la regla de envíos Productos No Prime ligeros.<br>
		Margen mínimo C corresponde al aplicable a los productos de clasificación C que no tienen la categoría Outlet y su antigüedad es superior a la establecida para considerarlos novedad.<br>
		Margen mínimo Outlet corresponde al aplicable a los productos que tienen la categoría Outlet independientemente de su antigüedad o clasificación ABC.<br>
		Margen mínimo corresponde al resto de los productos (no tienen la categoría Outlet ni clasificación C).<br>
		Margen mínimo Sin Stock corresponde a los productos que vendemos sin stock físico en la web. Esta regla tiene más peso que las demás, si un producto es C y sin stock, el margen a aplicar será Sin Stock.
	</p>		
</div>
 {* Quiero cargar la página de configuración del módulo con los datos que haya en la tabla frik_amazon_reglas, a modo de formulario rellenado, así puede editarse, añadir un nuevo país (o eliminar uno) y guardar lo que se edite o se cree.
 En lugar de utilizar el helper para forms de prestashop, que es un lío, genero todo directamente en el tpl *}
<div class="bootstrap panel main-block" id="panel_principal">
	<h3><i class="icon icon-cogs"></i> Configuración</h3>
	
	<form method="post" action="" class="form-inline" id="formulario_reglas">
		<div class="form-group row">
			<div class="form-group div_input">
				<label for="nombre_regla">Nombre Regla</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="icon icon-pencil"></i></span>
					<input type="text" name="nombre_regla" id="nombre_regla" value="{$nombre_regla|escape:'html':'UTF-8'}" class="form-control" required>
				</div>
			</div>
		</div>	
		<hr>

		<div id="contenedor_marketplaces"> {* Lo necesitamos para hacer appendchild cuando añadimos nuevos marketplaces *}
		{if $no_hay_datos}
			<h1>NO HAY NINGUNA INFORMACIÓN DE REGLAS DE PRECIO ALMACENADA</h1>
			<hr>
		{else}	
			{* {$marketplaces|@var_dump} *}			
			{foreach $marketplaces as $marketplace}
				
				<div class="form-group row">
					<div class="form-group col-xs-10">
						<div class="form-group col-xs-2 div_input">
							<label for="pais_{$marketplace['id_amazon_reglas']}">País</label><br>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon icon-globe"></i></span>
								<input type="text" name="pais_{$marketplace['id_amazon_reglas']}" id="pais_{$marketplace['id_amazon_reglas']}" value="{$marketplace['pais']|escape:'html':'UTF-8'}" class="form-control texto pais" readonly required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="codigo_{$marketplace['id_amazon_reglas']}">Código</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon icon-code"></i></span>
								<input type="text" name="codigo_{$marketplace['id_amazon_reglas']}" id="codigo_{$marketplace['id_amazon_reglas']}" value="{$marketplace['codigo']|escape:'html':'UTF-8'}" class="form-control texto codigo" readonly required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="moneda_{$marketplace['id_amazon_reglas']}">Moneda</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon icon-money"></i></span>
								<input type="text" name="moneda_{$marketplace['id_amazon_reglas']}" id="moneda_{$marketplace['id_amazon_reglas']}" value="{$marketplace['moneda']|escape:'html':'UTF-8'}" class="form-control texto" readonly required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="cambio_{$marketplace['id_amazon_reglas']}">Cambio</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon icon-exchange"></i></span>
								<input type="text" name="cambio_{$marketplace['id_amazon_reglas']}" id="cambio_{$marketplace['id_amazon_reglas']}" value="{$marketplace['cambio']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="margen_minimo_{$marketplace['id_amazon_reglas']}">Margen Mínimo</label>
							<div class="input-group">
								<span class="input-group-addon">%</span>
								<input type="text" name="margen_minimo_{$marketplace['id_amazon_reglas']}" id="margen_minimo_{$marketplace['id_amazon_reglas']}" value="{$marketplace['margen_minimo']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="margen_minimo_c_{$marketplace['id_amazon_reglas']}">Mínimo C</label>
							<div class="input-group">
								<span class="input-group-addon">%</span>
								<input type="text" name="margen_minimo_c_{$marketplace['id_amazon_reglas']}" id="margen_minimo_c_{$marketplace['id_amazon_reglas']}" value="{$marketplace['margen_minimo_c']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="margen_minimo_outlet_{$marketplace['id_amazon_reglas']}">Mínimo Outlet</label>
							<div class="input-group">
								<span class="input-group-addon">%</span>
								<input type="text" name="margen_minimo_outlet_{$marketplace['id_amazon_reglas']}" id="margen_minimo_outlet_{$marketplace['id_amazon_reglas']}" value="{$marketplace['margen_minimo_outlet']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="margen_minimo_sin_stock_{$marketplace['id_amazon_reglas']}">Mínimo Sin Stock</label>
							<div class="input-group">
								<span class="input-group-addon">%</span>
								<input type="text" name="margen_minimo_sin_stock_{$marketplace['id_amazon_reglas']}" id="margen_minimo_sin_stock_{$marketplace['id_amazon_reglas']}" value="{$marketplace['margen_minimo_sin_stock']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="coste_track_{$marketplace['id_amazon_reglas']}">Coste Tracked</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon icon-euro"></i></span>
								<input type="text" name="coste_track_{$marketplace['id_amazon_reglas']}" id="coste_track_{$marketplace['id_amazon_reglas']}" value="{$marketplace['coste_track']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
						<div class="form-group col-xs-1 div_input">
							<label for="coste_sign_{$marketplace['id_amazon_reglas']}">Coste Signed</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon icon-euro"></i></span>
								<input type="text" name="coste_sign_{$marketplace['id_amazon_reglas']}" id="coste_sign_{$marketplace['id_amazon_reglas']}" value="{$marketplace['coste_sign']|escape:'html':'UTF-8'}" class="form-control numerico" required>
							</div>
						</div>
					</div>
					<div class="form-group col-xs-2">
						<div class="form-group col-xs-4 div_input">
							<label for="accion_{$marketplace['id_amazon_reglas']}">Acción</label>
							<div class="select-group">
								<span class="select-group-addon"><i class="icon icon-wrench"></i> </span>
								<select id="accion_{$marketplace['id_amazon_reglas']}" name="accion_{$marketplace['id_amazon_reglas']}" class="form-control">
									<option value="start" {if $marketplace['accion']|escape:'html':'UTF-8' == 'start'}selected{/if}> start</option>
									<option value="stop" {if $marketplace['accion']|escape:'html':'UTF-8' == 'stop'}selected{/if}> stop</option>								
								</select>							
							</div>					
						</div>			

						<div class="form-group col-xs-3 div_input">
							<button type="submit" value="{$marketplace['id_amazon_reglas']}"  id="exportar_{$marketplace['id_amazon_reglas']}" name="exportar_marketplace" class="btn btn-default">            
								<i class="process-icon-download icon-download"></i>Exportar       
							</button>
						</div>

						<div class="form-group col-xs-3 div_input">
							<button type="submit" value="{$marketplace['id_amazon_reglas']}"  id="eliminar_{$marketplace['id_amazon_reglas']}" name="eliminar_marketplace" class="btn btn-default boton_eliminar">            
								<i class="process-icon-cancel icon-cancel"></i>Eliminar       
							</button>
						</div>
					</div>					
				</div>

				<hr>
			{/foreach}
			
			
		{/if}	
		</div>
		
		<br>

		{* input hidden para almacenar el id_amazon_reglas y sacar desde amazonreglas.php los input del $_POST *}
		<input type="hidden" name="hiddenids" id="hiddenids" value="">
		{* input hidden para almacenar el id de nuevos marketplaces y sacar desde amazonreglas.php los input del $_POST *}
		<input type="hidden" name="hiddennuevosids" id="hiddennuevosids" value="">

		{* input hidden para almacenar la url del archivo javascript por si no se ha cargado por entrar por acceso rápido *}
		<input type="hidden" name="url_js" id="url_js" value="{$url_js}">

		<button type="submit" id="cancelar" name="cancelar" class="btn btn-default">            
			<i class="process-icon-cancel icon-cancel"></i> Cancelar       
		</button>
		<button type="button" id="nuevo_marketplace" name="nuevo_marketplace" class="btn btn-default">            
			<i class="process-icon-plus icon-plus"></i> Añadir Marketplace     
		</button>

		<button type="submit" id="guarda_reglas" name="guarda_reglas" class="btn btn-default pull-right">            
			<i class="process-icon-save icon-save"></i> Guardar       
		</button>
		<button type="submit" id="exportar_reglas" name="exportar_reglas" class="btn btn-default pull-right">            
			<i class="process-icon-export icon-export"></i> Exportar reglas      
		</button>
		{* <button type="submit" id="exportar_productos_pesados" name="exportar_productos_pesados" class="btn btn-default pull-right">            
			<i class="process-icon-anchor icon-anchor"></i> Productos Pesados      
		</button>	 *}
	</form>
	
</div>

{* Por si no cargamos el módulo correctamente sino desde acceso rápido, cargamos css y js aquí también 
	Para no cargar dos veces el archivo de javascript, comprobamos primero si ya está cargado buscando los elementos script del DOM y comprobamos si alguno tiene el src del archivo de javascript, si no lo encontramos, creamos el tag dinamicamnete
*}
<script type="text/javascript">
window.addEventListener("load", function() {
    

	const src = document.querySelector('#url_js').value;
	if (isScriptAlreadyIncluded(src)) {
		console.log('ya existe js');
	} else {
		console.log('NO existe js');
		const script_js = document.createElement('script');
		script_js.setAttribute("type", "text/javascript"); 
		script_js.src = src;	

		//hacemos append del script al body
		document.body.appendChild(script_js);
	}

	function isScriptAlreadyIncluded(src){
		var scripts = document.getElementsByTagName("script");
		for(var i = 0; i < scripts.length; i++) 
		if(scripts[i].getAttribute('src') == src) return true;
		return false;
	}
});

</script>


{* <script type="text/javascript" src="{$url_js}"></script>  *}

{* <select name="at_ct" class="inline-block">
			{foreach $content_types as $val => $name}
				<option value="{$val|escape:'html':'UTF-8'}"{if $current_ct == $val} selected{/if}>{$name|escape:'html':'UTF-8'}</option>
			{/foreach}
		</select>
		{foreach $special_params as $ct => $param}
			{foreach $param as $name => $options}
				<select name="{$name|escape:'html':'UTF-8'}" class="update-list inline-block special-param {$ct|escape:'html':'UTF-8'}{if $ct != $current_ct} hidden{/if}">
					{foreach $options as $opt_name => $display_name}
						<option value="{$opt_name|escape:'html':'UTF-8'}">{$display_name|escape:'html':'UTF-8'}</option>
					{/foreach}
				</select>
			{/foreach}
		{/foreach}
		<select name="at_lang" class="update-list inline-block{if $current_ct == 'theme' || $current_ct == 'module' } hidden{/if}">
			{foreach $languages as $iso => $name}
				{if $iso != 'all'}
					<option value="{$iso|escape:'html':'UTF-8'}"{if $iso == $current_lang_iso} selected{/if}>{$iso|escape:'html':'UTF-8'}</option>
				{/if}
			{/foreach}
		</select>
		<div class="inline-block sorting">
			<label class="label-inline"><span>{l s='Sort by' mod='autotranslator'}</span></label>
			<select name="order_by" class="update-list inline-block order-by">
				{foreach $sorting_options as $opt_name => $o}
					<option value="{$opt_name|escape:'html':'UTF-8'}" class="{if !empty($o.class)}special-option {$o.class|escape:'html':'utf-8'}{/if}"{if $order.by == $opt_name} selected{/if}>{$o.name|escape:'html':'UTF-8'}</option>
				{/foreach}
			</select>
			{$way_options = ['DESC' => 'icon-long-arrow-down', 'ASC' => 'icon-long-arrow-up']}
			{foreach $way_options as $value => $icon_class}
				<a href="#" class="{$icon_class|escape:'html':'UTF-8'} order-way-label{if $order.way == $value} active{/if}" data-way="{$value|escape:'html':'UTF-8'}"></a>
			{/foreach}
			<input type="hidden" name="order_way" value="{$order.way|escape:'html':'UTF-8'}" class="order-way update-list">
		</div>
		<div class="pull-right">
			<label class="alert-info"><input type="checkbox" class="overwrite_existing" name="overwrite_existing"{if !empty($overwrite_existing)} checked{/if}> {l s='Overwrite existing translations' mod='autotranslator'}</label>
		</div>
	</form>
	<div class="dynamic-list">
		{include file="./list.tpl"}
	</div> *}

{* <div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Documentation' mod='reglasamazon'}</h3>
	<p>
		&raquo; {l s='You can get a PDF documentation to configure this module' mod='reglasamazon'} :
		<ul>
			<li><a href="#" target="_blank">{l s='English' mod='reglasamazon'}</a></li>
			<li><a href="#" target="_blank">{l s='French' mod='reglasamazon'}</a></li>
		</ul>
	</p>
</div> *}
