<aside
    class="sidebar"
    id="sidebar"
>

    <div class="sidebar-logo">

        <div class="logo-icon">
            🖧
        </div>

        <div class="logo-text">

            <strong>
                Network
            </strong>

            <span>
                Monitoring
            </span>

        </div>

    </div>


    <nav class="sidebar-menu">

        <div class="menu-section">

            <span class="menu-title">
                MAIN
            </span>

            <a
                href="<?= BASE_URL ?>?page=dashboard"
                class="menu-item active"
            >

                <span class="menu-icon">
                    📊
                </span>

                <span>
                    Dashboard
                </span>

            </a>

        </div>


        <div class="menu-section">

            <span class="menu-title">
                NETWORK
            </span>

<a
    href="<?= BASE_URL ?>?page=switch"
    class="menu-item
    <?= ($_GET['page'] ?? '') === 'switch'
        ? 'active'
        : '' ?>"
>

    <span class="menu-icon">
        🖧
    </span>

    <span>
        Switch
    </span>

</a>


<a
    href="<?= BASE_URL ?>?page=client"
    class="menu-item
    <?= ($_GET['page'] ?? '') === 'client'
        ? 'active'
        : '' ?>"
>

    <span class="menu-icon">
        💻
    </span>

    <span>
        Client
    </span>

</a>


<a
    href="<?= BASE_URL ?>?page=connection"
    class="menu-item <?= ($_GET['page'] ?? '') === 'connection' ? 'active' : '' ?>"
>
    <span class="menu-icon">🔗</span>

    <span>Koneksi</span>
</a>

            <a
                href="#"
                class="menu-item"
            >

                <span class="menu-icon">
                    🔗
                </span>

                <span>
                    Connections
                </span>

            </a>

        </div>


        <div class="menu-section">

            <span class="menu-title">
                MONITORING
            </span>

            <a
                href="#"
                class="menu-item"
            >

                <span class="menu-icon">
                    🗺️
                </span>

                <span>
                    Network Map
                </span>

            </a>


            <a
                href="#"
                class="menu-item"
            >

                <span class="menu-icon">
                    📡
                </span>

                <span>
                    Monitoring
                </span>

            </a>

        </div>


        <div class="menu-section">

            <span class="menu-title">
                SYSTEM
            </span>

            <a
                href="#"
                class="menu-item"
            >

                <span class="menu-icon">
                    ⚙️
                </span>

                <span>
                    Settings
                </span>

            </a>

        </div>

    </nav>


    <div class="sidebar-footer">

        <div class="version">
            Version <?= APP_VERSION ?>
        </div>

    </div>

</aside>