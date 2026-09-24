<?php

class Departamento extends Model {
  public function listar(?int $pais_id = null): array {
    $sql = "
      SELECT
        d.departamento_id,
        d.pais_id,
        d.nombre,
        p.nombre AS pais
      FROM
        departamento AS d
      INNER JOIN pais AS p ON p.pais_id = d.pais_id
    ";

    if ($pais_id !== null) {
      $sql .= "
        WHERE
          d.pais_id = :pais_id
      ";
    }

    $sql .= "
      ORDER BY
        p.nombre,
        d.nombre
    ";

    $stmt = $this->db->prepare($sql);

    if ($pais_id !== null) {
      $stmt->bindValue(':pais_id', $pais_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function obtener(int $departamento_id): array|false {
    $sql = "
      SELECT
        d.departamento_id,
        d.pais_id,
        d.nombre
      FROM
        departamento AS d
      WHERE
        d.departamento_id = :departamento_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':departamento_id', $departamento_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function guardar(int $pais_id, string $nombre): int {
    $sql = "
      INSERT INTO departamento (
        pais_id,
        nombre
      ) VALUES (
        :pais_id,
        :nombre
      )
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':pais_id', $pais_id, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->execute();

    return (int) $this->db->lastInsertId();
  }

  public function actualizar(int $departamento_id, int $pais_id, string $nombre): bool {
    $sql = "
      UPDATE
        departamento
      SET
        pais_id = :pais_id,
        nombre  = :nombre
      WHERE
        departamento_id = :departamento_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':pais_id', $pais_id, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':departamento_id', $departamento_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function eliminar(int $departamento_id): bool {
    $sql = "
      DELETE FROM
        departamento
      WHERE
        departamento_id = :departamento_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':departamento_id', $departamento_id, PDO::PARAM_INT);

    return $stmt->execute();
  }
}
