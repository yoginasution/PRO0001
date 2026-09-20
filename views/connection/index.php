<?php

$pageTitle = 'Koneksi Switch & Client';

require __DIR__ . '/../layouts/header.php';

?>

<div class="connection-container">

    <div class="page-header">

        <div>

            <h1>Koneksi Network</h1>

            <p>
                Kelola koneksi antara Switch dan Client
            </p>

        </div>


        <div>

            <a
                href="<?= BASE_URL ?>?page=connection&action=create"
                class="btn btn-primary"
            >
                + Tambah Koneksi
            </a>

        </div>

    </div>


    <?php if (
        isset($_GET['success'])
    ): ?>

        <div class="alert alert-success">

            <?php if (
                $_GET['success'] === 'created'
            ): ?>

                Koneksi berhasil ditambahkan.

            <?php elseif (
                $_GET['success'] === 'updated'
            ): ?>

                Koneksi berhasil diperbarui.

            <?php elseif (
                $_GET['success'] === 'deleted'
            ): ?>

                Koneksi berhasil dihapus.

            <?php endif; ?>

        </div>

    <?php endif; ?>


    <div class="table-card">

        <div class="table-responsive">

            <table class="data-table connection-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Switch</th>

                        <th>Port Switch</th>

                        <th>Client</th>

                        <th>Port Client</th>

                        <th>Status</th>

                        <th>Keterangan</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (
                    empty($connections)
                ): ?>

                    <tr>

                        <td
                            colspan="8"
                            class="empty-state"
                        >

                            Belum ada data koneksi.

                        </td>

                    </tr>

                <?php else: ?>


                    <?php foreach (
                        $connections as $index => $connection
                    ): ?>

                        <tr>

                            <td>
                                <?= $index + 1 ?>
                            </td>


                            <td>

                                <div class="device-name">

                                    🔀

                                    <?= htmlspecialchars(
                                        $connection['switch_name']
                                    ) ?>

                                </div>

                                <?php if (
                                    !empty(
                                        $connection['switch_ip']
                                    )
                                ): ?>

                                    <small>
                                        <?= htmlspecialchars(
                                            $connection['switch_ip']
                                        ) ?>
                                    </small>

                                <?php endif; ?>

                            </td>


                            <td>

                                <span class="port-badge">

                                    <?= htmlspecialchars(
                                        $connection['switch_port']
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <div class="device-name">

                                    💻

                                    <?= htmlspecialchars(
                                        $connection['client_name']
                                    ) ?>

                                </div>

                                <?php if (
                                    !empty(
                                        $connection['client_ip']
                                    )
                                ): ?>

                                    <small>
                                        <?= htmlspecialchars(
                                            $connection['client_ip']
                                        ) ?>
                                    </small>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $connection['client_port']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?php

                                $status =
                                    $connection['status'];

                                $statusLabel =
                                    ucfirst($status);

                                ?>

                                <span
                                    class="connection-status status-<?= htmlspecialchars(
                                        $status
                                    ) ?>"
                                >

                                    <?= $statusLabel ?>

                                </span>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $connection['description']
                                    ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="<?= BASE_URL ?>?page=connection&action=edit&id=<?= (int) $connection['id'] ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>


	<form
    			method="POST"
    			action="<?= BASE_URL ?>?page=connection&action=delete"
    			class="inline-form"
    			onsubmit="return confirm('Hapus koneksi ini?')"
		>

    		<?= csrf_field() ?>

   		<input
        		type="hidden"
        		name="id"
        		value="<?= (int) $connection['id'] ?>"
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

    </div>

</div>


<?php require __DIR__ . '/../layouts/footer.php'; ?>