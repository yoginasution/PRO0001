<?php

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/
define('APP_NAME', 'Monitoring Network');
define('APP_VERSION', '1.0.0');

define('BASE_URL', '/monitoring_network/');



/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| CSRF Protection
|--------------------------------------------------------------------------
*/

/**
 * Membuat token CSRF.
 */
function csrf_token(): string
{
    if (
        empty($_SESSION['csrf_token'])
    ) {
        $_SESSION['csrf_token'] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}


/**
 * Menghasilkan input hidden CSRF.
 */
function csrf_field(): string
{
    return sprintf(
        '<input type="hidden" name="csrf_token" value="%s">',
        htmlspecialchars(
            csrf_token(),
            ENT_QUOTES,
            'UTF-8'
        )
    );
}


/**
 * Memvalidasi CSRF token.
 */
function verify_csrf_token(
    ?string $token
): bool {

    if (
        empty($token) ||
        empty($_SESSION['csrf_token'])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $token
    );
}


/**
 * Memastikan request mempunyai
 * CSRF token yang valid.
 */
function require_csrf(): void
{
    if (
        !verify_csrf_token(
            $_POST['csrf_token'] ?? null
        )
    ) {

        http_response_code(403);

        exit(
            '403 Forbidden - CSRF Token tidak valid.'
        );
    }
}

/**
 * Timezone
 */
date_default_timezone_set('Asia/Jakarta');

/**
 * Environment
 *
 * development
 * production
 */
define('APP_ENV', 'development');

/**
 * Error reporting
 */
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}