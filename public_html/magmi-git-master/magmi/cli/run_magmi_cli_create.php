<?php
$output = shell_exec('/bin/bash  '.__DIR__.'/magmiCliCreate.sh > '.__DIR__.'/magmiCliCreate.log &');
echo "<pre>Magmi Cli is running</pre>";
?>
