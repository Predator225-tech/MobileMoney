<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des opérations</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="container">
    <?php $operations = $operations ?? []; ?>

    <div class="header d-flex">
        <div>
            <h3>Gestion des types d'opérations</h3>
            <p>Crée, modifie et supprime les opérations utilisées par les clients et les barèmes.</p>
        </div>
        <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-outline">Retour Dashboard</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card" style="background: #fdfdfd; margin-bottom: 20px;">
        <h6>Créer une opération</h6>
        <form action="<?= base_url('/admin/operations/store') ?>" method="post" class="d-flex" style="gap: 10px; flex-wrap: wrap;">
            <input type="text" name="nom" placeholder="Ex: Dépôt" required style="flex: 1; min-width: 240px;">
            <button type="submit" class="btn btn-success">Ajouter</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operations as $operation): ?>
                    <tr>
                        <form action="<?= base_url('/admin/operations/update/' . $operation['id']) ?>" method="post">
                            <td><?= esc($operation['id']) ?></td>
                            <td><input type="text" name="nom" value="<?= esc($operation['nom']) ?>" required></td>
                            <td>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <a href="<?= base_url('/admin/operations/delete/' . $operation['id']) ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette opération ?');">Supprimer</a>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>