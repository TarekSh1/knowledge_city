<?php
echo 'Whoops! Wrong link dude.';

file_put_contents('z3.txt', $_SERVER['REQUEST_URI']);