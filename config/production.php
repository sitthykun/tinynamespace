<?php
/**
 * Created by PhpStorm.
 * User: MasakoKh or Sitthykun LY
 * Date: 4/5/15
 * Time: 3:16 PM
 */



return [
    /* ----------------------------
     * multi databases
     *
     *
     * ----------------------------
     */
    'database'  => [
        /* ------------------------
         * mail database designs for only client usage
         * ------------------------
         */
        'mail'      => [
            'host'      => '127.0.0.1',
            'dbname'    => 'mailclient',
            'schema'    => 'schemaname',
            'username'  => 'mailuser',
            'password'  => '123456',
            'port'      => '5432'
        ],

        /* ------------------------
         * website's content design for viewing
         * ------------------------
         */
        'content'   => [
            'host'      => '127.0.0.1',
            'dbname'    => 'content',
            'schema'    => 'schemaname',
            'username'  => 'contentuser',
            'password'  => '123456',
            'port'      => '5432'
        ]
    ],

    /* ---------------------------
     * cached and temporary data
     *
     *
     * ---------------------------
     */
    'redis'     => [
        'port'          => '',
        'dbnumber'      => 6,
        'username'      => 'redismymomory',
        'password'      => 'helloredisworld'
    ],

    /* ----------------------------
     *
     *
     * ----------------------------
     */
    'url'       => [
        'home'          => 'procambodia.com'
    ],

    /* ----------------------------
     * Mail system
     *
     * ----------------------------
     */
    'mail'      => [
        'log'           => 'log@procambodia.com',
        'admin'         => 'admin@procambodia.com',
        'error'         => 'error@procambodia.com',
    ]
];