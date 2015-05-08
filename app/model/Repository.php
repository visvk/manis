<?php

namespace Project;

use Nette;
use Nette\Database\Connection;

/**
 * Provádí operace nad databázovou tabulkou.
 */
abstract class Repository extends Nette\Object {

    /** @var Nette\Database\Context */
    public $db;

    public function __construct(Connection $db) {
        $this->db = new Nette\Database\Context($db);
    }
	public function findByID($id)
	{
		return $this->findAll()->where('id', $id)->fetch();
	}
    /**
     * Vrací objekt reprezentující databázovou tabulku.
     * @return Nette\Database\Table\Selection
     */
    protected function getTable() {
// název tabulky odvodíme z názvu třídy
        preg_match('#(\w+)Repository$#', get_class($this), $m);
        return $this->db->table(lcfirst($m[1]));
    }

    /**
     * Vrací všechny řádky z tabulky.
     * @return Nette\Database\Table\Selection
     */
    public function findAll() {
        return $this->getTable();
    }

    /**
     * Vrací řádky podle filtru, např. array('name' => 'John').
     * @return Nette\Database\Table\Selection
     */
    public function findBy(array $by) {
        return $this->getTable()->where($by);
    }

}