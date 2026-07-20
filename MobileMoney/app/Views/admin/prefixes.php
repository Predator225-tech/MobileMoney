<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Préfixes</title>
    <style>
        /* CSS Intégré */
        body { font-family: sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h3 { color: #007bff; margin: 0; }
        
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin-bottom: 20px; background: #fafafa; }
        .form-row { display: flex; gap: 10px; }
        input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-weight: bold; display: inline-block; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-danger-outline { background: #fff; color: #dc3545; border: 1px solid #dc3545; }
        .btn-danger-outline:hover { background: #dc3545; color: #fff; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .badge { background: #17a2b8; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h3>Gestion des Préfixes</h3>
        <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-secondary">Retour au Dashboard</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <form action="<?= base_url('/admin/prefixes/store') ?>" method="post" class="form-row">
            <input type="text" name="code" placeholder="Exemple: 034" required>
            <button type="submit" class="btn btn-success">Ajouter</button>
        </form>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr><th>ID</th><th>Code Préfixe</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($prefixes)): ?>
                    <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><span class="badge"><?= esc($p['code']) ?></span></td>
                        <td>
                            <a href="<?= base_url('/admin/prefixes/delete/' . $p['id']) ?>" 
                               class="btn btn-danger-outline" 
                               onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center;">Aucun préfixe enregistré.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>