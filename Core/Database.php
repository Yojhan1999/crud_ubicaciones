<?php

class Database {
  private string $host;
  private string $port;
  private string $dbname;
  private string $user;
  private string $password;

  public function __construct() {
    $this->host     = getenv('DB_HOST') ?: 'localhost';
    $this->port     = getenv('DB_PORT') ?: '3306';
    $this->dbname   = getenv('DB_NAME') ?: 'crud_ubicaciones';
    $this->user     = getenv('DB_USER') ?: 'root';
    $this->password = getenv('DB_PASSWORD') ?: '';
  }

  public function conectar(): PDO {
    $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";

    return new PDO(
      $dsn,
      $this->user,
      $this->password,
      [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
      ]
    );
  }
}
