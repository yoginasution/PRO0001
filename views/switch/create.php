<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="main-content">


    <div class="page-header">

        <div>

            <h1>
                Tambah Switch
            </h1>

            <p>
                Tambahkan perangkat switch baru.
            </p>

        </div>

    </div>


    <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

            <strong>
                Terdapat kesalahan:
            </strong>

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <section class="panel form-panel">

        <form
            method="POST"
            action="<?= BASE_URL ?>?page=switch&action=store"
        >
	    <?= csrf_field() ?>

            <div class="form-section">

                <h2>
                    Informasi Switch
                </h2>


                <div class="form-grid">


                    <div class="form-group">

                        <label>
                            Nama Switch
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars(
                                $data['name']
                                ?? ''
                            ) ?>"
                            placeholder="Contoh: SW-CORE-01"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Hostname
                        </label>

                        <input
                            type="text"
                            name="hostname"
                            value="<?= htmlspecialchars(
                                $data['hostname']
                                ?? ''
                            ) ?>"
                            placeholder="core-switch-01"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            IP Address
                        </label>

                        <input
                            type="text"
                            name="ip_address"
                            value="<?= htmlspecialchars(
                                $data['ip_address']
                                ?? ''
                            ) ?>"
                            placeholder="192.168.1.1"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <select name="status">

                            <option
                                value="online"
                                <?= (
                                    ($data['status']
                                    ?? 'offline')
                                    === 'online'
                                )
                                ? 'selected'
                                : ''
                                ?>
                            >
                                Online
                            </option>

                            <option
                                value="offline"
                                <?= (
                                    ($data['status']
                                    ?? '')
                                    === 'offline'
                                )
                                ? 'selected'
                                : ''
                                ?>
                            >
                                Offline
                            </option>

                            <option
                                value="maintenance"
                                <?= (
                                    ($data['status']
                                    ?? '')
                                    === 'maintenance'
                                )
                                ? 'selected'
                                : ''
                                ?>
                            >
                                Maintenance
                            </option>

                        </select>

                    </div>


                    <div class="form-group full">

                        <label>
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="<?= htmlspecialchars(
                                $data['location']
                                ?? ''
                            ) ?>"
                            placeholder="Server Room"
                        >

                    </div>


                </div>

            </div>


            <div class="form-section">

                <h2>
                    Koordinat Maps
                </h2>


                <div class="form-grid">


                    <div class="form-group">

                        <label>
                            Latitude
                        </label>

                        <input
                            type="text"
                            name="latitude"
                            value="<?= htmlspecialchars(
                                $data['latitude']
                                ?? ''
                            ) ?>"
                            placeholder="-6.2000000"
                        >

                        <small>
                            Contoh: -6.2000000
                        </small>

                    </div>


                    <div class="form-group">

                        <label>
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            value="<?= htmlspecialchars(
                                $data['longitude']
                                ?? ''
                            ) ?>"
                            placeholder="106.8166667"
                        >

                        <small>
                            Contoh: 106.8166667
                        </small>

                    </div>


                </div>

            </div>


            <div class="form-section">

                <h2>
                    Keterangan
                </h2>


                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Keterangan switch..."
                    ><?= htmlspecialchars(
                        $data['description']
                        ?? ''
                    ) ?></textarea>

                </div>

            </div>


            <div class="form-footer">

                <a
                    href="<?= BASE_URL ?>?page=switch"
                    class="btn btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Switch
                </button>

            </div>


        </form>

    </section>

</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>