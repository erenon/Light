<?php
echo "
This is the index page of Project:Light. <br/>\n
This page should be accessible through localhost/light/ url, without public/ or index.php
";

var_dump($_GET);

echo getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
?>
