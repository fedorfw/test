<?php
	// подключаемся к базе
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "test_db";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// принимаем id
	if(isset($argv[1])) {
		$id = $argv[1];
	} else {
		die("Пожалуйста, укажите идентификатор.");
	}

	// запрос в базу
	$sql = "SELECT ID, EPC, EPL
        FROM testtable
        WHERE AR = (SELECT AR FROM testtable WHERE ID = $id)
        AND CR = (SELECT CR FROM testtable WHERE ID = $id)
        ORDER BY ID DESC
        LIMIT 20";

	$result = $conn->query($sql);

	//вывод результата
	if ($result->num_rows > 0) {
		$rows = $result->fetch_all(MYSQLI_ASSOC);
		foreach ($rows as $row) {
			echo "ID: " . $row["ID"] . ", EPC: " . $row["EPC"] . ", EPL: " . $row["EPL"] . "\n";
		}
	} else {
		echo "0 results";
	}

	$conn->close();
