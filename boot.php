<?php
// boot php

// hier werden grundsätzliche Sachen gemacht, die nicht so gut oder gar nicht erklärt werden im den docs
// extention point - hook - hier wird die action eingehängt z.B. 
// 'CLANG_ADDED', 'CLANG_DELETED', 'CACHE_DELETED', 'PACKETGES_LOADED'
// 

if (rex::isBackend() && rex::getUser())
    {
        // Prüft ob der DebugMode in System aktiviert ist und ein Request erfolgte
        if (rex::isDebugMode() && rex_request_method() == 'get')
        {

            // Compiler
            $compiler = new rex_scss_compiler();
            // Hauptverzeichnis des AddOns
            $compiler->setRootDir($this->getPath());
            // Festlegen des SCSS-Files
            $compiler->setScssFile($this->getPath('scss/guinav.scss'));
            // Wo soll die kompilierte Version erstellt werden?
            $compiler->setCssFile($this->getPath('assets/css/guinav.css'));
            // Kompilierung starten
            $compiler->compile();
            // Kopiere das kompilierte css in den öffentlichen assets-Ordner
            rex_file::copy($this->getPath('assets/css/guinav.css'), $this->getAssetsPath('guinav.css'));
        }
    }

$rv = rex_view::addCssFile( $this->getAssetsUrl('guinav.css') );

