<?php
$title = "Users";
require_once "../inc/header.inc.php";







?>


<div class="d-flex flex-column m-5  table-responsive">
    <h2 class="text-center fw-bolder mb-5">Liste des utilisateurs</h2>
    <table class="table table-dark table-bordered mt-5">
        <thead>
            <tr>
                <th>ID</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Civility</th>
                <th>Address</th>
                <th>ZipCode</th>
                <th>City</th>
                <th>Country</th>
                <th>Role</th>
                <th>Supprimer</th>
                <th>Modifier</th>
            </tr>
        </thead>
        <tbody>

        <?php
        $users = allUsers();

        foreach($users as $user){

        
        ?>
            <tr>
                <td><?=$user['id_user']?></td>
                <td><?=ucfirst($user['firstName'])?></td>
                <td><?=ucfirst($user['lastName'])?></td>
                <td><?=$user['pseudo']?></td>
                <td><?=$user['email']?></td>
                <td><?=$user['phone']?></td>
                <td><?=$user['civility']?></td>
                <td><?=$user['address']?></td>
                <td><?=$user['zipCode']?></td>
                <td><?=ucfirst($user['city'])?></td>
                <td><?=ucfirst($user['country'])?></td>
                <td><?=$user['role']?></td>
                <td class="text-center">
                    <a href="dashboard.php?users_php&action=delete&id_user=<?= $user['id_user']?>"><i class="bi bi-trash3-fill text-danger"></i></a>
                </td>
                <td class="text-center">
                    <a href="dashboard.php?users_php&action=update&id_user=<?= $user['id_user']?>" class="btn btn-danger"><?=($user['role']) == 'ROLE_ADMIN' ? 'Rôle user' : 'Rôle admin'?>
                </td>
            </tr>

            <?php
            }
            ?>
        </tbody>

    </table>
</div>




<?php
require_once "../inc/footer.inc.php"
?>