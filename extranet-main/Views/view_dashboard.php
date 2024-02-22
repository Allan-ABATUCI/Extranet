<!-- Vue permettant de consulter son dashboard comportant les missions, prestataire assigné, composante et consulter son bon de livraison -->

<div class='dashboard__table'>
    <table>
        <thead>
            <tr>
                <?php foreach ($header as $title): ?>
                    <th><?= $title ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dashboard as $row): ?>
                <tr>
                    <?php foreach ($row as $cle => $value): ?>
                        <?php if ($cle == 'prenom' or $cle == 'nom'): ?>
                            <td><?= $row['prenom'] . ' ' . $row['nom'] ?></td>
                            <?php break; ?>
                        <?php endif; endforeach; ?>
                        <?php  ?>
                    <td style="display: flex; justify-content: space-around;">
                        <div style="text-align: center;">
                        <a href="?controller=<?=$_SESSION['role']?>&action=liste_bdl" id="<?=$row['id_composante']?>">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <p>Consulter</p>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="Content\js\prestataire.js"></script>