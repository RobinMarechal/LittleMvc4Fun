<?php
foreach ($users as $u) 
{
?>

	<h1><?= $u->username ?> : </h1>
	<p>ID : <?= $u->id ?></p>
	<p>Username : <?= $u->username ?></p>
	<p>Name : <?= $u->name ?></p>
	<p>Email : <?= $u->email ?></p>

<?php
}
?>