<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>


<main class="main-content">


    <div class="page-header">

        <div>

            <h1>
                Edit Switch
            </h1>

            <p>
                Perbarui informasi perangkat switch.
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
            action="<?= BASE_URL ?>?page=switch&action=update"
        >
	    <?= csrf_field() ?>

            <input
                type="hidden"
                name="id"
                value="<?= (int) $switch['id'] ?>"
            >


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
                                $switch['name']
                            ) ?>"
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
                                $switch['hostname']
                                ?? ''
                            ) ?>"
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
                                $switch['ip_address']
                                ?? ''
                            ) ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <select name="status">

                            <option
                                value="online"
                                <?= $switch['status']
                                    === 'online'
                                    ? 'selected'
                                    : ''
                                ?>
                            >
                                Online
                            </option>

                            <option
                                value="offline"
                                <?= $switch['status']
                                    === 'offline'
                                    ? 'selected'
                                    : ''
                                ?>
                            >
                                Offline
                            </option>

                            <option
                                value="maintenance"
                                <?= $switch['status']
                                    === 'maintenance'
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
                                $switch['location']
                                ?? ''
                            ) ?>"
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
                                $switch['latitude']
                                ?? ''
                            ) ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label>
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            value="<?= htmlspecialchars(
                                $switch['longitude']
                                ?? ''
                            ) ?>"
                        >

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
                    ><?= htmlspecialchars(
                        $switch['description']
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
                    Update Switch
                </button>

            </div>

        </form>

    </section>

</main>


<?php require __DIR__ . '/../layouts/footer.php'; ?>