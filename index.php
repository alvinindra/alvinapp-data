<html>

<head>
    <meta charset="UTF-8" />
    <title>Daftar Pelamar - Alvinapp Data</title>
    <meta name="description" content="My First Web App Azure" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/materialize.min.css">

</head>

<body>
    <div class="container">
        <h2>Daftar Pelamar</h2>
        <h6><b>*</b> Isikan nama dan alamat email pelamar, lalu klik <strong>Submit</strong> untuk daftar.</h6>
        <form method="post" action="index.php" enctype="multipart/form-data">
            Nama <input type="text" placeholder="Masukan Nama" name="name" id="name" /></br></br>
            Email <input type="text" placeholder="Masukan Email" name="email" id="email" /></br></br>
            Pekerjaan <input type="text" placeholder="Masukan Pekerjaan" name="job" id="job" /></br></br>
            <input class="btn" type="submit" name="submit" value="Submit" />
            <input class="btn" type="submit" name="load_data" value="Load Data" />
        </form>
        <?php
            
            try {
                $conn = new PDO("sqlsrv:server = tcp:alvinapp-server.database.windows.net,1433; Database = dbazuredata-alvinapp", "alvinapp", "@2019dicoding");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e) {
                echo "Failed: " . $e;
            }

            if (isset($_POST['submit'])) {
                try {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $job = $_POST['job'];
                    $date = date("Y-m-d");
                    // Insert data
                    $sql_insert = "INSERT INTO Applicants (name, email, job, date) 
                                VALUES (?,?,?,?)";
                    $stmt = $conn->prepare($sql_insert);
                    $stmt->bindValue(1, $name);
                    $stmt->bindValue(2, $email);
                    $stmt->bindValue(3, $job);
                    $stmt->bindValue(4, $date);
                    $stmt->execute();
                } catch(Exception $e) {
                    echo "Failed: " . $e;
                }

                echo "<h3>Anda telah terdaftar!</h3>";
            } else if (isset($_POST['load_data'])) {
                try {
                    $sql_select = "SELECT * FROM Applicants";
                    $stmt = $conn->query($sql_select);
                    $applicantsRow = $stmt->fetchAll(); 
                    if(count($applicantsRow) > 0) {
                        echo "<h2>Yang Sudah Terdaftar:</h2>";
                        echo "<table class='responsive-table'>";
                        echo "<tr><th>Nama</th>";
                        echo "<th>Email</th>";
                        echo "<th>Pekerjaan</th>";
                        echo "<th>Tanggal</th></tr>";
                        foreach($applicantsRow as $applicants) {
                            echo "<tr><td>".$applicants['name']."</td>";
                            echo "<td>".$applicants['email']."</td>";
                            echo "<td>".$applicants['job']."</td>";
                            echo "<td>".$applicants['date']."</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<h3>Tidak satupun pelamar yang terdaftar.</h3>";
                    }
                } catch(Exception $e) {
                    echo "Failed: " . $e;
                }
            }
        ?>
    </div>
	
</body>

</html>
