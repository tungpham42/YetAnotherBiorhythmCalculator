<?php
function template($filename): string {
	return realpath($_SERVER['DOCUMENT_ROOT']).'/templates/'.$filename.'.tpl.php';
}