<?php

/**
 * Telephone Scheme Plugin
 */

if(!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_telephone extends DokuWiki_Syntax_Plugin {

    function getType() { return 'substition'; }
    function getPType() { return 'normal'; }
    function getSort() { return 1; }

   function connectTo($mode) {

        $this->Lexer->addSpecialPattern('<<tel:.+?>>', $mode, 'plugin_telephone');
    }

    function handle($match, $state, $pos, Doku_Handler $handler) {

        $match = substr($match, 6, -2);
        list($number, $flags) = explode('&', $match, 2);

        $match = array($number, explode('&', $flags));
        return $match;
    }

    function render($mode, Doku_Renderer $renderer, $data) {

        if ($mode == 'xhtml') {

            list($number, $flags) = $data;
            if ( !is_Array($flags) ) {

                $flags = array($flags);
            }

            $icon = true;

            foreach($flags as $flag) {

                switch ($flag) {
                    case 'noicon':
                        $icon = false;
                        break;
                }
            }

            $raw = $number;

            $number = preg_replace('/[^+0-9]/', '', $number); // strip all but numbers and the plus sign

            $class = ($icon ? "telephone icon" : "telephone");

            $renderer->doc .= '<a href="tel:' . $number . '" class="' . $class . '" title="tel:' . $number . '">';
            $renderer->doc .= $raw;
            $renderer->doc .= '</a>';

            return true;
        }

        return false;
    }
}