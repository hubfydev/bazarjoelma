<?php
declare(strict_types=1);

// Configuração para hospedagem Hostinger.
// Para rodar localmente com Docker, altere DB_HOST para 'db' e use
// DB_NAME='bazar_mix_jo', DB_USER='bazar_user', DB_PASS='bazar_pass'.
const DB_HOST    = 'localhost';
const DB_NAME    = 'u921961937_bazar_mix_jo';
const DB_USER    = 'u921961937_bazar_user';
const DB_PASS    = 'bazar_pass@2026Jo';
const DB_CHARSET = 'utf8mb4';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die('Erro ao conectar ao banco de dados.');
    }

    return $pdo;
}
