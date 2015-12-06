<?php

function activation_code($code) {
	$dbh = new PDO('sqlite:database.db');
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT code from users WHERE code=?");
	$stmt->execute(array($code));
	$duplicate = $stmt->fetchAll();
	return count($duplicate);
}
	
function recovery_code($code) {
	$dbh = new PDO('sqlite:database.db');
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->prepare("SELECT code from recovery WHERE code=?");
	$stmt->execute(array($code));
	$duplicate = $stmt->fetchAll();
	return count($duplicate);
}
 
?>