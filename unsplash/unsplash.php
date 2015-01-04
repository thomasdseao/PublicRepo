<?php
	error_reporting(E_ALL); 
	ini_set("display_errors", 1); 
    require_once("simple_html_dom.php");
	$i = 1;
	$end = false;
	while($end == false){
		$html = file_get_html("https://unsplash.com/?page=".$i."");
		if(count($html->find('a')) >= 1){
			foreach($html->find('a') as $element){
				if (stripos($element->href, '/photos/') !== FALSE) {
					$redirect = get_headers("https://unsplash.com".$element->href, 1);
					$newLink = $redirect["Location"];
					//echo $newLink;
					$content= file_get_contents($newLink);
					$name = str_replace("/photos/", "", $element->href);
					$name = str_replace("/download", "", $name);
					$filename = "imgunsplash/".$name.".jpg";
					$somecontent = $content; 

					// Assurons nous que le fichier est accessible en écriture
					if(!file_exists($filename)){
						$file = fopen($filename, 'w+'); 
						fclose($file);
						if (is_writable($filename)) {

							// Dans notre exemple, nous ouvrons le fichier $filename en mode d'ajout
							// Le pointeur de fichier est placé à la fin du fichier
							// c'est là que $somecontent sera placé
							if (!$handle = fopen($filename, 'a')) {
								 echo "Impossible d'ouvrir le fichier ($filename)<br />";
								 exit;
							}

							// Ecrivons quelque chose dans notre fichier.
							if (fwrite($handle, $somecontent) === FALSE) {
								echo "Impossible d'écrire dans le fichier ($filename)<br />";
								exit;
							}

							echo "Ecriture réussi<br />";

							fclose($handle);

						} else {
							echo "Le fichier $filename n'est pas accessible en écriture.<br />";
						}
					}else{
						"L'image $filename existe déjà<br />";
					}
				}
			}
			$i++;
		}else{
			$end = true;
		}
	}
	echo $i."Page";