<?php 

namespace QueryParty\Database;

use PDO;

class QueryBuilder
{
    /**
     * Representa a instância da conexão com o banco de dados.
     *
     * @var PDO
     */
    private PDO $conn;

    /**
     * Representa o nome da tabela a ser consultada.
     *
     * @var string
     */
    private string $table;

    /**
     * Representa os campos a serem selecionados na consulta SQL.
     *
     * @var array
     */
    private array $fields = [];

    /**
     * Representa as cláusulas JOIN da consulta SQL.
     *
     * @var array
     */
    private array $joins = [];

    /**
     * Representa as cláusulas WHERE da consulta SQL.
     *
     * @var array
     */
    private array $where = [];

    /**
     * Representa as cláusulas GROUP BY da consulta SQL.
     *
     * @var array
     */
    private array $groupBy = [];

    /**
     * Representa as cláusulas HAVING da consulta SQL.
     *
     * @var array
     */
    private array $having = [];

    /**
     * Representa as cláusulas ORDER BY da consulta SQL.
     *
     * @var array
     */
    private array $orderBy = [];

    /**
     * Representa a cláusula LIMIT da consulta SQL.
     *
     * @var string|null
     */
    private ?string $limit = null;

    /**
     * Representa os parâmetros da consulta SQL.
     *
     * @var array
     */
    private array $params = [];

    /**
     * Representa a consulta SQL a ser executada.
     *
     * @var string|null
     */
    private ?string $query = null;

    /**
     * Cria uma nova instância do QueryBuilder.
     *
     * @param string $table O nome da tabela a ser consultada.
     */
    public function __construct(string $table)
    {
        $this->conn = Connection::getInstance();
        $this->table = $table;
    }

    /**
     * Define os campos a serem selecionados na consulta SQL.
     *
     * @param array|string $fields Os campos a serem selecionados.
     * @return self
     */
    public function select(array|string $fields = '*'): self
    {
        if (is_array($fields)) {
            $this->fields = $fields;
        } else {
            $this->fields = [$fields];
        }

        return $this;
    }

    /**
     * Adiciona uma cláusula JOIN na consulta SQL.
     *
     * @param string $table A tabela a ser unida.
     * @param string $condition A condição de união.
     * @param string $type O tipo de união (por padrão, vazio).
     * @return self
     */
    public function join(string $table, string $condition, string $type = ''): self
    {
        $this->joins[] = "$type JOIN $table ON $condition";

        return $this;
    }

    /**
     * Adiciona uma cláusula WHERE na consulta SQL.
     *
     * @param string $field O campo da cláusula WHERE.
     * @param string $operator O operador da cláusula WHERE.
     * @param mixed $value O valor a ser comparado na cláusula WHERE.
     * @return self
     */
    public function where(string $field, string $operator, $value): self
    {
        $this->where[] = "$field $operator ?";
        $this->params[] = $value;

        return $this;
    }

    /**
     * Adiciona uma cláusula GROUP BY na consulta SQL.
     *
     * @param string $field O campo para agrupar.
     * @return self
     */
    public function groupBy(string $field): self
    {
        $this->groupBy[] = $field;

        return $this;
    }

    /**
     * Adiciona uma cláusula HAVING na consulta SQL.
     *
     * @param string $field O campo da cláusula HAVING.
     * @param string $operator O operador da cláusula HAVING.
     * @param mixed $value O valor a ser comparado na cláusula HAVING.
     * @return self
     */
    public function having(string $field, string $operator, $value): self
    {
        $this->having[] = "$field $operator ?";
        $this->params[] = $value;

        return $this;
    }

    /**
     * Adiciona uma cláusula ORDER BY na consulta SQL.
     *
     * @param string $field O campo para ordenação.
     * @param string $direction A direção da ordenação (por padrão, 'ASC').
     * @return self
     */
    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "$field $direction";

        return $this;
    }

    /**
     * Define a cláusula LIMIT da consulta SQL.
     *
     * @param int $limit O número máximo de registros a retornar.
     * @param int $offset O deslocamento a partir do qual retornar registros (por padrão, 0).
     * @return self
     */
    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = "$offset, $limit";

        return $this;
    }

    /**
     * Executa a consulta SQL e retorna os resultados.
     *
     * @return array Os resultados da consulta.
     */
    public function fetch(): array
    {
        $sql = "SELECT " . implode(", ", $this->fields) . " FROM " . $this->table;

        if (!empty($this->joins)) {
            $sql .= " " . implode(" ", $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }

        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY " . implode(", ", $this->groupBy);
        }

        if (!empty($this->having)) {
            $sql .= " HAVING " . implode(" AND ", $this->having);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(", ", $this->orderBy);
        }

        if (!empty($this->limit)) {
            $sql .= " LIMIT " . $this->limit;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($this->params);

        return $stmt->fetchAll();
    }

    /**
     * Define a cláusula INSERT da consulta SQL.
     *
     * @param array $data Os dados a serem inseridos.
     * @return self
     */
    public function insert(array $data): self
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($values), '?');

        $fields = implode(', ', $fields);
        $placeholders = implode(', ', $placeholders);

        $sql = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";

        $this->query = $sql;
        $this->params = $values;

        return $this;
    }

    /**
     * Define a cláusula UPDATE da consulta SQL.
     *
     * @param array $data Os dados a serem atualizados.
     * @return self
     */
    public function update(array $data): self
    {
        $set = [];
        $values = [];

        foreach ($data as $field => $value) {
            $set[] = "$field = ?";
            $values[] = $value;
        }

        $set = implode(', ', $set);

        $sql = "UPDATE $this->table SET $set";

        $this->query = $sql;
        $this->params = $values;

        return $this;
    }

    /**
     * Define a cláusula DELETE da consulta SQL.
     *
     * @return self
     */
    public function delete(): self
    {
        $sql = "DELETE FROM $this->table";

        $this->query = $sql;

        return $this;
    }

    /**
     * Executa a consulta SQL definida pela função insert() ou update() e retorna o resultado da execução.
     *
     * @return bool O resultado da execução da consulta SQL.
     */
    public function execute(): bool
    {
        if (!empty($this->where)) {
            $this->query .= " WHERE " . implode(" AND ", $this->where);
        }

        $stmt = $this->conn->prepare($this->query);
        return $stmt->execute($this->params);
    }

}
