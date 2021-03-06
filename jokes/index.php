<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/magicquotes.inc.php';

if (isset($_GET['addjoke'])) {

	include 'form.html.php';
	exit();
}

if (isset($_GET['deletejoke'])) {

	include $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php';
	
	try {

		$sql = 'DELETE FROM joke 
				WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e) {

		$error = 'Error deleting joke: ' . $e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
		exit();
	}
	
	header('Location: .');
	exit();
}

if (isset($_POST['joketext'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php';
	
	try {
	
		$sql = 'INSERT INTO joke 
				SET joketext = :joketext, 
					jokedate = CURDATE()';
		$s = $pdo->prepare($sql);
		$s->bindValue(':joketext', $_POST['joketext']);
		$s->execute();
	}
	catch (PDOException $e) {
	
		$error = 'Error adding submitted joke: ' . $e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
		exit();
	}
	
	header('Location: .');
	exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/include/db.inc.php';

try {

	$sql = 'SELECT id, joketext 
			FROM joke';
	$result = $pdo->query($sql);
}
catch (PDOException $e) {

	$error = 'Error fetching jokes: ' . $e->getMessage();
	include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
	exit();
}

$jokes = array();
foreach ($result as $row) {

	$jokes[] = array('id' => $row['id'], 'text' => $row['joketext']);
}

include 'jokes.html.php';

?>
