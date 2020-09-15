<?php

namespace Source\App\Admin;

use Source\Models\Address;
use Source\Models\Customer;
use Source\Support\Pager;
use Source\Support\Thumb;
use Source\Support\Upload;
use Source\Models\CustomerHasAddress;

/**
 * Class Customers
 * @package Source\App\Admin
 */
class Customers extends Admin
{
	/**
	 * Customers constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param array|null $data
	 */
	public function home(?array $data): void
	{
		//search redirect
		if (!empty($data["s"])) {
			$s = str_search($data["s"]);
			echo json_encode(["redirect" => url("/admin/clientes/{$s}/1")]);
			return;
		}

		$search = null;
		$customers = (new Customer())->find();

		if (!empty($data["search"]) && str_search($data["search"]) != "all") {
			$search = str_search($data["search"]);
			$customers = (new Customer())->find("MATCH(name, cpf, phone) AGAINST(:s)", "s={$search}");
			if (!$customers->count()) {
				$this->message->info("Sua pesquisa não retornou resultados")->flash();
				redirect("/admin/clientes");
			}
		}

		$all = ($search ?? "all");
		$pager = new Pager(url("/admin/clientes/{$all}/"));
		$pager->pager($customers->count(), 12, (!empty($data["page"]) ? $data["page"] : 1));

		$head = $this->seo->render(
			CONF_SITE_NAME . " | Clientes",
			CONF_SITE_DESC,
			url("/admin"),
			url("/admin/assets/images/image.jpg"),
			false
		);

		echo $this->view->render("customers/home", [
			"app" => "clientes/home",
			"head" => $head,
			"search" => $search,
			"customers" => $customers->order("name")->limit($pager->limit())->offset($pager->offset())->fetch(true),
			"paginator" => $pager->render()
		]);
	}

	public function create(?array $data): void
	{
		$head = $this->seo->render(
			CONF_SITE_NAME . " | Novo Cliente",
			CONF_SITE_DESC,
			url("/admin"),
			url("/admin/assets/images/image.jpg"),
			false
		);

		echo $this->view->render("customers/form", [
			"app" => "customers/form",
			"head" => $head,
		]);
	}

	public function store(?array $data): void
	{
		if (isset($data) && $data) {
			$data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

			$customerCreate = new Customer();
			$customerCreate->name = $data["name"];
			$customerCreate->birth_date = date_fmt_back($data["birth_date"]);
			$customerCreate->cpf = str_remove_specials($data["cpf"]);
			$customerCreate->rg = $data["rg"];
			$customerCreate->phone = str_remove_specials($data["phone"]);


			if (!$customerCreate->save()) {
				$json["message"] = $customerCreate->message()->render();
				echo json_encode($json);
				return;
			}

			if (isset($data['addresses']) && $data['addresses']) {
				foreach ($data['addresses'] as $address) {
					$addressCreate = new Address();
					$addressCreate->zip_code = $address['zip_code'];
					$addressCreate->address = $address['address'];
					$addressCreate->state = $address['state'];
					$addressCreate->city = $address['city'];
					$addressCreate->area = $address['area'];
					$addressCreate->number = $address['number'] ?? null;
					$addressCreate->details = $address['details'] ?? null;

					if ($addressCreate->save()) {
						$relationCreate = new CustomerHasAddress();
						$relationCreate->customer_id = $customerCreate->id;
						$relationCreate->address_id = $addressCreate->id;
						$relationCreate->save();
					}
				}
			}


			$this->message->success("Cliente cadastrado com sucesso!")->flash();
			$json["redirect"] = url("/admin/clientes");

			echo json_encode($json);
		}
		return;
	}

	public function edit(?array $data): void
	{
		$customerEdit = null;
		if (!empty($data["customer_id"])) {
			$customerId = filter_var($data["customer_id"], FILTER_VALIDATE_INT);
			$customerEdit = (new Customer())->findById($customerId);
		}

		$head = $this->seo->render(
			CONF_SITE_NAME . " | {$customerEdit->name}",
			CONF_SITE_DESC,
			url("/admin"),
			url("/admin/assets/images/image.jpg"),
			false
		);

		echo $this->view->render("customers/form", [
			"app" => "customers/form",
			"head" => $head,
			"customer" => $customerEdit,
			"addresses" => $customerEdit->addresses()
		]);
	}

	public function update(?array $data): void
	{
		if (isset($data) && $data) {
			$data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$customerUpdate = (new Customer())->findById((int)$data["customer_id"]);

			if (!$customerUpdate) {
				$this->message->error("Você tentou gerenciar um cliente que não existe")->flash();
				echo json_encode(["redirect" => url("/admin/clientes")]);
				return;
			}

			$customerUpdate->name = $data["name"];
			$customerUpdate->birth_date = date_fmt_back($data["birth_date"]);
			$customerUpdate->cpf = str_remove_specials($data["cpf"]);
			$customerUpdate->rg = $data["rg"];
			$customerUpdate->phone = str_remove_specials($data["phone"]);

			if (!$customerUpdate->save()) {
				$json["message"] = $customerUpdate->message()->render();
				echo json_encode($json);
				return;
			}

			$customerUpdate->clearAddresses();
			if ((isset($data['addresses']) && $data['addresses'])) {
				foreach ($data['addresses'] as $address) {
					$addressCreate = new Address();
					$addressCreate->zip_code = $address['zip_code'];
					$addressCreate->address = $address['address'];
					$addressCreate->state = $address['state'];
					$addressCreate->city = $address['city'];
					$addressCreate->area = $address['area'];
					$addressCreate->number = $address['number'] ?? null;
					$addressCreate->details = $address['details'] ?? null;

					if ($addressCreate->save()) {
						$relationCreate = new CustomerHasAddress();
						$relationCreate->customer_id = $customerUpdate->id;
						$relationCreate->address_id = $addressCreate->id;
						$relationCreate->save();
					}
				}
			}


			$this->message->success("Cliente atualizado com sucesso!")->flash();
			$json["redirect"] = url("/admin/clientes");
			echo json_encode($json);
		}
		return;
	}

	public function delete(?array $data): void
	{
		if (isset($data['customer_id']) && $data['customer_id']) {
			$data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
			$customerDelete = (new Customer())->findById($data["customer_id"]);

			if (!$customerDelete) {
				$this->message->error("Você tentou deletar um cliente que não existe")->flash();
				redirect('admin/clientes');
			}

			$customerDelete->destroy();

			$this->message->success("O cliente foi excluído com sucesso!")->flash();
		}
		redirect('admin/clientes');
		return;
	}
}
