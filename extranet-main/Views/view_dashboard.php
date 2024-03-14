<style>
    .dashboard-link {
        display: flex;
        justify-content: space-around;
        text-align: center;
    }
</style>

<div class='dashboard__table'>
    <table>
        <thead>
            <tr>
                <?php foreach ($header as $title): ?>
                    <th>
                        <?= $title ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dashboard as $row): ?>
                <tr>
                    <?php foreach ($row as $key => $value): ?>
                        <?php if (!preg_match("/^id/", $key) && $key != "prenom"): ?>
                            <td>
                                <?php if ($key == "nom"): ?>
                                    <?= $value . " " . $row['prenom'] ?>
                                <?php else: ?>
                                    <?= "{$value}" ?>
                                <?php endif ?>
                            </td>
                        <?php endif ?>
                    <?php endforeach; ?>
                    <td class="dashboard-link">
                        <div>
                            <a href="<?= $bdlLink . "&id=" . $row['id_composante'] ?><?php if (isset($row['id_prestataire'])) {
                                      echo "&id-prestataire=" . $row['id_prestataire'];
                                  }
                                  ; ?>">
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