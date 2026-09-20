<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="main-content">

    <div class="page-header">

        <div>

            <h1>
                Switch
            </h1>

            <p>
                Kelola perangkat switch jaringan.
            </p>

        </div>


        <a
            href="<?= BASE_URL ?>?page=switch&action=create"
            class="btn btn-primary"
        >
            + Tambah Switch
        </a>

    </div>


    <?php if (isset($_GET['success'])): ?>

        <div class="alert alert-success">

            <?php

            $message = match (
                $_GET['success']
            ) {

                'created' =>
                    'Switch berhasil ditambahkan.',

                'updated' =>
                    'Switch berhasil diperbarui.',

                'deleted' =>
                    'Switch berhasil dihapus.',

                default =>
                    'Operasi berhasil.'
            };

            ?>

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>


    <section class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Daftar Switch
                </h2>

                <span>
                    Total <?= count($switches) ?> perangkat
                </span>

            </div>

        </div>


        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nama</th>

                        <th>Hostname</th>

                        <th>IP Address</th>

                        <th>Location</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (empty($switches)): ?>

                    <tr>

                        <td
                            colspan="7"
                            class="empty-state"
                        >

                            Belum ada data Switch.

                        </td>

                    </tr>

                <?php else: ?>


                    <?php foreach (
                        $switches as $index => $switch
                    ): ?>

                        <tr>

                            <td>
                                <?= $index + 1 ?>
                            </td>


                            <td>

                                <strong>
                                    <?= htmlspecialchars(
                                        $switch['name']
                                    ) ?>
                                </strong>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $switch['hostname']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $switch['ip_address']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $switch['location']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?php

                                $statusClass =
                                    match ($switch['status']) {

                                        'online' =>
                                            'status-online',

                                        'offline' =>
                                            'status-offline',

                                        'maintenance' =>
                                            'status-maintenance',

                                        default =>
                                            ''
                                    };

                                ?>

                                <span
                                    class="status-badge
                                    <?= $statusClass ?>"
                                >

                                    <span>
                                        ●
                                    </span>

                                    <?= ucfirst(
                                        htmlspecialchars(
                                            $switch['status']
                                        )
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="<?= BASE_URL ?>?page=switch&action=edit&id=<?= (int) $switch['id'] ?>"
                                        class="btn-action edit"
                                    >
                                        Edit
                                    </a>


	<form
    			method="POST"
    			action="<?= BASE_URL ?>?page=switch&action=delete"
    			class="inline-form"
    			onsubmit="return confirm('Hapus switch ini?')"
		>

    		<?= csrf_field() ?>

    		<input
        		type="hidden"
        		name="id"
        		value="<?= (int) $switch['id'] ?>"
    		>

    		<button
        		type="submit"
        		class="btn btn-sm btn-danger"
    		>
        		Hapus
    		</button>

	</form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>


                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>