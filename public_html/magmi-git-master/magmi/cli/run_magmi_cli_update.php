<?php
$output = shell_exec('/bin/bash  '.__DIR__.'/magmiCliUpdate.sh > '.__DIR__.'/magmiCliUpdate.log &');
echo "<pre>Magmi Cli is running</pre>";
?>
