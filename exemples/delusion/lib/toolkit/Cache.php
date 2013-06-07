<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-emulation
// Version 1.0
//--------------------------

if(!defined('TOOLKIT_VERSION'))
    exit("Veuillez charger le toolkit avant d'utiliser le cache !");

if(TOOLKIT_VERSION_ID < 100)
    exit("La version du toolkit ne correspond pas. Veuillez mettre à jour le Toolkit, ou le cache !");

/**
 * Classe de gestion de cache
 * @since 1.0
 */
class Cache extends Singleton {

    /**
     * Enregistre les dernières données utilisées
     * @var array
     */
    private $data = array();

    /**
     * Retourne la valeur enregistré dans la cache
     * @param string $key
     * @param boolean $deleteAfter Supprimer après l'accès ?
     * @return mixed La valeur enregistré, ou FALSE si inexistant ou trop ancien
     */
    public function get($key, $deleteAfter = false) {
        if(isset($this->data[$key]))
            return $this->data[$key];

        $file = self::getFile($key);

        if (!file_exists($file))
            return false;

        $data = unserialize(file_get_contents($file));

        if ($data['deletion_time'] < time()) {
            unlink($file);
            return false;
        }

        if ($deleteAfter){
            unlink($file);
            return $data['content'];
        }

        return $this->data[$key] = $data['content'];
    }

    /**
     * Enregistre une valeur dans le cache
     * @param string $key L'identifiant
     * @param mixed $value La valeur à enregistrer
     * @param int $time La durée de vie
     * @return boolean
     */
    public function set($key, $value, $time = 60) {
        $file = self::getFile($key);

        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);

        $data = array(
            'content' => $value,
            'deletion_time' => time() + $time
        );

        $this->data[$key] = $value;

        return file_put_contents($file, serialize($data));
    }

    /**
     * Supprime une valeur enregistré dans le cache
     * @param string $key
     * @return boolean
     */
    public function delete($key) {
        if(isset($this->data[$key]))
            unset($this->data[$key]);

        return unlink(self::getFile($key));
    }

    private static function getFile($key) {
        return TOOLKIT_DIR . 'cache' . DS . str_replace('.', DS, $key) . '.cache';
    }

}

#==================================================
#                   Fonctions
#==================================================

/**
 * Enregistre une valeur dans le cache
 * @param string $key L'identifiant
 * @param mixed $value La valeur à enregistrer
 * @param int $time La durée de vie
 * @return boolean
 */
function cache_save($key, $value, $time = 60){
    return Cache::instance()->set($key, $value, $time);
}

/**
 * Retourne la valeur enregistré dans la cache
 * @param string $key
 * @param boolean $deleteAfter Supprimer après l'accès ?
 * @return mixed La valeur enregistré, ou FALSE si inexistant ou trop ancien
 */
function cache_get($key, $deleteAfter = false){
    return Cache::instance()->get($key, $deleteAfter);
}

/**
 * Supprime une valeur enregistré dans le cache
 * @param string $key
 * @return boolean
 */
function cache_delete($key){
    return Cache::instance()->delete($key);
}
