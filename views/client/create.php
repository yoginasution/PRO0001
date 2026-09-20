<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="main-content">


    <div class="page-header">

        <div>

            <h1>
                Tambah Client
            </h1>

            <p>
                Tambahkan perangkat client baru.
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
            action="<?= BASE_URL ?>?page=client&action=store"
        >
	    <?= csrf_field() ?>

            <!-- INFORMASI CLIENT -->

            <div class="form-section">

                <h2>
                    Informasi Client
                </h2>


                <div class="form-grid">


                    <div class="form-group">

                        <label>
                            Nama Client
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars(
                                $data['name']
                                ?? ''
                            ) ?>"
                            placeholder="CLIENT-001"
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
                            placeholder="client-001"
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
                            placeholder="192.168.1.101"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            MAC Address
                        </label>

                        <input
                            type="text"
                            name="mac_address"
                            value="<?= htmlspecialchars(
                                $data['mac_address']
                                ?? ''
                            ) ?>"
                            placeholder="00:11:22:33:44:55"
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


                    <div class="form-group">

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
                            placeholder="Office 1"
                        >

                    </div>


                </div>

            </div>


            <!-- MAP -->

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


            <!-- DESCRIPTION -->

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
                        placeholder="Keterangan client..."
                    ><?= htmlspecialchars(
                        $data['description']
                        ?? ''
                    ) ?></textarea>

                </div>

            </div>


            <!-- BUTTON -->

            <div class="form-footer">


                <a
                    href="<?= BASE_URL ?>?page=client"
                    class="btn btn-secondary"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Client
                </button>


            </div>


        </form>

    </section>

</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>