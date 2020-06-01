<?php
$menu_arr = array (
   0 => array(
      "data" => "tasks",
      "href" => "taches.php",
      "value" => "tâches"
   ),
   1 => array(
      "data" => "folders",
      "href" => "classeurs.php",
      "value" => "classeurs"
   ),
   2 => array(
      "data" => "titles",
      "href" => "titres.php",
      "value" => "titres"
   )
);
$menu_merged = array_merge($menu_arr, $menu_arr_extra);


foreach($menu_merged as $item) {
   $data = $item["data"];
   $href = $item["href"];
   $value = ucfirst($item["value"]); // Capitalize the string
   $pageString = pathinfo($href, PATHINFO_FILENAME); // Strips extension off a string to return filename
   $isActive = $page == $pageString ? ' active' : '';
   echo '<li><a class="liens'. $isActive .'" data-menu"'. $data .'" href="'. $href .'">'. $value .'</a></li>';
}

?>