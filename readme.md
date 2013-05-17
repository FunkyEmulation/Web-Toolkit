# Web Toolkit
_Par v4vx_

## Présentation

Web Toolkit est une bibliothèque permettant de facilité le codage en PHP des CMS.
Il est principalement axé sécurité, le point faible de la plus part des CMS actuels. Son utilisation est des plus simples possible, aucunes normes est obligatoire, et le Toolkit s'adapte totalement au codage **OOP** comme **procedurial** !

Web Toolkit est basé sur _Single Framework_, avec un code plus flexible et complet, tout en gardant les performances ! (moins de 1ms pour charge le Tookit).

## Utilisation

Son utilisation est faite pour être **simple** et **intuitive**.
Pour l'utiliser, il faut juste télécharger la dernière version (actuellement **v1.0**), mettre le dossier _lib_ à la raine du site, et include le Toolkit au tout début du code !

```php
<?php
//Fichier index.php
require 'lib/toolkit/toolkit.php';

/*
 * Le code ici !
 */
```

Le Toolkit n'imposant pas de structure stricte, il est utilisable sur un projet déjà avancé, et ce **sans rien toucher au code actuel** !

## Fonctionnalités

- [x] Un système de configuration (simple)
- [x] Un registre (enregistre des variables de façon à ce qu'elles soient utilisable n'importe où dans le code)
- [x] Un Loader (s'occupe de charge des classes / lib, ect...)
- [x] Un système de bdd utilisant PDO, mais simplifié, aliant facilité et sécurité
- [x] gestion des ActiveRecord (Opérations de sauvegarde ou de suppression facilités)
- [x] Gestions des entrés de façon sécurisé (sans passer directement par $_GET ou $_POST) avec nettoyage des varaibles
- [x] Système de cache performant et simple
- [x] Système de gestion des sorties (Output), avec vues et layout en natif

## Liens :

Description du projet : [Funky-emu](http://www.funky-emu.net/showthread.php?tid=41661&pid=336602#pid336602)
