<?php

namespace Source\Models;

use Source\Core\Model;

class CustomerHasAddress extends Model
{
	/**
	 * Address constructor.
	 */
	public function __construct()
	{
		parent::__construct("customer_has_address", [], ["customer_id", "address_id"]);
	}

	/**
	 * @param int $customer_id
	 * @param int $address_id
	 * @return CustomerHasAddress
	 */
	public function bootstrap(
		string $customer_id,
		string $address_id
	): Address {
		$this->customer_id = $customer_id;
		$this->address_id = $address_id;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function save(): bool
	{
		if (!$this->required()) {
			$this->message->warning("ID de cliente e ID de endereço são obrigatórios");
			return false;
		}

		/** Relation Create */
		$relationId = $this->create($this->safe());
		if ($this->fail()) {
			$this->message->error("Erro ao cadastrar, verifique os dados");
			return false;
		}

		return true;
	}
}
