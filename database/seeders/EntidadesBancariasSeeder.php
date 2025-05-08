<?php

namespace Database\Seeders;

use App\Models\EntidadesBancarias;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntidadesBancariasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 1;

    $entidadesFinancieras = [
      'Banco de Comercio',
      'Banco de Crédito del Perú',
      'Banco Interamericano de Finanzas (BanBif)',
      'Banco Pichincha',
      'BBVA',
      'Citibank Perú',
      'Interbank',
      'MiBanco',
      'Scotiabank Perú',
      'Banco GNB Perú',
      'Banco Falabella',
      'Banco Ripley',
      'Banco Santander Perú',
      'Alfin Banco',
      'Bank of China',
      'Bci Perú',
      'ICBC PERU BANK',
      'Agrobanco',
      'Banco de la Nación',
      'COFIDE',
      'Fondo MiVivienda',
      'Financiera Crediscotia',
      'Financiera Confianza',
      'Financiera Compartamos',
      'Financiera Efectiva',
      'Financiera Proempresa',
      'Financiera Surgir',
      'Financiera Mitsui Auto Finance',
      'Financiera Oh!',
      'Financiera Qapaq',
      'Caja Arequipa',
      'Caja Cusco',
      'Caja Del Santa',
      'Caja Trujillo',
      'Caja Huancayo',
      'Caja Ica',
      'Caja Maynas',
      'Caja Paita',
      'Caja Piura',
      'Caja Tacna',
      'Caja Metropolitana de Lima',
      'Incasur',
      'Los Andes',
      'Prymera',
      'Del Centro',
      'Cencosud Scotia',
      'Alternativa',
      'Volvo Financial Services',
      'Vivela',
      'Santander Consumer Perú',
      'TOTAL, Servicios Financieros',
      'J.P. Morgan Banco de Inversión'
    ];

    foreach ($entidadesFinancieras as $ef) {

      EntidadesBancarias::create([
        'nombre_entidad' => $ef,
        'codigo' => 'EF-' . $contador,
      ]);

      $contador++;
    }
  }
}
