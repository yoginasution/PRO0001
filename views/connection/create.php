<?php

$pageTitle = 'Tambah Koneksi';

require __DIR__ . '/../layouts/header.php';

?>

<div class="connection-container">

    <div class="page-header">

        <div>

            <h1>Tambah Koneksi</h1>

            <p>
                Hubungkan Switch dengan Client
            </p>

        </div>

    </div>


    <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <div class="form-card">

        <form
            method="POST"
            action="<?= BASE_URL ?>?page=connection&action=store"
        >
	    <?= csrf_field() ?>

            <div class="form-grid">


                <!-- SWITCH -->

                <div class="form-group">

                    <label for="switch_id">

                        Switch
                        <span class="required">*</span>

                    </label>

                    <select
                        id="switch_id"
                        name="switch_id"
                        required
                    >

                        <option value="">
                            -- Pilih Switch --
                        </option>


                        <?php foreach (
                            $switches as $switch
                        ): ?>

                            <option
                                value="<?= (int) $switch['id'] ?>"
                                <?= (
                                    (int) $formData['switch_id']
                                    === (int) $switch['id']
                                )
                                    ? 'selected'
                                    : ''
                                ?>
                            >

                                <?= htmlspecialchars(
                                    $switch['name']
                                ) ?>

                                <?php if (
                                    !empty(
                                        $switch['ip_address']
                                    )
                                ): ?>

                                    -
                                    <?= htmlspecialchars(
                                        $switch['ip_address']
                                    ) ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- CLIENT -->

                <div class="form-group">

                    <label for="client_id">

                        Client
                        <span class="required">*</span>

                    </label>

                    <select
                        id="client_id"
                        name="client_id"
                        required
                    >

                        <option value="">
                            -- Pilih Client --
                        </option>


                        <?php foreach (
                            $clients as $client
                        ): ?>

                            <option
                                value="<?= (int) $client['id'] ?>"
                                <?= (
                                    (int) $formData['client_id']
                                    === (int) $client['id']
                                )
                                    ? 'selected'
                                    : ''
                                ?>
                            >

                                <?= htmlspecialchars(
                                    $client['name']
                                ) ?>

                                <?php if (
                                    !empty(
                                        $client['ip_address']
                                    )
                                ): ?>

                                    -
                                    <?= htmlspecialchars(
                                        $client['ip_address']
                                    ) ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- SWITCH PORT -->

                <div class="form-group">

                    <label for="switch_port">

                        Port Switch
                        <span class="required">*</span>

                    </label>

                    <input
                        type="text"
                        id="switch_port"
                        name="switch_port"
                        value="<?= htmlspecialchars(
                            $formData['switch_port']
                        ) ?>"
                        placeholder="Contoh: Gi0/1"
                        required
                    >

                    <small>
                        Contoh: Gi0/1, Gi0/24, Fa0/1
                    </small>

                </div>


                <!-- CLIENT PORT -->

                <div class="form-group">

                    <label for="client_port">
                        Port / Interface Client
                    </label>

                    <input
                        type="text"
                        id="client_port"
                        name="client_port"
                        value="<?= htmlspecialchars(
                            $formData['client_port']
                            ?? ''
                        ) ?>"
                        placeholder="Contoh: eth0"
                    >

                </div>


                <!-- STATUS -->

                <div class="form-group">

                    <label for="status">

                        Status Koneksi

                    </label>

                    <select
                        id="status"
                        name="status"
                    >

                        <option
                            value="up"
                            <?= $formData['status'] === 'up'
                                ? 'selected'
                                : '' ?>
                        >
                            Up
                        </option>

                        <option
                            value="down"
                            <?= $formData['status'] === 'down'
                                ? 'selected'
                                : '' ?>
                        >
                            Down
                        </option>

                        <option
                            value="maintenance"
                            <?= $formData['status'] === 'maintenance'
                                ? 'selected'
                                : '' ?>
                        >
                            Maintenance
                        </option>

                    </select>

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group full">

                    <label for="description">
                        Keterangan
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Keterangan koneksi..."
                    ><?= htmlspecialchars(
                        $formData['description']
                        ?? ''
                    ) ?></textarea>

                </div>


            </div>


            <div class="form-actions">

                <a
                    href="<?= BASE_URL ?>?page=connection"
                    class="btn btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Koneksi
                </button>

            </div>

        </form>

    </div>

</div>


<?php require __DIR__ . '/../layouts/footer.php'; ?>