<?php
/*
Plugin Name: Kattene
Author: webfood
Plugin URI: http://webfood.info/make-kattene/
Description: kattene.
Version: 1.1
Author URI: http://webfood.info/
Text Domain: kattene
Domain Path: /languages

License:
 Released under the GPL license
  http://www.gnu.org/copyleft/gpl.html

  Copyright 2018 (email : webfood.info@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function kattene_func( $args, $content ) {
  $content = str_replace("<br />", "", $content);
  $arr = json_decode($content,true);
  $sites = $arr["sites"];

  $main_tmp = array_filter($sites,
    function($site){
      return $site["main"];
    }
  );

  $main = array_pop($main_tmp);

  $cnt = count($sites);

  if ($cnt == 1):
      $num_class = "__one";
  elseif ($cnt == 2):
      $num_class = "__two";
  elseif ($cnt == 3):
      $num_class = "__three";
  elseif ($cnt == 4):
      $num_class = "__four";
  elseif ($cnt == 5):
      $num_class = "__five";
  endif;

  $str = '<div class="kattene">
    <div class="kattene__imgpart"><a target="_blank" href="'.$main["url"].'"><img src="'.$arr["image"].'"></a></div>
    <div class="kattene__infopart">
      <div class="kattene__title"><a target="_blank" href="'.$main["url"].'">'.$arr["title"].'</a></div>
      <div class="kattene__description">'.$arr["description"].'</div>
      <div class="kattene__btns '.$num_class.'">
      ';

  for( $i=0 ; $i<$cnt ; $i++ ){
     $str .= '<div><a class="kattene__btn __'.$sites[$i]["color"].'" target="_blank" href="'.$sites[$i]["url"].'">'.$sites[$i]["label"].'</a></div>
  ';
  }

  $str .= '</div>
    </div>
  </div>';
  $path = str_replace(home_url(),'',plugin_dir_url( __FILE__ ));
  wp_enqueue_style( 'kattene', $path . 'style.css');

  add_action( 'wp_footer', 'kattene_script' );
  do_action( 'kattene' );
  return $str;
}

add_shortcode( 'kattene', 'kattene_func' );

function kattene_script() {
  echo <<< EOM
<script>
 var loadDeferredStyles = function() {
   var addStylesNodes = document.getElementsByClassName("deferred-kattene");
   var replacement = document.createElement("div");

   addStylesNodes = Array.prototype.slice.call(addStylesNodes);
   addStylesNodes.forEach(function(elm) {
     replacement.innerHTML += elm.textContent;
     elm.parentElement.removeChild(elm);
   });
   document.body.appendChild(replacement);
 };
 var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
     window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
 if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
 else window.addEventListener('load', loadDeferredStyles);
</script>
EOM;
}

function add_noscript_to_kattene( $tag, $handle ) {
  if ( !in_array( $handle , [ 'kattene', 'kattene-custom' ], true ) ) {
      return $tag;
  }
  $tag = str_replace( '<link', '<noscript class="deferred-kattene"><link', $tag );
  return str_replace( '/>', '/></noscript>', $tag );
}
add_filter( 'style_loader_tag', 'add_noscript_to_kattene', 10, 2 );

remove_filter('the_content', 'wptexturize');
