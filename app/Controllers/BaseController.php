<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use \IonAuth\Libraries\IonAuth;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function _render_page($view, $data = NULL, $returnhtml = FALSE)
    {
        // Logika autentikasi
        $this->ion_auth = new IonAuth();
        $this->session = \Config\Services::session();

        if (!$this->ion_auth->loggedIn()) {
            return \Config\Services::response()->setStatusCode(401)->setBody(view('template/error_401'));
        }
        if ($this->ion_auth->inGroup('admin')) {
            $data['role'] = 'admin';
        } else {
            $data['role'] = 'user';
        }
        echo view($view, $data);
        // Menginisialisasi data view
        // $viewdata = (empty($data)) ? $this->data : $data;
        // $viewdata['group'] = $this->session->get('group');
        // $viewdata['permission'] = $this->session->get('permission');
        // $viewdata['permission'] = $viewdata['permission'] == null ? [] : $viewdata['permission'];

        // // Load header, sidemenu, dan breadcrumb
        // echo view('template/default', $viewdata);

        // // Load view atau views
        // if (is_array($view)) {
        //     $view_html = "";
        //     foreach ($view as $v) {
        //         $view_html .= view($v, $viewdata);
        //     }
        // } else {
        //     $view_html = view($view, $viewdata);
        // }

        // // Load footer
        // // echo view('template/utilitas/footer', $viewdata, $returnhtml);

        // // Mengembalikan hasil jika $returnhtml bernilai TRUE
        // if ($returnhtml) {
        //     return $view_html;
        // }
    }
}
