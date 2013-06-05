<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-Emulation
// Version 1.0
//--------------------------

if(!defined('TOOLKIT_VERSION'))
    exit("Veuillez charger le toolkit avant d'utiliser Debug !");

if(TOOLKIT_VERSION_ID < 101)
    exit("La version du toolkit ne correspond pas. Veuillez mettre à jour le Toolkit !");

#==================================================
#                  Classe de débug
#==================================================

/**
 * Gestion des debug
 * @since 1.1
 */
class Debug{
    /**
     * Affiche un fichier, pour le débug
     * @param string $file le chemin ver le fichier
     * @param int $line ligne de l'erreur
     */
    public static function displayFile($file, $line){
        if(!file_exists($file)){
            echo 'Fichier indisponible :/';
            return;
        }

        $f_data = file($file);

        echo '<table class="debug_file">';
        for($i = $line - 3; $i < $line + 2; $i++){
            $class = $line - 1 === $i ? 'class="debug_higthlight"' : '';
            printf('<tr %s><td>#%d</td><td><pre>%s</pre></td></tr>', $class, $i, $f_data[$i]);
        }
        echo '</table>';
    }
}
