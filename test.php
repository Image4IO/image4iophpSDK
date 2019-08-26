<?php
	include("imageio.class.php");
	
	$image = new Image4IO(apiKey,apiSecret);
	
	//Bağlantı kontrolü
	$connect = $image->connect();

	
 /*  $list = $image->listfolder('/');
   echo $list[content];

   $get = $image->get('/58b55411-8670-4161-bbc6-f09fa821eae8.jpg');
   echo $get[content];
   $create = $image->createfolder('dsc');*/

   if($_POST) {
	$a = $image->upload($_FILES['file'],'/');
	echo $a[content];
   }
 /*  $fetch = $image->fetch('url','hedef');
   
   $delete = $image->delete('3960be03-6105-4268-8988-8a4e3f85faf4.png');	

   $deletefolder = $image ->deletefolder('no2');

   $copy = $image->copys('kaynak','hedef');

   $move = $image->move('kaynak','taşıma');*/
  

	
?>

<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="file"/>
	<input type="text" name="path"/>
	<button>Gönder</button><br>

	
</form>

