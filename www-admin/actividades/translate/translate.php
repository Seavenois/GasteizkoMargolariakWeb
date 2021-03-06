<?php
    $http_host = $_SERVER['HTTP_HOST'];
    include("../../functions.php");
    $con = startdb();
    if (!checkSession($con)){
        header("Location: /index.php");
        exit (-1);
    }
    else{
        $id = mysqli_real_escape_string($con, $_GET['id']);
        $q = mysqli_query($con, "SELECT * FROM activity WHERE id = $id");
        if(mysqli_num_rows($q) == 0){
            header("Location: /actividades/translate/");
            exit (-1);
        }
        else{
            $r = mysqli_fetch_array($q);
?>
<html>
    <head>
        <meta content="text/html; charset=windows-1252" http-equiv="content-type"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <title>Traducir <?=$r['title_es']?> - Administraci&oacute;n</title>
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="/css/ui.css"/>
        <link rel="stylesheet" type="text/css" href="/css/actividades.css"/>
        <!-- CSS for mobile version -->
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/ui.css"/>
        <link rel="stylesheet" type="text/css" media="(max-width : 990px)" href="/css/m/actividades.css"/>
        <!-- Script files -->
        <script type="text/javascript" src="script.js"></script>
        <script type="text/javascript" src="/script/ui.js"></script>
        <script src="../../ckeditor/ckeditor.js"></script>
    </head>
    <body>
<?php
        include('../../toolbar.php');
?>
        <div id='content'>
            <div class='section'>
                <h3>Taducir &#8216;<?=$r["title_es"]?>&#8217;</h3>
                <div id='translation_instructions' class='entry'>
                    <h4>C&oacute;mo traducir</h4>
                    <ul>
                        <li>Rellena los campos de abajo para el idioma correspondiente (columna de en medio para ingl&eacute;s, derecha para euskera)</li>
                        <li>No es necesario traducir todos los campos ni todos los idiomas. Traduce solo lo que sepas y quieras.</li>
                        <li>Al terminar, recuerda pulsar el boton &#8216;Guardar&#8217; al final de la p&acute;gina.</li>
                        <li>Muchas gracias por tu trabajo!</li>
                    </ul>
                </div>
                <br/><br/>
                <form method='post' action='/actividades/translate/apply.php'>
                    <input type='hidden' name='id' value='<?=$r["id"]?>'/>
                    <table id='table_translate_languages'>
                        <tr>
                            <td class='translate_language'>
                                <div class='entry translation'>
                                    <h3>Castellano</h3>
                                    <div class='translate_row_title'>
                                        <span class='bold'>T&iacute;tulo: </span><?=$r["title_es"]?>
                                    </div>
                                    <div class='translate_row_description'>
                                        <span class='bold'>Descripci&oacute;n</span>
                                        <br/>
                                        <p><?=$r["text_es"]?></p>
                                    </div>
<?php
                                    if (strlen($r['after_es']) > 0){
?>
                                        <div class='translate_row_after'>
                                            <span class='bold'>Texto para despu&eacute;s</span>
                                            <br/>
                                            <p><?=$r["after_es"]?></p>
                                        </div>
<?php
                                    }
                                    $q_i = mysqli_query($con, "SELECT id, name_es, name_en, name_eu, description_es, description_en, description_eu, date_format(start, '%H:%i') AS start FROM activity_itinerary WHERE activity = $r[id];");
                                    if (mysqli_num_rows($q_i) > 0){
?>
                                        <div class='translate_row_itinerary'>
                                            <span class='bold'>Itinerario<br/></span>
                                            <table id='table_translate_itinerary'>
<?php
                                                while ($r_i = mysqli_fetch_array($q_i)){
?>
                                                    <tr>
                                                        <td><?=$r_i["start"]?></td>
                                                        <td>&nbsp;&nbsp;<?=$r_i["name_es"]?>&nbsp;&nbsp;</td>
                                                        <td>&nbsp;&nbsp;<?=$r_i["description_es"]?>&nbsp;&nbsp;</td>
                                                    </tr>
<?php
                                                }
?>
                                            </table>
                                        </div>
<?php
                                    }
?>
                                </div>
                            </td>
                            <td class='translate_language'>
                                <div class='entry translation'>
                                    <h3>Ingl&eacute;s</h3>
                                    <div class='translate_row_title'>
                                        <span class='bold'>T&iacute;tulo: </span>
                                        <input required type='text' name='title_en' required value='<?=$r["title_en"]?>'/>
                                    </div>
                                    <div class='translate_row_description'>
                                        <span class='bold'>Descripci&oacute;n</span>
                                        <br/>
                                        <textarea name='text_en' required><?=$r["text_en"]?></textarea>
                                    </div>
                                    <script>CKEDITOR.replace('text_en');</script>
<?php
                                    if (strlen($r['after_es']) > 0){
?>
                                        <div class='translate_row_after'>
                                            <span class='bold'>Texto para despu&eacute;s</span>
                                            <br/>
                                            <textarea name='after_en' required><?=$r["after_en"]?></textarea>
                                        </div>
                                        <script>CKEDITOR.replace('after_en');</script>
<?php
                                    }
                                    $q_i = mysqli_query($con, "SELECT id, name_es, name_en, name_en, description_es, description_en, description_en, date_format(start, '%H:%i') AS start FROM activity_itinerary WHERE activity = $r[id];");
                                    if (mysqli_num_rows($q_i) > 0){
?>
                                        <div class='translate_row_itinerary'>
                                            <span class='bold'>Itinerario</span>
                                            <br/>
                                            <table id='table_translate_itinerary'>
<?php
                                                while ($r_i = mysqli_fetch_array($q_i)){
?>
                                                    <tr>
                                                        <td><?$r_i["start"]?></td>
                                                        <td>
                                                            <input type='text' name='it_<?=$r_i["id"]?>_name_en' required value='<?=$r_i["name_en"]?>'/>
                                                        </td>
                                                        <td>
                                                            <textarea name='it_<?=$r_i["id"]?>_desc_en' required><?=$r_i["description_en"]?></textarea>
                                                        </td>
                                                    </tr>
<?php
                                                }
?>
                                            </table>
                                        </div>
<?php
                                    }
?>
                                </div>
                            </td>
                            <td class='translate_language'>
                                <div class='entry translation'>
                                    <h3>Euskera</h3>
                                    <div class='translate_row_title'>
                                        <span class='bold'>T&iacute;tulo: </span>
                                        <input required type='text' name='title_eu' required value='<?=$r["title_eu"]?>'/>
                                    </div>
                                    <div class='translate_row_description'>
                                        <span class='bold'>Descripci&oacute;n</span>
                                        <br/>
                                        <textarea name='text_eu' required><?=$r["text_eu"]?></textarea>
                                    </div>
                                    <script>CKEDITOR.replace('text_eu');</script>
<?php
                                    if (strlen($r['after_es']) > 0){
?>
                                        <div class='translate_row_after'>
                                            <span class='bold'>Texto para despu&eacute;s</span>
                                            <br/>
                                            <textarea name='after_eu' required><?=$r["after_eu"]?></textarea>
                                        </div>
                                        <script>CKEDITOR.replace('after_eu');</script>
<?php
                                    }
                                    $q_i = mysqli_query($con, "SELECT id, name_es, name_eu, name_eu, description_es, description_eu, description_eu, date_format(start, '%H:%i') AS start FROM activity_itinerary WHERE activity = $r[id];");
                                    if (mysqli_num_rows($q_i) > 0){
?>
                                        <div class='translate_row_itinerary'>
                                            <span class='bold'>Itinerario</span>
                                            <br/>
                                            <table id='table_translate_itinerary'>
<?php
                                                while ($r_i = mysqli_fetch_array($q_i)){
?>
                                                    <tr>
                                                        <td><?=$r_i["start"]?></td>
                                                        <td>
                                                            <input type='text' name='it_<?=$r_i["id"]?>_name_eu' required value='<?=$r_i["name_eu"]?>'/>
                                                        </td>
                                                        <td>
                                                            <textarea name='it_<?=$r_i["id"]?>_desc_eu' required><?=$r_i["description_eu"]?></textarea>
                                                        </td>
                                                    </tr>
<?php
                                                }
?>
                                            </table>
                                        </div>
<?php
                                    }
?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id='translate_controls'>
                        <input type='submit' value='Guardar'/>
                        <input type='button' value='Cancelar y salir' onClick='exitTranslation();'/>
                    </div>
                </form>
?>
            </div>
        </div>
    </body>
</html>
<?php
    }
?>
