<?php

class Pais extends Model {
  public function listar(): array {
    $sql = "
      SELECT
        p.pais_id,
        p.nombre
      FROM
        pais AS p
      ORDER BY
        p.nombre
    ";

    return $this->db->query($sql)->fetchAll();
  }

  public function obtener(int $pais_id): array|false {
    $sql = "
      SELECT
        p.pais_id,
        p.nombre
      FROM
        pais AS p
      WHERE
        p.pais_id = :pais_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':pais_id', $pais_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function guardar(string $nombre): int {
    $sql = "
      INSERT INTO pais (
        nombre
      ) VALUES (
        :nombre
      )
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->execute();

    return (int) $this->db->lastInsertId();
  }

  public function actualizar(int $pais_id, string $nombre): bool {
    $sql = "
      UPDATE
        pais
      SET
        nombre = :nombre
      WHERE
        pais_id = :pais_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':pais_id', $pais_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function eliminar(int $pais_id): bool {
    $sql = "
      DELETE FROM
        pais
      WHERE
        pais_id = :pais_id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':pais_id', $pais_id, PDO::PARAM_INT);

    return $stmt->execute();
  }
}
