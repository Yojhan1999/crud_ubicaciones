<?php

class Ciudad extends Model {
  public function listar(?int $departamento_id = null): array {
    $sql = "
      SELECT
        c.ciudad_id,
        c.departamento_id,
        c.nombre,
        d.nombre AS departamento,
        p.nombre AS pais
      FROM
        ciudad AS c
      INNER JOIN departamento AS d ON d.departamento_id = c.departamento_id
      INNER JOIN pais AS p ON p.pais_id = d.pais_id
    ";

    if ($departamento_id !== null) {
      $sql .= "
        WHERE
          c.departamento_id = :departamento_id
      ";
    }

    $sql .= "
      ORDER BY
        p.nombre,
        d.nombre,
        c.nombre
    ";

    $stmt = $this->db->prepare($sql);

    if ($departamento_id !== null) {
      $stmt->bindValue(':departamento_id', $departamento_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function obtener(int $ciudad_id): array|false {
    $sql = "
      SELECT
        c.ciudad_id,
        c.departamento_id,
        d.pais_id,
        c.nombre
      FROM
        ciudad AS c
      INNER JOIN departamento AS d ON d.departamento_id = c.departamento_id
      WHERE
        c.ciudad_id = :ciudad_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':ciudad_id', $ciudad_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function guardar(int $departamento_id, string $nombre): int {
    $sql = "
      INSERT INTO ciudad (
        departamento_id,
        nombre
      ) VALUES (
        :departamento_id,
        :nombre
      )
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':departamento_id', $departamento_id, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->execute();

    return (int) $this->db->lastInsertId();
  }

  public function actualizar(int $ciudad_id, int $departamento_id, string $nombre): bool {
    $sql = "
      UPDATE
        ciudad
      SET
        departamento_id = :departamento_id,
        nombre           = :nombre
      WHERE
        ciudad_id = :ciudad_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':departamento_id', $departamento_id, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':ciudad_id', $ciudad_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function eliminar(int $ciudad_id): bool {
    $sql = "
      DELETE FROM
        ciudad
      WHERE
        ciudad_id = :ciudad_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':ciudad_id', $ciudad_id, PDO::PARAM_INT);

    return $stmt->execute();
  }
}
