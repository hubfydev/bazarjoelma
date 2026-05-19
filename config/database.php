<?php
declare(strict_types=1);

// Docker: host 'db' é o nome do serviço MySQL no docker-compose.
// Hospedagem: altere para 'localhost' e as credenciais do seu painel.
const DB_HOST = 'db';
const DB_NAME = 'bazar_mix_jo';
const DB_USER = 'bazar_user';
const DB_PASS = 'bazar_pass';
const DB_CHARSET = 'utf8mb4';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
