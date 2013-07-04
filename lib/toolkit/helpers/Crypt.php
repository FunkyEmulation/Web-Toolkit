<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-emulation
// Version 1.1
//--------------------------


#==================================================
#                 Classe principale
#==================================================


/**
 * Helper pour cryptage / encodage
 * @since 1.1
 */
class Crypt{
    /**
     * sel statique.
     * Pour des raisons de sécurité, il est conseillé de le modifié
     * même si celà n'est pas obligatoire.
     * Mais modifiez-le avant d'enregistrer les mots de passes, sinon il sera impossible de
     * les vérifier par la suite !
     */
    const STATIC_SALT = 'WTK_';

    /**
     * Crypte un mot de passe, et retourne le mot de passe crypté + la clé
     * @param string $pass mot de passe à crypter
     * @param int $key_size taille de la clé à générer (si inférieur ou égal à 0, la clé n'est pas généré)
     * @param string $algo algorythme à utiliser
     * @return array
     * <pre>
     * array(
     *      0 => MOT DE PASSE HASHE
     *      1 => CLEF
     *      'hash' => MOT DE PASSE HASHE
     *      'key' => CLEF
     * )
     * </pre>
     */
    public static function crypt_password($pass, $key_size = 12, $algo = 'md5'){
        $str = self::STATIC_SALT.$pass;
        $key = '';

        if($key_size > 0)
            $key = self::generate_salt($key_size);

        $str .= $key;
        $str = hash($algo, $str);

        return array(
            $str,
            $key,
            'hash' => $str,
            'key' => $key
        );
    }

    /**
     * Vérifie si le mot de passe correspond au mot de passe crypté par crypt_password()
     * enregistré
     * @param string $pass entrée à vérifier
     * @param string $hash la mot de passe crypté par crypt_password()
     * @param int $key clmé généré par crypt_password
     * @param string $algo algorithme de cryptage utilisé (doit être le même que celui utilisé pour crypter)
     * @return boolean
     */
    public static function verif_password($pass, $hash, $key = '', $algo = 'md5'){
        $pass = hash($algo, self::STATIC_SALT.$pass.$key);

        return $pass === $hash;
    }

    /**
     * Génère un salt aléatoire
     * @param int $size taille du salt
     * @return string
     */
    public static function generate_salt($size = 12){
        $chars = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+-*/=%&$#');
        $c_length = count($chars);
        $ret = '';

        for($i = 0; $i < $size; $i++)
            $ret .= $chars[rand(0, $c_length - 1)];

        return $ret;
    }
}

#==================================================
#                 Fonctions helpers
#==================================================

if(!function_exists('crypt_password')){
    /**
     * Crypte un mot de passe, et retourne le mot de passe crypté + la clé
     * @param string $pass mot de passe à crypter
     * @param int $key_size taille de la clé à générer (si inférieur ou égal à 0, la clé n'est pas généré)
     * @param string $algo algorythme à utiliser
     * @return array
     * <pre>
     * array(
     *      0 => MOT DE PASSE HASHE
     *      1 => CLEF
     *      'hash' => MOT DE PASSE HASHE
     *      'key' => CLEF
     * )
     * </pre>
     */
    function crypt_password($pass, $key_size = 12, $algo = 'md5'){
        return Crypt::crypt_password($pass, $key_size, $algo);
    }
}

if(!function_exists('verif_password')){
    /**
     * Vérifie si le mot de passe correspond au mot de passe crypté par crypt_password()
     * enregistré
     * @param string $pass entrée à vérifier
     * @param string $hash la mot de passe crypté par crypt_password()
     * @param int $key clmé généré par crypt_password
     * @param string $algo algorithme de cryptage utilisé (doit être le même que celui utilisé pour crypter)
     * @return boolean
     */
    function verif_password($pass, $hash, $key = '', $algo = 'md5'){
        return Crypt::verif_password($pass, $hash, $key, $algo);
    }
}