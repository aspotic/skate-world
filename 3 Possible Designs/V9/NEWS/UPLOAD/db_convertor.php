<?php

function my_array_shift(&$array){
  reset($array);
  $temp = each($array);
  unset($array[$temp['key']]);
  return $temp['value'];
}

  @mkdir('./news' ,0777);
  $file = file('./news.db');
  //my_array_shift( $file );
  $i = Count( $file ) - 1;
  foreach( $file as $value ){
    list($newsm,$fullnewsm,$userm,$subm,$emailm,$iconm,$datem,$numcom,$rand) = explode( '|<|', $value );
    $data  = "<?php die('You cannot access this file'); ?>\n";
    $newsm = str_replace("&br;", " &br;", $newsm);
    $fullnewsm = str_replace("&br;", " &br;", $fullnewsm);
    $data .= "$newsm|<|$fullnewsm|<|$userm|<|$subm|<|$emailm|<|$iconm|<|$datem|<|$numcom|<|\n";
    echo "File created: ".($i+1)."<br>\n";
    $file2 = file('./comments.db.php');
    my_array_shift( $file2 );
    $data2 = '';
    foreach( $file2 as $value2 ){
      list($ip,$post,$name,$email,$datum,$unique,$num) = explode( '|<|', $value2 );
      if( $num == $rand ){
        $post = str_replace("&br;", " &br;", $post);
        $data2 .= "$ip|<|$post|<|$name|<|$email|<|$datum|<|$unique|<|\n";
      }
    }
    echo "File created: ".($i+1)."<br>\n";
    $fp = fopen( "./news/news.".($i+1).".php", "w");
    fwrite( $fp, $data.$data2);
    fclose( $fp );
    @chmod( "./news/news.".($i+1).".php", 0666);
    $i--;
  }
  
  $file = file('./news.db');
  //my_array_shift( $file );
  $i = Count( $file );
  $data = "<?php die('You cannot access this file'); ?>\n";
  foreach( $file as $value ){
    list($newsm,$fullnewsm,$userm,$subm,$emailm,$iconm,$datem,$numcom,$rand) = explode( '|<|', $value );
    $data .= $i."|<|".$datem."|<|".$userm."|<|".$subm."|<|\n";
    $i--;
  }
  $fp = fopen( "./news/toc.php", "w");
  fwrite( $fp, $data);
  fclose( $fp );
  echo $data;
 /* $file = file('./news/toc');
  if( $file[0] != '' ){
    list($tmp1, $tmp2) = explode('|<|', $file[0]);
    echo $tmp1;
    $break = "\n";
  } else $break = "";   */
?>
