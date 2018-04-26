<!doctype html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Fatal Application Errror</title>
 </head>
<body>
<h1>Fatal Application Errror</h1>
<h2><?php echo date(DATE_COOKIE); ?></h2>
<h3>Error Message:</h3>
<pre>
<?php print_r($error_message); ?> 
</pre>
</body>
</html>
<?php exit(1); ?> 
