<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="main-content">


    <div class="page-header">

        <div>

            <h1>
                Client
            </h1>

            <p>
                Kelola perangkat client jaringan.
            </p>

        </div>


        <a
            href="<?= BASE_URL ?>?page=client&action=create"
            class="btn btn-primary"
        >
            + Tambah Client
        </a>

    </div>


    <?php if (isset($_GET['success'])): ?>

        <div class="alert alert-success">

            <?php

            $message = match (
                $_GET['success']
            ) {

                'created' =>
                    'Client berhasil ditambahkan.',

                'updated' =>
                    'Client berhasil diperbarui.',

                'deleted' =>
                    'Client berhasil dihapus.',

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
                    Daftar Client
                </h2>

                <span>
                    Total <?= count($clients) ?> client
                </span>

            </div>

        </div>


        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Client</th>

                        <th>Hostname</th>

                        <th>IP Address</th>

                        <th>MAC Address</th>

                        <th>Location</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>


                <?php if (empty($clients)): ?>

                    <tr>

                        <td
                            colspan="8"
                            class="empty-state"
                        >

                            Belum ada data Client.

                        </td>

                    </tr>


                <?php else: ?>


                    <?php foreach (
                        $clients as $index => $client
                    ): ?>

                        <tr>


                            <td>
                                <?= $index + 1 ?>
                            </td>


                            <td>

                                <strong>
                                    <?= htmlspecialchars(
                                        $client['name']
                                    ) ?>
                                </strong>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $client['hostname']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $client['ip_address']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <code>
                                    <?= htmlspecialchars(
                                        $client['mac_address']
                                        ?? '-'
                                    ) ?>
                                </code>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $client['location']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?php

                                $statusClass =
                                    match ($client['status']) {

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
                                    class="
                                    status-badge
                                    <?= $statusClass ?>
                                    "
                                >

                                    <span>
                                        ●
                                    </span>

                                    <?= ucfirst(
                                        htmlspecialchars(
                                            $client['status']
                                        )
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <div
                                    class="action-buttons"
                                >

                                    <a
                                        href="<?= BASE_URL ?>?page=client&action=edit&id=<?= (int) $client['id'] ?>"
                                        class="btn-action edit"
                                    >
                                        Edit
                                    </a>

	<form
    		method="POST"
    		action="<?= BASE_URL ?>?page=client&action=delete"
    		class="inline-form"
    		onsubmit="return confirm('Hapus client ini?')"
	>

    		<?= csrf_field() ?>

    	<input
        	type="hidden"
        	name="id"
        	value="<?= (int) $client['id'] ?>"
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