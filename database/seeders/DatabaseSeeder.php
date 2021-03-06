<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		//Default user
		DB::table('users')->insert([
			[
				'name' => 'Administrador',
				'email' => 'admin@admin',
				'password' => Hash::make('12345678'),
				'avatar' => 'none'
			]
		]);

		//Group
		DB::table('groupdecision')->insert([
			['node' => 1, 'email' => 'rvfrancozo@gmail.com', 'weight' => 1/3],
			['node' => 1, 'email' => 'rafael.francozo@ifms.edu.br', 'weight' => 1/3],
			['node' => 7, 'email' => 'rafael.francozo@ifms.edu.br', 'weight' => 0.5],
		]);

		//Populate Sample
		DB::table('node')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 0, 'descr' => 'Comprar um Carro'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Custo'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Conforto'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Segurança'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 2, 'descr' => 'Carro1'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 2, 'descr' => 'Carro2'],
		]);

		//Julgamento dos Critérios com relação ao objetivo
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 1, 'id_node1' => 2, 'id_node2' => 3, 'score' => 7], //custo x conforto
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 1, 'id_node1' => 2, 'id_node2' => 4, 'score' => 3], //custo x segurança
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 1, 'id_node1' => 3, 'id_node2' => 4, 'score' => 1 / 3], //conforto x segurança
		]);

		//Julgamento das Alternativas com relação ao Custo
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 2, 'id_node1' => 5, 'id_node2' => 6, 'score' => 7],
		]);

		//Julgamento das Alternativas com relação ao Conforto
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 3, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1 / 5],
		]);

		//Julgamento das Alternativas com relação ao Segurança
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 4, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1 / 9],
		]);

		//Group Populate rvfrancozo@gmail.com
		//Julgamento dos Critérios com relação ao objetivo
		DB::table('judments')->insert([
			['user_email' => 'rvfrancozo@gmail.com', 'id_node' => 1, 'id_node1' => 2, 'id_node2' => 3, 'score' => 1], //custo x conforto
			['user_email' => 'rvfrancozo@gmail.com', 'id_node' => 1, 'id_node1' => 2, 'id_node2' => 4, 'score' => 1], //custo x segurança
			['user_email' => 'rvfrancozo@gmail.com', 'id_node' => 1, 'id_node1' => 3, 'id_node2' => 4, 'score' => 1], //conforto x segurança
		]);

		//Julgamento das Alternativas com relação ao Custo
		DB::table('judments')->insert([
			['user_email' => 'rvfrancozo@gmail.com', 'id_node' => 2, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação ao Conforto
		DB::table('judments')->insert([
			['user_email' => 'rvfrancozo@gmail.com', 'id_node' => 3, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação ao Segurança
		DB::table('judments')->insert([
			['user_email' => 'rvfrancozo@gmail.com', 'id_node' => 4, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1],
		]);

		//Group Populate rafael.francozo@ifms.edu.br
		//Julgamento dos Critérios com relação ao objetivo
		DB::table('judments')->insert([
			['user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 1, 'id_node1' => 2, 'id_node2' => 3, 'score' => 1], //custo x conforto
			['user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 1, 'id_node1' => 2, 'id_node2' => 4, 'score' => 1], //custo x segurança
			['user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 1, 'id_node1' => 3, 'id_node2' => 4, 'score' => 1], //conforto x segurança
		]);

		//Julgamento das Alternativas com relação ao Custo
		DB::table('judments')->insert([
			['user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 2, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação ao Conforto
		DB::table('judments')->insert([
			['user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 3, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação ao Segurança
		DB::table('judments')->insert([
			['user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 4, 'id_node1' => 5, 'id_node2' => 6, 'score' => 1],
		]);


		//Problema 2 Escolha de Líder
		//https://en.wikipedia.org/wiki/Analytic_hierarchy_process_%E2%80%93_leader_example
		DB::table('node')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 0, 'descr' => 'Choosing a Leader'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Experience'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Education'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Charisma'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 1, 'descr' => 'Age'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 2, 'descr' => 'Tom'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 2, 'descr' => 'Dick'],
			['user_id' => 1, 'user_email' => 'admin@admin', 'level' => 2, 'descr' => 'Harry'],
		]);

		//Julgamento dos Critérios rafael.francozo@ifms.edu.br
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 7, 'id_node1' => 8, 'id_node2' => 9, 'score' => 1], //Experience x Education
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 7, 'id_node1' => 8, 'id_node2' => 10, 'score' => 1], //Experience x Charisma
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 7, 'id_node1' => 8, 'id_node2' => 11, 'score' => 1], //Experience x Age
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 7, 'id_node1' => 9, 'id_node2' => 10, 'score' => 1], //Education x Charisma
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 7, 'id_node1' => 9, 'id_node2' => 11, 'score' => 1], //Education x Age
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 7, 'id_node1' => 10, 'id_node2' => 11, 'score' => 1], //Age x Charisma
		]);

		//Julgamento das Alternativas com relação a Experience
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 8, 'id_node1' => 12, 'id_node2' => 13, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 8, 'id_node1' => 12, 'id_node2' => 14, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 8, 'id_node1' => 13, 'id_node2' => 14, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação a Education
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 9, 'id_node1' => 12, 'id_node2' => 13, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 9, 'id_node1' => 12, 'id_node2' => 14, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 9, 'id_node1' => 13, 'id_node2' => 14, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação a Charisma
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 10, 'id_node1' => 12, 'id_node2' => 13, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 10, 'id_node1' => 12, 'id_node2' => 14, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 10, 'id_node1' => 13, 'id_node2' => 14, 'score' => 1],
		]);

		//Julgamento das Alternativas com relação a Age
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 11, 'id_node1' => 12, 'id_node2' => 13, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 11, 'id_node1' => 12, 'id_node2' => 14, 'score' => 1],
			['user_id' => 1, 'user_email' => 'rafael.francozo@ifms.edu.br', 'id_node' => 11, 'id_node1' => 13, 'id_node2' => 14, 'score' => 1],
		]);

		//Julgamento dos Critérios admin@admin
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 7, 'id_node1' => 8, 'id_node2' => 9, 'score' => 4], //Experience x Education
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 7, 'id_node1' => 8, 'id_node2' => 10, 'score' => 3], //Experience x Charisma
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 7, 'id_node1' => 8, 'id_node2' => 11, 'score' => 7], //Experience x Age
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 7, 'id_node1' => 9, 'id_node2' => 10, 'score' => 1 / 3], //Education x Charisma
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 7, 'id_node1' => 9, 'id_node2' => 11, 'score' => 3], //Education x Age
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 7, 'id_node1' => 10, 'id_node2' => 11, 'score' => 5], //Age x Charisma
		]);

		//Julgamento das Alternativas com relação a Experience
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 8, 'id_node1' => 12, 'id_node2' => 13, 'score' => 3],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 8, 'id_node1' => 12, 'id_node2' => 14, 'score' => 1 / 5],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 8, 'id_node1' => 13, 'id_node2' => 14, 'score' => 1 / 7],
		]);

		//Julgamento das Alternativas com relação a Education
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 9, 'id_node1' => 12, 'id_node2' => 13, 'score' => 3],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 9, 'id_node1' => 12, 'id_node2' => 14, 'score' => 1 / 5],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 9, 'id_node1' => 13, 'id_node2' => 14, 'score' => 1 / 7],
		]);

		//Julgamento das Alternativas com relação a Charisma
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 10, 'id_node1' => 12, 'id_node2' => 13, 'score' => 5],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 10, 'id_node1' => 12, 'id_node2' => 14, 'score' => 9],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 10, 'id_node1' => 13, 'id_node2' => 14, 'score' => 4],
		]);

		//Julgamento das Alternativas com relação a Age
		DB::table('judments')->insert([
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 11, 'id_node1' => 12, 'id_node2' => 13, 'score' => 1 / 3],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 11, 'id_node1' => 12, 'id_node2' => 14, 'score' => 5],
			['user_id' => 1, 'user_email' => 'admin@admin', 'id_node' => 11, 'id_node1' => 13, 'id_node2' => 14, 'score' => 9],
		]);
	}
}
