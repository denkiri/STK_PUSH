<?php
/* Database config */
$db_host		= 'localhost';
$db_user		= 'denkiric_payment';
$db_pass		= 'denkiric_payment';
$db_database	= 'denkiric_pharmacy'; 

/* End config */

$db2 = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_user, $db_pass);
$db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>