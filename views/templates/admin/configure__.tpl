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

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Reglas de precios Amazon' mod='reglasamazon'}</h3>
	<p>
		Aquí puedes modificar los parámetros en función a los que se establecerán las reglas de precios para los diferentes marketplaces de Amazon.<br />
		Se muestra la información almacenada en la tabla de reglas de Amazon, puedes editar los valores, añadir un nuevo marketplace o eliminar uno existente.<br />
		El botón guardar almacenará los parámetros actuales. El botón exportar generará el documento para importar las reglas en Amazon.
	</p>	
</div>
 {* Quiero cargar la página de configuración del módulo con los datos que haya en la tabla frik_amazon_reglas, a modo de formulario rellenado, así puede editarse, añadir un nuevo país (o eliminar uno) y guardar lo que se edite o se cree.
 En lugar de utilizar el helper para forms de prestashop, que es un lío, genero todo directamente en el tpl *}
<div class="bootstrap panel main-block">
	<h3><i class="icon icon-cogs"></i> Configuración</h3>
	
	<form method="post" action="" class="form-inline">
		{if $no_hay_datos}
			<h1>NO HAY NINGUNA INFORMACIÓN ALMACENADA</h1>
			<hr>
		{else}
			{* {$marketplaces|@var_dump} *}
			{foreach $marketplaces as $marketplace}
				
				<div class="form-group row">
					<div class="form-group col-xs-1">
						<label for="pais">País</label>
						<input type="text" name="pais" id="pais" value="{$marketplace['pais']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="codigo">Código</label>
						<input type="text" name="codigo" id="codigo" value="{$marketplace['codigo']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="moneda">Moneda</label>
						<input type="text" name="moneda" id="moneda" value="{$marketplace['moneda']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="cambio">Cambio</label>
						<input type="text" name="cambio" id="cambio" value="{$marketplace['cambio']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="margen_minimo">Margen Mínimo</label>
						<input type="text" name="margen_minimo" id="margen_minimo" value="{$marketplace['margen_minimo']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="coste_track">Coste Track</label>
						<input type="text" name="coste_track" id="coste_track" value="{$marketplace['coste_track']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="coste_sign">Coste Sign</label>
						<input type="text" name="coste_sign" id="coste_sign" value="{$marketplace['coste_sign']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<div class="form-group col-xs-1">
						<label for="accion">Acción</label>
						<input type="text" name="accion" id="accion" value="{$marketplace['accion']|escape:'html':'UTF-8'}" class="form-control">
					</div>
					<button type="submit" class="btn btn-default">Eliminar</button>
				</div>

				<hr>
			{/foreach}
			
		{/if}	

		
		<br>
		<button type="submit" value="1" id="cancelar" name="cancelar" class="btn btn-default">            
			<i class="process-icon-cancel icon-cancel"></i> Cancelar       
		</button>
		<button type="submit" value="1" id="nuevo_marketplace" name="nuevo_marketplace" class="btn btn-default">            
			<i class="process-icon-plus icon-plus"></i> Añadir Marketplace     
		</button>

		<button type="submit" value="1" id="guarda_reglas" name="guarda_reglas" class="btn btn-default pull-right">            
			<i class="process-icon-save icon-save"></i> Guardar       
		</button>
		<button type="submit" value="1" id="exportar_reglas" name="exportar_reglas" class="btn btn-default pull-right">            
			<i class="process-icon-export icon-export"></i> Exportar reglas      
		</button>	
	</form>
	
</div>



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
