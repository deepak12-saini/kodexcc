<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
 * So you can use `$this` to reference the application class instance
 * if required.
 */
return function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    /*
     * Admin prefix: enables URL generation for ['prefix' => 'Admin', 'controller' => 'Users', ...]
     * e.g. /admin/users/login, /admin/users/forgot-password, /admin/users/dashboard
     */
    $routes->prefix('Admin', function (RouteBuilder $builder): void {
        $builder->setRouteClass(DashedRoute::class);
        // Legacy URL `admin/mailers/sendemail/{id}` → MailersController::sendSpecificationEmail (not AppController::sendemail).
        $builder->connect(
            '/mailers/sendemail/{id}',
            ['controller' => 'Mailers', 'action' => 'sendSpecificationEmail'],
            ['pass' => ['id'], 'id' => '\d+'],
        );
        // Legacy underscore URLs: DashedRoute expects `label-stocks`, not `label_stocks`.
        $builder->connect(
            '/label_stocks',
            ['controller' => 'LabelStocks', 'action' => 'index'],
        );
        $builder->connect(
            '/package_stocks',
            ['controller' => 'PackageStocks', 'action' => 'index'],
        );
        // Legacy: admin/purchases/resource_requirement → Admin\PurchasesController::resourceRequirement
        $builder->connect(
            '/purchases/resource_requirement',
            ['controller' => 'Purchases', 'action' => 'resourceRequirement'],
        );
        // Legacy underscore: admin/productions/batch_count_sheet → batchCountSheet
        $builder->connect(
            '/productions/batch_count_sheet',
            ['controller' => 'Productions', 'action' => 'batchCountSheet'],
        );
        $builder->fallbacks();
    });

    $routes->scope('/', function (RouteBuilder $builder): void {
        // Allow legacy DuroEzy PDF links like `/dompdf/1234-5678.pdf`.
        $builder->connect(
            '/dompdf/{file}',
            ['controller' => 'Dompdf', 'action' => 'download'],
            ['pass' => ['file'], 'file' => '.*\.pdf'],
        );

        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        // Home route is defined below for FrontsController::home().


        $builder->connect('/documents-brochures', ['controller' => 'Fronts', 'action' => 'documents_brochures']);
        $builder->connect('/product-msds', ['controller' => 'Fronts', 'action' => 'product_msds']);
        $builder->connect('/product-data-sheets', ['controller' => 'Fronts', 'action' => 'product_data_sheets']);
        $builder->connect('/kodewhite-wall-putty', ['controller' => 'Fronts', 'action' => 'wallputty']);
        $builder->connect('/otp', ['controller' => 'Rewards', 'action' => 'otp']);
        $builder->connect('/reward', ['controller' => 'Rewards', 'action' => 'login']);
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/register', ['controller' => 'Users', 'action' => 'register']);
        $builder->connect('/products/*', ['controller' => 'Products', 'action' => 'index']);
        $builder->connect('/', ['controller' => 'Fronts', 'action' => 'home']);
        $builder->connect('/service', ['controller' => 'Fronts', 'action' => 'service']);
        $builder->connect('/specification', ['controller' => 'Fronts', 'action' => 'specification']);
        $builder->connect('/specifiers', ['controller' => 'Fronts', 'action' => 'specification']);
        $builder->connect('/about-us', ['controller' => 'Fronts', 'action' => 'about']);
        $builder->connect('/faqs', ['controller' => 'Fronts', 'action' => 'faq']);
        $builder->connect('/feedback', ['controller' => 'Fronts', 'action' => 'feedback']);
        $builder->connect('/questionary', ['controller' => 'Fronts', 'action' => 'questionary']);
        $builder->connect('/tmclead', ['controller' => 'Fronts', 'action' => 'newlead']);
        $builder->connect('/admin', ['controller' => 'Users', 'action' => 'login', 'prefix' => 'Admin']);
        $builder->connect('/contact-us', ['controller' => 'Fronts', 'action' => 'contact']);
        $builder->connect('/technical-literature/*', ['controller' => 'Fronts', 'action' => 'technical_literature']);
        $builder->connect('/durooem', ['controller' => 'Fronts', 'action' => 'durooem']);
        $builder->connect('/durolab', ['controller' => 'Fronts', 'action' => 'durolab']);
        $builder->connect('/flake', ['controller' => 'Fronts', 'action' => 'flake']);
        $builder->connect('/video', ['controller' => 'Fronts', 'action' => 'video']);
        $builder->connect('/durotech-institute-of-waterproofing', ['controller' => 'Fronts', 'action' => 'institute_waterproofing']);

        // Legacy cron URL (CakePHP 2 used AppController\Sales::cronjob without /admin prefix).
        $builder->connect(
            '/sales/cronjob',
            ['prefix' => 'Admin', 'controller' => 'Sales', 'action' => 'cronjob'],
        );

        $builder->connect('/bathroom-floor-waterproofing', ['controller' => 'Fronts', 'action' => 'bathroom_floor_waterproofing']);
        $builder->connect('/bathroom-waterproofing', ['controller' => 'Fronts', 'action' => 'bathroom_waterproofing']);
        $builder->connect('/shower-waterproofing', ['controller' => 'Fronts', 'action' => 'shower_waterproofing']);

        // Legacy staff URLs: `label_stocks` (underscore) → LabelStocksController (no Admin prefix).
        $builder->connect(
            '/label_stocks',
            ['controller' => 'LabelStocks', 'action' => 'index'],
        );
        $builder->connect(
            '/label_stocks/{action}/*',
            ['controller' => 'LabelStocks'],
        );
        $builder->connect(
            '/package_stocks',
            ['controller' => 'PackageStocks', 'action' => 'index'],
        );
        $builder->connect(
            '/package_stocks/{action}/*',
            ['controller' => 'PackageStocks'],
        );
        // Legacy staff URL: purchases/resource_requirement (underscore) → resource_requirement action.
        $builder->connect(
            '/purchases/resource_requirement',
            ['controller' => 'Purchases', 'action' => 'resource_requirement'],
        );
        $builder->connect(
            '/purchases/resource-requirement',
            ['controller' => 'Purchases', 'action' => 'resource_requirement'],
        );
        // Legacy staff URL (underscore) → camelCase action for CakePHP 5.
        $builder->connect(
            '/staffs/change_password',
            ['controller' => 'Staffs', 'action' => 'changePassword'],
        );

        // Legacy staff Productions URLs (underscore paths).
        $builder->connect(
            '/productions/batch_register_add',
            ['controller' => 'Productions', 'action' => 'batchRegisterAdd'],
        );
        $builder->connect(
            '/productions/batch_register_edit/{id}',
            ['controller' => 'Productions', 'action' => 'batchRegisterEdit'],
            ['pass' => ['id'], 'id' => '\d+'],
        );
        $builder->connect(
            '/productions/batch_count_sheet',
            ['controller' => 'Productions', 'action' => 'batchCountSheet'],
        );
        $builder->connect(
            '/productions/batch_count_sheet_add',
            ['controller' => 'Productions', 'action' => 'batchCountSheetAdd'],
        );
        $builder->connect(
            '/productions/batch_count_sheet_edit/{id}',
            ['controller' => 'Productions', 'action' => 'batchCountSheetEdit'],
            ['pass' => ['id'], 'id' => '\d+'],
        );

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * It is NOT recommended to use fallback routes after your initial prototyping phase!
         * See https://book.cakephp.org/5/en/development/routing.html#fallbacks-method for more information
         */
        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
