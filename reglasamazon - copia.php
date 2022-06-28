<?php
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
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Reglasamazon extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'reglasamazon';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Sergio';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Reglas de precios Amazon');
        $this->description = $this->l('Establecer los parámetros de las reglas de precios para los marketplaces de Amazon.');

        $this->confirmUninstall = $this->l('¿Me vas a desinstalar?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('REGLASAMAZON_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('REGLASAMAZON_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        // if (((bool)Tools::isSubmit('submitReglasamazonModule')) == true) {
        //     $this->postProcess();
        // }

        if (((bool)Tools::isSubmit('eliminar_marketplace')) == true) {
            //se ha pulsado eliminar en un marketplace almacenado. Obtenemos el id de la tabla con el value del botón pulsado y lo eliminamos de la tabla
            $id_marketplace = Tools::getValue('eliminar_marketplace');
            $sql_delete_marketplace = 'DELETE FROM frik_amazon_reglas WHERE id_amazon_reglas = '.$id_marketplace;
            Db::getInstance()->Execute($sql_delete_marketplace);            
        }

        if (((bool)Tools::isSubmit('cancelar')) == true) {
            //usamos el botón cancelar para que se recargue la pantalla sin haber guardado nada
        }    
        
        if (((bool)Tools::isSubmit('guarda_reglas')) == true) {
            // se ha pulsado guardar, comprobamos todos los campos de los formularios y hacemos update o insert en tabla frik_amazon_reglas
            //he almacenado los ids de la tabla frik_amazon_reglas que hay que hacer update en hidden input con name 'hiddenids'
            //he almacenado los ids que hay que hacer insert en hidden input con name 'hiddennuevosids'
            //con ese id sacamos los campos del formulario
            // print_r($_POST['hiddenids']);
            // print_r($_POST['hiddennuevosids']);
            // print_r($_POST['nombre_regla']);

            //08/03/2022 comprobamos el campo del input nombre_reglas
            $nombre_regla_almacenado = Configuration::get('REGLASAMAZON_NOMBRE_REGLA');
            $nombre_regla_formulario = $_POST['nombre_regla'];

            if ($nombre_regla_almacenado != $nombre_regla_formulario) {
                Configuration::updateValue('REGLASAMAZON_NOMBRE_REGLA', $nombre_regla_formulario);
            }

            //los id vienen unidos por '-'
            if ($_POST['hiddenids']) {
                $ids_tabla = explode('-', $_POST['hiddenids']);
                
                //sacamos un update por cada id de ids_tabla
                foreach ($ids_tabla as $id) {
                    $pais = $_POST['pais_'.$id]; 
                    $codigo = strtoupper($_POST['codigo_'.$id]);
                    $moneda = strtoupper($_POST['moneda_'.$id]);
                    $cambio = $_POST['cambio_'.$id];
                    $margen_minimo = $_POST['margen_minimo_'.$id];
                    $margen_minimo_c = $_POST['margen_minimo_c_'.$id];
                    $margen_minimo_outlet = $_POST['margen_minimo_outlet_'.$id];
                    $coste_track = $_POST['coste_track_'.$id];
                    $coste_sign = $_POST['coste_sign_'.$id];
                    $accion = $_POST['accion_'.$id];

                    //obtenemos los campos de la tabla y los comparamos para saber si algo ha cambiado, si ha cambiado hacemos update, si no no hacemos nada
                    $sql_select = 'SELECT * FROM frik_amazon_reglas WHERE id_amazon_reglas = '.$id;
                    $select = Db::getInstance()->ExecuteS($sql_select);
                    
                    if (($select[0]['pais'] != $pais) || ($select[0]['codigo'] != $codigo) || ($select[0]['moneda'] != $moneda) || ($select[0]['cambio'] != $cambio) || ($select[0]['margen_minimo'] != $margen_minimo) || ($select[0]['margen_minimo_c'] != $margen_minimo_c) || ($select[0]['margen_minimo_outlet'] != $margen_minimo_outlet) || ($select[0]['coste_track'] != $coste_track) || ($select[0]['coste_sign'] != $coste_sign) || ($select[0]['accion'] != $accion)) {
                        $sql_update_marketplace = 'UPDATE frik_amazon_reglas SET
                            pais = "'.$pais.'",
                            codigo = "'.$codigo.'",
                            moneda = "'.$moneda.'",
                            cambio = '.$cambio.',
                            margen_minimo = '.$margen_minimo.',
                            margen_minimo_c = '.$margen_minimo_c.',
                            margen_minimo_outlet = '.$margen_minimo_outlet.',
                            coste_track = '.$coste_track.',
                            coste_sign = '.$coste_sign.',
                            accion = "'.$accion.'",
                            date_upd = NOW()
                            WHERE id_amazon_reglas = '.$id;
                        Db::getInstance()->Execute($sql_update_marketplace); 
                    } 
                }
            }
            
            if ($_POST['hiddennuevosids']) {
                $ids_nuevos = explode('-', $_POST['hiddennuevosids']);
                
                //sacamos un insert por cada id de ids_nuevos, vienen con nuevo_ delante de cada id con lo que recoge p.ej pais_nuevo_id 
                foreach ($ids_nuevos as $id) {
                    $pais = $_POST['pais_'.$id]; echo 'pais nuevo='.$pais;
                    $codigo = strtoupper($_POST['codigo_'.$id]);
                    $moneda = strtoupper($_POST['moneda_'.$id]);
                    $cambio = $_POST['cambio_'.$id];
                    $margen_minimo = $_POST['margen_minimo_'.$id];
                    $margen_minimo_c = $_POST['margen_minimo_c_'.$id];
                    $margen_minimo_outlet = $_POST['margen_minimo_outlet_'.$id];
                    $coste_track = $_POST['coste_track_'.$id];
                    $coste_sign = $_POST['coste_sign_'.$id];
                    $accion = $_POST['accion_'.$id];

                    $sql_insert_nuevo_marketplace = 'INSERT INTO frik_amazon_reglas
                        (pais, codigo, moneda, cambio, margen_minimo, margen_minimo_c, margen_minimo_outlet, coste_track, coste_sign, accion, date_add)
                        VALUES ("'.$pais.'", "'.$codigo.'", "'.$moneda.'", '.$cambio.', '.$margen_minimo.', '.$margen_minimo_c.', '.$margen_minimo_outlet.', '.$coste_track.', '.$coste_sign.', "'.$accion.'", NOW())';
                    Db::getInstance()->Execute($sql_insert_nuevo_marketplace); 
                }
            }

        }

        if (((bool)Tools::isSubmit('exportar_reglas')) == true) {
            //si se pulsa exportar reglas ejecutamos el script que obtiene de la BD los productos con categoría amazon, crea las reglas de precio en función a los parámetros y crea y ofrece un archivo txt con el formato adecuado para Amazon
            //01/05/2021 sumamos el margen mínimo también al precio máximo para evitar que quede por debajo del precio exportado
            //03/06/2021 obtenemos los días que un producto es considerado novedad (clasificación B) desde lafrips_configuration así como los límites para considerar un producto C o B según consumo
            // 10/06/2021 Se cambia la gestión de lafrips_consumos, novedad va marcado en la tabla y la clasificación abc también, de modo que se puede modificar la sql
            $novedad = (int)Configuration::get('CLASIFICACIONABC_NOVEDAD', 0);
            $maxC = Configuration::get('CLASIFICACIONABC_MAX_C');        
            
            //08/03/2022 obtenemos nombre regla
            $nombre_regla = Configuration::get('REGLASAMAZON_NOMBRE_REGLA');

            $sql_productos = "SELECT IFNULL(pat.reference, pro.reference) AS sku, 
            REPLACE(
            CASE #cuando es España, pvp real más coste envío
            WHEN are.codigo = 'ES' THEN (
                            CASE
                            WHEN (#si hay pvp descuento > 0 cogemos descuento, si no pvp normal
                                CASE 
                                    WHEN spp.reduction_type = 'percentage' THEN ROUND(((pro.price*((tax.rate/100)+1)) - (pro.price*((tax.rate/100)+1) * spp.reduction)),2)	
                                    WHEN spp.reduction_type = 'amount'  THEN ROUND(((pro.price*((tax.rate/100)+1)) - spp.reduction),2)	
                                    ELSE 0
                                END) > 0 THEN (
                                    CASE 
                                        WHEN spp.reduction_type = 'percentage' THEN ROUND(((pro.price*((tax.rate/100)+1)) - (pro.price*((tax.rate/100)+1) * spp.reduction)),2)	
                                        WHEN spp.reduction_type = 'amount'  THEN ROUND(((pro.price*((tax.rate/100)+1)) - spp.reduction),2)	
                                        ELSE 0
                                    END)
                            ELSE ROUND(pro.price*((tax.rate/100)+1),2)
                            END
                        ) + are.coste_track
            ELSE ( #Resto marketplaces, calculamos total, si el total es > 30 , usamos coste signed, y hacemos cambio de moneda. Aquí miramos si es outlet, C o resto
                    CASE #case para saber si es outlet, c o normal
                        WHEN (SELECT id_product FROM lafrips_category_product WHERE id_category = 319 AND id_product = pro.id_product) THEN (
                            CASE 
                            WHEN ROUND(( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_track) * ( ((are.margen_minimo_outlet + 15)/100) + 1) ), 2) > 30 THEN 
                                    ROUND((( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_sign) * ( ((are.margen_minimo_outlet + 15)/100) + 1) )) * are.cambio, 2)
                            ELSE ROUND((( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_track) * ( ((are.margen_minimo_outlet + 15)/100) + 1) )) * are.cambio, 2)
                            END
                        ) #fin case es outlet
                        WHEN (con.abc = 'C' OR con.consumo IS NULL) THEN (
                            CASE 
                            WHEN ROUND(( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_track) * ( ((are.margen_minimo_c + 15)/100) + 1) ), 2) > 30 THEN 
                                    ROUND((( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_sign) * ( ((are.margen_minimo_c + 15)/100) + 1) )) * are.cambio, 2)
                            ELSE ROUND((( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_track) * ( ((are.margen_minimo_c + 15)/100) + 1) )) * are.cambio, 2)
                            END
                        ) #fin case es C
                        ELSE (
                            CASE 
                            WHEN ROUND(( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_track) * ( ((are.margen_minimo + 15)/100) + 1) ), 2) > 30 THEN 
                                    ROUND((( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_sign) * ( ((are.margen_minimo + 15)/100) + 1) )) * are.cambio, 2)
                            ELSE ROUND((( ((pro.wholesale_price*((tax.rate/100)+1)) + are.coste_track) * ( ((are.margen_minimo + 15)/100) + 1) )) * are.cambio, 2)
                            END
                        ) #fin case no es outlet ni C        
                    END
                )
            END
            ,'.',',')
            AS 'minimum-seller-allowed-price',
            REPLACE(
            CASE #case para saber si es outlet, c o normal
                WHEN (SELECT id_product FROM lafrips_category_product WHERE id_category = 319 AND id_product = pro.id_product) THEN (
                    CASE 
                    WHEN ROUND(pro.price*((tax.rate/100)+1) + are.coste_track,2) > 30 THEN 
                            ROUND(((pro.price*((tax.rate/100)+1) + are.coste_sign) * ( ((are.margen_minimo_outlet + 15)/100) + 1)) * are.cambio ,2)		
                    ELSE ROUND(((pro.price*((tax.rate/100)+1) + are.coste_track) * ( ((are.margen_minimo_outlet + 15)/100) + 1)) * are.cambio ,2)
                    END
                ) #fin case es outlet
                WHEN (con.abc = 'C' OR con.consumo IS NULL) THEN (
                    CASE 
                    WHEN ROUND(pro.price*((tax.rate/100)+1) + are.coste_track,2) > 30 THEN 
                            ROUND(((pro.price*((tax.rate/100)+1) + are.coste_sign) * ( ((are.margen_minimo_c + 15)/100) + 1)) * are.cambio ,2)		
                    ELSE ROUND(((pro.price*((tax.rate/100)+1) + are.coste_track) * ( ((are.margen_minimo_c + 15)/100) + 1)) * are.cambio ,2)
                    END
                ) #fin case es C
                ELSE (
                    CASE 
                    WHEN ROUND(pro.price*((tax.rate/100)+1) + are.coste_track,2) > 30 THEN 
                            ROUND(((pro.price*((tax.rate/100)+1) + are.coste_sign) * ( ((are.margen_minimo + 15)/100) + 1)) * are.cambio ,2)		
                    ELSE ROUND(((pro.price*((tax.rate/100)+1) + are.coste_track) * ( ((are.margen_minimo + 15)/100) + 1)) * are.cambio ,2)
                    END
                ) #fin case no es outlet ni C        
            END
            ,'.',',')
            AS 'maximum-seller-allowed-price', #si el pvp sin descuentos + coste track es > 30, se pone signed. Luego se añade amazon y margen mínimo y cambio moneda  
            are.codigo AS 'country-code', 
            are.moneda AS 'currency-code', 
            '$nombre_regla' AS 'rule-name', #08/03/2022 posibilidad de cambiar nombre regla
            are.accion AS 'rule-action'
            FROM lafrips_product pro
            JOIN lafrips_stock_available ava ON pro.id_product = ava.id_product #AND ava.id_product_attribute = 0
            LEFT JOIN lafrips_product_attribute pat ON pat.id_product = ava.id_product AND pat.id_product_attribute = ava.id_product_attribute
            JOIN frik_amazon_reglas are
            JOIN lafrips_tax_rule tar ON pro.id_tax_rules_group = tar.id_tax_rules_group AND tar.id_country = 6
            JOIN lafrips_tax tax ON tax.id_tax = tar.id_tax
            LEFT JOIN lafrips_specific_price spp ON ava.id_product =  spp.id_product
                AND spp.from_quantity = 1    
                AND spp.id_specific_price_rule = 0
                AND spp.id_customer = 0
                AND ( spp.to = '0000-00-00 00:00:00' OR (spp.from < NOW() AND spp.to > NOW() ))
            LEFT JOIN lafrips_consumos con ON con.id_product = ava.id_product AND con.id_product_attribute = ava.id_product_attribute
            WHERE pro.id_product IN ( #categorías aAmazon
            SELECT id_product FROM lafrips_category_product WHERE id_category IN (2164, 2347, 2351, 2356, 2360, 2366, 2368, 2372, 2383, 2445, 2446, 2452))
            AND ava.quantity > 0
            AND #si el producto tiene atributos, evitamos el producto base, solo el atributo con stock
            (
                CASE
                    WHEN (SELECT COUNT(id_product) FROM lafrips_stock_available WHERE id_product = pro.id_product) > 1 THEN ava.id_product_attribute != 0
                    ELSE ava.id_product_attribute = 0
                END
            )
            AND pro.active = 1
            AND pro.cache_is_pack = 0
            ORDER BY pro.id_product, sku, are.codigo ASC";

            if ($productos = Db::getInstance()->ExecuteS($sql_productos)){ 
                //creamos y abrimos el txt donde meteremos la información
                $archivo = 'reglas_globales_amazon_'.date('d').'-'.date('m').'-'.date('Y').'_'.date('H').date('i').date('s').'.txt';
                $contenido = fopen($archivo, "w") or die("Unable to open file!");

                // Las primeras 3 líneas del archivo están reservadas para Amazon. Las meto a mano, haciendo el salto de línea y en la 2 y 3 separando con tabulador
                // \n y \t tienen que ir entre comillas dobles para que sean cambio de página y tabulado
                fwrite($contenido, "AutomatePricing-1.0	\"Las 3 primeras filas son para uso exclusivo de Amazon.com; no modifiques ni elimines las 3 primeras filas.\"\n");
                fwrite($contenido, "SKU\tPrecio mínimo de venta\tPrecio máximo de venta\tCódigo de país\tCódigo de divisa\tNombre de la regla\tAcción de la regla\n");
                fwrite($contenido, "sku\tminimum-seller-allowed-price\tmaximum-seller-allowed-price\tcountry-code\tcurrency-code\trule-name\trule-action\n");

                foreach ($productos as $producto){
                    $sku = $producto['sku'];
                    $minimum = $producto['minimum-seller-allowed-price'];
                    // $maximum = $producto['maximum-seller-allowed-price']; Por problemas con el máximo, lo ponemos doble para probar
                    $maximum = $producto['maximum-seller-allowed-price']*2;
                    $country = $producto['country-code'];
                    $currency = $producto['currency-code'];
                    $rule = $producto['rule-name'];
                    $action = $producto['rule-action'];
                    
                    //las variables se ponen sin '..' porque al ir entre dobles comillas las interpreta directamente
                    fwrite($contenido, "$sku\t$minimum\t$maximum\t$country\t$currency\t$rule\t$action\n");
                    
                }

                //una vez lleno, cerramos el archivo
                fclose($contenido);
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.basename($archivo));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($archivo));
                header("Content-Type: text/plain");
                readfile($archivo);

            }

        }   

        $this->context->smarty->assign('module_dir', $this->_path);

        //buscamos en la tabla frik_amazon_reglas si existe ya alguna configuración
        $sql_marketplaces = 'SELECT * FROM frik_amazon_reglas';
        $marketplaces = Db::getInstance()->ExecuteS($sql_marketplaces);

        //08/03/2022 obtenemos el nombre configurado para las reglas
        $nombre_regla = Configuration::get('REGLASAMAZON_NOMBRE_REGLA'); 

        if (count($marketplaces) < 1) {
            $no_hay_datos = true;
        } else {
            $no_hay_datos = false;
            
        }

        //al cargar el módulo desde el menú de acceso rápido se da un problema con el javascript, no pasamos por este archivo y no se le indica la url para que lo cargue, lo cual no ocurre si iniciamos el módulo desde la pestaña modulos. Para solucionarlo le vamos a poner un <script src=blabla> en configure.tpl para asegurarnos de cargarlo. Enviamos la base url como variable para que se pueda usar tanto en producción como en test        

        $this->context->smarty->assign(array(
            'no_hay_datos' => $no_hay_datos,
            'marketplaces' => $marketplaces,
            'nombre_regla' => $nombre_regla,
            'url_js' => $this->_path.'views/js/back_reglas_amazon.js',
            'url_css' => $this->_path.'views/css/back_reglas_amazon.css',
            
        ));

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        // return $output.$this->renderForm();
        // para no utilizar el help configform etc, montamos todo directamente en configure.tpl en lugar de renderizar nada 
        return $output;
    }
    //No uso todo lo del config form etc
    
    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    // protected function renderForm()
    // {
    //     $helper = new HelperForm();

    //     $helper->show_toolbar = false;
    //     $helper->table = $this->table;
    //     $helper->module = $this;
    //     $helper->default_form_language = $this->context->language->id;
    //     $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

    //     $helper->identifier = $this->identifier;
    //     $helper->submit_action = 'submitReglasamazonModule';
    //     $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
    //         .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
    //     $helper->token = Tools::getAdminTokenLite('AdminModules');

    //     $helper->tpl_vars = array(
    //         'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
    //         'languages' => $this->context->controller->getLanguages(),
    //         'id_language' => $this->context->language->id,
    //     );

    //     return $helper->generateForm(array($this->getConfigForm()));
    // }

    // /**
    //  * Create the structure of your form.
    //  */
    // protected function getConfigForm()
    // {
    //     return array(
    //         'form' => array(
    //             'legend' => array(
    //             'title' => $this->l('Settings'),
    //             'icon' => 'icon-cogs',
    //             ),
    //             'input' => array(
    //                 array(
    //                     'type' => 'switch',
    //                     'label' => $this->l('Live mode'),
    //                     'name' => 'REGLASAMAZON_LIVE_MODE',
    //                     'is_bool' => true,
    //                     'desc' => $this->l('Use this module in live mode'),
    //                     'values' => array(
    //                         array(
    //                             'id' => 'active_on',
    //                             'value' => true,
    //                             'label' => $this->l('Enabled')
    //                         ),
    //                         array(
    //                             'id' => 'active_off',
    //                             'value' => false,
    //                             'label' => $this->l('Disabled')
    //                         )
    //                     ),
    //                 ),
    //                 array(
    //                     'col' => 3,
    //                     'type' => 'text',
    //                     'prefix' => '<i class="icon icon-envelope"></i>',
    //                     'desc' => $this->l('Enter a valid email address'),
    //                     'name' => 'REGLASAMAZON_ACCOUNT_EMAIL',
    //                     'label' => $this->l('Email'),
    //                 ),
    //                 array(
    //                     'type' => 'password',
    //                     'name' => 'REGLASAMAZON_ACCOUNT_PASSWORD',
    //                     'label' => $this->l('Password'),
    //                 ),
    //             ),
    //             'submit' => array(
    //                 'title' => $this->l('Save'),
    //             ),
    //         ),
    //     );
    // }

    // /**
    //  * Set values for the inputs.
    //  */
    // protected function getConfigFormValues()
    // {
    //     return array(
    //         'REGLASAMAZON_LIVE_MODE' => Configuration::get('REGLASAMAZON_LIVE_MODE', true),
    //         'REGLASAMAZON_ACCOUNT_EMAIL' => Configuration::get('REGLASAMAZON_ACCOUNT_EMAIL', 'contact@prestashop.com'),
    //         'REGLASAMAZON_ACCOUNT_PASSWORD' => Configuration::get('REGLASAMAZON_ACCOUNT_PASSWORD', null),
    //     );
    // }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back_reglas_amazon.js');
            $this->context->controller->addCSS($this->_path.'views/css/back_reglas_amazon.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
