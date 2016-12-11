<?php
$doc = new DOMDocument();
$doc->load( '../db/feed.xml' );

#echo "Loading....\n";
 
$entries = $doc->getElementsByTagName( "entry" );

$date = date_create();


$doc->formatOutput = true;
$feed = $doc->getElementsByTagName( "feed" );
 
$r = $doc->createElement( "entry" );
$feed->item(0)->appendChild( $r );

$id = $doc->createElement( "id" );
$id->appendChild($doc->createTextNode( date_timestamp_get($date) ));
$r->appendChild( $id );
 
$title = $doc->createElement( "title" );
$title->appendChild($doc->createTextNode( $_POST['title'] ));
$r->appendChild( $title );
 
$text = $doc->createElement( "text" );
$text->appendChild($doc->createTextNode( $_POST['text'] ));
$r->appendChild( $text );
 

$myfile = fopen("../db/feed.xml", "w") or die("Unable to open file!");
fwrite($myfile, $doc->saveXML());
fclose($myfile);

echo "Success";

?>
