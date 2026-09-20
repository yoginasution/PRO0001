<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/SwitchController.php';
require_once __DIR__ . '/controllers/ClientController.php';
require_once __DIR__ . '/controllers/ConnectionController.php';


$page = $_GET['page'] ?? 'dashboard';

$action = $_GET['action'] ?? 'index';


switch ($page) {


    /*
    ==========================================
    DASHBOARD
    ==========================================
    */

    case 'dashboard':

        $controller =
            new DashboardController($pdo);

        $controller->index();

        break;


    /*
    ==========================================
    SWITCH
    ==========================================
    */

    case 'switch':

        $controller =
            new SwitchController($pdo);


        switch ($action) {

            case 'create':
                $controller->create();
                break;

            case 'store':

                if (
                    $_SERVER['REQUEST_METHOD']
                    !== 'POST'
                ) {
                    header(
                        'Location: ' .
                        BASE_URL .
                        '?page=switch'
                    );

                    exit;
                }

                $controller->store();

                break;

            case 'edit':
                $controller->edit();
                break;

            case 'update':

                if (
                    $_SERVER['REQUEST_METHOD']
                    !== 'POST'
                ) {
                    header(
                        'Location: ' .
                        BASE_URL .
                        '?page=switch'
                    );

                    exit;
                }

                $controller->update();

                break;

            
	case 'delete':

    			if (
        		$_SERVER['REQUEST_METHOD'] !== 'POST'
    			) {

        		http_response_code(405);

        		exit(
            			'405 Method Not Allowed'
        		);
    		}

    		$controller->delete();

    		break;

            default:
                $controller->index();
                break;
        }

        break;


    /*
    ==========================================
    CLIENT
    ==========================================
    */

    case 'client':

        $controller =
            new ClientController($pdo);


        switch ($action) {

            case 'create':

                $controller->create();

                break;


            case 'store':

                if (
                    $_SERVER['REQUEST_METHOD']
                    !== 'POST'
                ) {

                    header(
                        'Location: ' .
                        BASE_URL .
                        '?page=client'
                    );

                    exit;
                }

                $controller->store();

                break;


            case 'edit':

                $controller->edit();

                break;


            case 'update':

                if (
                    $_SERVER['REQUEST_METHOD']
                    !== 'POST'
                ) {

                    header(
                        'Location: ' .
                        BASE_URL .
                        '?page=client'
                    );

                    exit;
                }

                $controller->update();

                break;


            case 'delete':

    		if (
        		$_SERVER['REQUEST_METHOD'] !== 'POST'
    		) {

        		http_response_code(405);

        		exit(
            			'405 Method Not Allowed'
        		);
    		}

    		$controller->delete();

    		break;


            default:

                $controller->index();

                break;
        }

        break;

    /*
    ==========================================
    CONNECTION
    ==========================================
    */


case 'connection':

    $controller =
        new ConnectionController($pdo);

    switch ($action) {

        case 'create':

            $controller->create();

            break;


        case 'store':

            if (
                $_SERVER['REQUEST_METHOD']
                !== 'POST'
            ) {

                header(
                    'Location: '
                    . BASE_URL
                    . '?page=connection'
                );

                exit;
            }

            $controller->store();

            break;


        case 'edit':

            $controller->edit();

            break;


        case 'update':

            if (
                $_SERVER['REQUEST_METHOD']
                !== 'POST'
            ) {

                header(
                    'Location: '
                    . BASE_URL
                    . '?page=connection'
                );

                exit;
            }

            $controller->update();

            break;


      case 'delete':

    	if (
        	$_SERVER['REQUEST_METHOD'] !== 'POST'
    	) {

       	 	http_response_code(405);

        	exit(
            		'405 Method Not Allowed'
        	);
    	}

    		$controller->delete();

	    	break;


        default:

            $controller->index();

            break;
    }

    break;


    /*
    ==========================================
    404
    ==========================================
    */

    default:

        http_response_code(404);

        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';

        echo '<a href="' .
            BASE_URL .
            '">
            Kembali ke Dashboard
        </a>';

        break;
}