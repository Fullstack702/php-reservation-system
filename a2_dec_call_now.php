<!DOCTYPE html>
<html lang="fr" ng-app="formApp">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>EPT - Login</title>
    <meta name="robots" content="noindex,nofollow" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,900,300,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic,700italic" rel="stylesheet" />
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" rel="Stylesheet" />
</head>

<body>
    <?php
    require "./php/config.php";
    $authenticated = false;
    if (isset($_POST['submit'])) {
        if ($_POST['password'] === 'hello.gov') {
            $authenticated = true;
            try {
                $connection = new PDO($dsn, $username, $password, $options);
                $sql = "SELECT * FROM users where contact_time like 'instant_call'";
                $statement = $connection->prepare($sql);
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
            }
        }
    }
    if (isset($_POST['id'])) {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "DELETE FROM users WHERE id=" . $_POST['id'];
        $statement = $connection->prepare($sql);
        $statement->execute();
        $sql = "SELECT * FROM users ";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>
    <?php
    if (!$authenticated) { ?>
        <div class="password-form">
            <form method="post">
                <div class="form-group">
                    <label for="location">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="col-12 text-right">
                    <input type="submit" name="submit" value="Submit">
                </div>
            </form>
        </div>
    <?php } ?>
    <?php
    if ($authenticated) { ?>
        <div id="sound"></div>
        <div class="result">
            <table style="width:100%">
                <thead>
                    <th>Date Lead</th>
                    <th>Horaire de contact</th>
                    <th>J'habite</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Adresse</th>
                    <th>CP</th>
                    <th>Ville</th>
                    <th>E-mail</th>
                    <th>Téléphone</th>
                    <th>Referer</th>
                    <th>IP</th>
                    <th>OS</th>
                    <th>Device</th>
                    <th></th>
                </thead>
                <?php
                if ($result && $statement->rowCount() > 0) { ?>
                    <tbody>
                        <?php foreach ($result as $row) { ?>
                            <tr>
                                <td><?php echo $row["date"]; ?></td>
                                <td><?php echo $row["contact_time"]; ?></td>
                                <td><?php echo $row["house_type"] == 1 ? 'Une maison' : 'Un appartement'; ?></td>
                                <td><?php echo $row["lastname"]; ?></td>
                                <td><?php echo $row["firstname"]; ?></td>
                                <td><?php echo $row["address"]; ?></td>
                                <td><?php echo $row["zip"]; ?></td>
                                <td><?php echo $row["city"]; ?> </td>
                                <td><?php echo $row["email"]; ?></td>
                                <td><?php echo $row["phone"]; ?></td>
                                <td><?php echo $row["referer"]; ?></td>
                                <td><?php echo $row["ip"]; ?></td>
                                <td><?php echo $row["os"]; ?> </td>
                                <td><?php echo $row["device"]; ?> </td>
                                <td><img class="btn-remove" src="./images/delete.png" width="32" data-toggle="modal" data-target="#modal" onclick="<?php echo "remove(" . $row["id"] . ")"; ?>"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php } else { ?>
                    No results founds
                <?php } ?>

            </table>
        </div>

    <?php   } ?>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-body">
                    <h6 class="text-center">Voulez-vous vraiment supprimer ce lead ?</h6>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="removeLead()">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var id = 0;

        function remove(index) {
            id = index;
        }

        function removeLead() {
            $.post(
                'result.php', {
                    id: id
                },
                function(data) {

                },
                'json'
            );
            setTimeout(() => {
                $('#myModal').modal('hide')
                location.reload();
            }, 1000);
        }

        var fetch = setInterval(getNewInstantCall, 10000);

        function getNewInstantCall() {
            var lastTime = $("table tr:last-child td:first-child").text();
            $.post("php/getNewInstantCall.php", {
                    time: lastTime
                },
                function(data, status, xhr) {
                    $('table tbody tr:last-child').after(data);
                    if (data != '') {
                        playSound('message')
                    }
                }, 'json');

        }


        function playSound(filename) {
            var oggSource = '<source src="' + filename + '.ogg" type="audio/ogg">';
            document.getElementById("sound").innerHTML = '<audio autoplay="autoplay">' + oggSource + '</audio>';
        }
    </script>
</body>

</html>