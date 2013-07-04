<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-emulation
// Version 1.0
//--------------------------


#==================================================
#                Classe helper HTML
#==================================================

/**
 * Helper HTML
 * @since 1.1
 */
class HTML{
    /**
     * Définie l'encodage de la page
     * @param string $charset
     */
    public static function contentType($charset = 'utf-8'){
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />';
    }
}

#==================================================
#                    Fonctions
#==================================================

/**
 * Définie l'encodage de la page
 * @param string $charset
 */
function html_content_type($charset = 'utf-8'){
    HTML::contentType($charset);
}