<?php

namespace Source\Models;

use Source\Core\Model;

class Customer extends Model
{
	public $addresses = [];

	/**
	 * Customer constructor.
	 */
	public function __construct()
	{
		parent::__construct("customers", ["id"], ["name", "cpf", "phone"]);
	}

	/**
	 * @param string $name
	 * @param string $cpf
	 * @param string $phone
	 * @return Customer
	 */
	public function bootstrap(
		string $name,
		string $cpf,
		string $phone
	): Customer {
		$this->name = $name;
		$this->cpf = $cpf;
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return Address|null
	 */
	public function addresses(): ?array
	{
		$pivot = (new CustomerHasAddress())->find('customer_id = :cid', "cid={$this->id}");

		if ($pivot->count()) {
			foreach ($pivot->fetch(true) as $rel) {
				$this->addresses[] = (new Address())->findById($rel->address_id)->data;
			}
		}
		return $this->addresses;

	}

	public function clearAddresses(): bool
	{
		$pivot = (new CustomerHasAddress())->find('customer_id = :cid', "cid={$this->id}");

		if ($pivot->count()) {
			foreach ($pivot->fetch(true) as $rel) {
				$subject = (new Address())->findById($rel->address_id);
				if (!$subject->destroy()) {
					return false;
				}
			}
			return true;
		} else {
			return true;
		}
	}

	/**
	 * @param string $cpf
	 * @param string $columns
	 * @return null|Customer
	 */
	public function findByCpf(string $cpf, string $columns = "*"): ?Customer
	{
		$find = $this->find("cpf = :c", "c={$cpf}", $columns);
		return $find->fetch();
	}

	/**
	 * @return string
	 */
	public function firstName(): string
	{
		return substr($this->name, 0, strpos($this->name, ' '));
	}

	public function destroy(): bool
	{
		$destroy = parent::destroy();

		if ($destroy) {
			$this->clearAddresses();
		}

		return $destroy;
	}

	/**
	 * @return bool
	 */
	public function save(): bool
	{
		if (!$this->required()) {
			$this->message->warning("Nome, CPF e telefone são obrigatórios");
			return false;
		}

		if (!is_cpf($this->cpf)) {
			$this->message->warning("CPF inválido");
			return false;
		}


		/** Customer Update */
		if (!empty($this->id)) {
			$customerId = $this->id;

			if ($this->find("cpf = :c AND id != :i", "c={$this->cpf}&i={$customerId}", "id")->fetch()) {
				$this->message->warning("O CPF informado já está cadastrado");
				return false;
			}

			$this->update($this->safe(), "id = :id", "id={$customerId}");
			if ($this->fail()) {
				$this->message->error("Erro ao atualizar, verifique os dados");
				return false;
			}
		}

		/** Customer Create */
		if (empty($this->id)) {
			if ($this->findByCpf($this->cpf, "id")) {
				$this->message->warning("O CPF informado já está cadastrado");
				return false;
			}

			$customerId = $this->create($this->safe());
			if ($this->fail()) {
				$this->message->error("Erro ao cadastrar, verifique os dados");
				return false;
			}
		}

		$this->data = ($this->findById($customerId))->data();
		return true;
	}
}
