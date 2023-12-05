<?php
//function to show our result from the https action/result
function myPrint_r($value, $label = '') {
  if(DEBUG) :
    echo '<pre>';
    echo $label." ";
      print_r($value);
    echo '</pre>';
  endif;
}