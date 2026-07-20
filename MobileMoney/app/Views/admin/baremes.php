<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Barèmes</title>
    <!-- Votre CSS local uniquement -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="container">
    <?php $baremes = $baremes ?? []; ?>
    <?php $types_operations = $types_operations ?? []; ?>
    <?php $selected_type = $selected_type ?? null; ?>

    <!-- En-tête -->
    <div class="header d-flex">
        <div>
            <h3>Gestion des barèmes de frais</h3>
            <p>Définissez vos intervalles tarifaires par type d'opération.</p>
        </div>
        <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-outline">Retour Dashboard</a>
    </div>

    <!-- Alertes -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <!-- Sélecteur d'opération -->
    <div class="card">
        <label>Sélectionner une opération</label>
        <div class="d-flex" style="gap: 10px; margin-top: 10px;">
            <?php foreach ($types_operations as $typeOperation): ?>
                <a href="<?= base_url('/admin/baremes/' . $typeOperation['id']) ?>" 
                   class="btn <?= ($selected_type && (int)$selected_type['id'] === (int)$typeOperation['id']) ? 'btn-primary' : 'btn-outline' ?>">
                    <?= esc($typeOperation['nom']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($selected_type): ?>
        <!-- Formulaire de création -->
        <div class="card" style="background: #fdfdfd;">
            <h6>Ajouter un intervalle pour <?= esc($selected_type['nom']) ?></h6>
            <form action="<?= base_url('/admin/baremes/store') ?>" method="post" class="d-flex">
                <input type="hidden" name="id_type_operation" value="<?= esc($selected_type['id']) ?>">
                <input type="number" step="0.01" name="montant_min" placeholder="Montant min" required>
                <input type="number" step="0.01" name="montant_max" placeholder="Montant max" required>
                <input type="number" step="0.01" name="frais_fixe" placeholder="Frais fixe (Ar)" required>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
        </div>

        <!-- Tableau des barèmes -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Montant Min</th>
                        <th>Montant Max</th>
                        <th>Frais Fixe (Ar)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($baremes)): ?>
                        <tr><td colspan="5" style="text-align:center;">Aucun intervalle pour cette opération.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($baremes as $bareme): ?>
                        <tr>
                            <form action="<?= base_url('/admin/baremes/update/' . $bareme['id']) ?>" method="post">
                                <td><?= esc($bareme['id']) ?></td>
                                <td><input type="number" name="montant_min" value="<?= esc($bareme['montant_min']) ?>" required></td>
                                <td><input type="number" name="montant_max" value="<?= esc($bareme['montant_max']) ?>" required></td>
                                <td><input type="number" name="frais_fixe" value="<?= esc($bareme['frais_fixe']) ?>" required></td>
                                <td>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <a href="<?= base_url('/admin/baremes/delete/' . $bareme['id']) ?>" class="btn btn-danger" onclick="return confirm('Supprimer cet intervalle ?');">Supprimer</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>