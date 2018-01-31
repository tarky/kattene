<?php
/*
Plugin Name: Kattene
Author: webfood
Plugin URI: https://github.com/tarky/kattene
Description: kattene.
Version: 0.1
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

  $main = array_filter($sites,
    function($site){
      return $site["main"];
    }
  )[0];

  $cnt = count($sites);

  if ($cnt == 1):
      $num_class = "__one";
  elseif ($cnt == 2):
      $num_class = "__two";
  elseif ($cnt == 3):
      $num_class = "__three";
  elseif ($cnt == 4):
      $num_class = "__four";
  endif;

  $str = '<div class="kattene">
    <div class="kattene__imgpart"><a target="_blank" href="'.$main["url"].'"><img src="'.$arr["image"].'"></a></div>
    <div class="kattene__infopart">
      <div class="kattene__title"><a target="_blank" href="'.$main["url"].'">'.$arr["title"].'</a></div>
      <div class="kattene__description">'.$arr["description"].'</div>
      <div class="kattene__btns '.$num_class.'">
      ';

  for( $i=0 ; $i<$cnt ; $i++ ){
     $str .= '<div><a class="btn __'.$sites[$i]["color"].'" target="_blank" href="'.$sites[$i]["url"].'">'.$sites[$i]["label"].'</a></div>
  ';
  }

  $str .= '</div>
    </div>
  </div>';
	$path = str_replace(home_url(),'',plugin_dir_url( __FILE__ ));
  $str .= '<script>
    var cb = function() {
			var links = document.querySelectorAll(\'link[href="'.$path.'style.css"]\');
			if(links.length == 0){
        var l = document.createElement("link"); l.rel = "stylesheet";
        l.href = "'.$path.'style.css";
        var h = document.getElementsByTagName("head")[0]; h.parentNode.insertBefore(l, h);
			}
    };
    var raf = requestAnimationFrame || mozRequestAnimationFrame ||
        webkitRequestAnimationFrame || msRequestAnimationFrame;
    if (raf) raf(cb);
    else window.addEventListener("load", cb);
  </script>';

  return $str;
}

add_shortcode( 'kattene', 'kattene_func' );
