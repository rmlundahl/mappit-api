<?php
/*
 * This seeder is added as part of a project setup.
 * It can be run from the commandline as follows:
 *
 * php artisan db:seed --class=ProjectSetupHvaInDeStadSeeder
 *
 * if the className is not recognised, update your classmap files:
 * composer dump-autoload -o
 *
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSetupHvaInDeStadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('filters')->truncate();

        DB::table('filters')->insert([
            [
                'id' => '1000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Ik zoek een',
                'slug' => 'ik_zoek_een',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Status',
                'slug' => 'status',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Samen met HvA',
                'slug' => 'samen_met_hva',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Samen met partners',
                'slug' => 'samen_met_partners',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Samen met sectoren',
                'slug' => 'samen_met_sectoren',                
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'In het gebied',
                'slug' => 'in_het_gebied',                
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Selecteer partners',
                'slug' => 'selecteer_partners',                
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '8000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Sustainable development goals (SDG’s)',
                'slug' => 'sdgs',                
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],

            //
            // items for 'Ik zoek een'
            //
            [
                'id' => '1101',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'Studentenproject',
                'slug' => 'studentenproject',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1102',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'Onderzoeksproject',
                'slug' => 'onderzoeksproject',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1103',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'HvA Lab',
                'slug' => 'hva_lab',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1104',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'HvA Campus',
                'slug' => 'hva_campus',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1105',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'Centre of Expertise',
                'slug' => 'centre_of_expertise',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            //
            // items for 'Status'
            //
            [
                'id' => '2010',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Actueel',
                'slug' => 'actueel',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2020',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Afgerond',
                'slug' => 'afgerond',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
           
            //
            // items for 'Samen met HvA'
            //
            [
                'id' => '3010',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Bachelors',
                'slug' => 'bachelors',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3020',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Minoren',
                'slug' => 'minoren',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3030',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Masters',
                'slug' => 'masters',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3040',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Lectoraten',
                'slug' => 'lectoraten',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            
            //
            // items for 'Samen met partners'
            //
            [
                'id' => '4010',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Bedrijfsleven',
                'slug' => 'bedrijfsleven',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4020',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Maatschappelijke instellingen',
                'slug' => 'maatschappelijke_instellingen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4030',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Ondernemers en coöperaties',
                'slug' => 'ondernemers_en_coöperaties',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4040',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Overheid',
                'slug' => 'overheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4050',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Bewoners en communities',
                'slug' => 'bewoners_en_communities',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4060',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Kennisinstellingen',
                'slug' => 'kennisinstellingen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            
            //
            // items for 'Samen met sectoren'
            //
            [
                'id' => '5010',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Bouw en Infrastructuur',
                'slug' => 'bouw_en_infrastructuur',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5020',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Economie en Management',
                'slug' => 'economie_en_management',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5030',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Logistiek, Lucht- en Zeevaart',
                'slug' => 'logistiek_lucht_en_zeevaart',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5040',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Design en Creatie',
                'slug' => 'design_en_creatie',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5050',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'ICT',
                'slug' => 'ict',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5060',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Gezondheid',
                'slug' => 'gezondheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5070',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Media en Communicatie',
                'slug' => 'media_en_communicatie',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5080',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Mens en Maatschappij',
                'slug' => 'mens_en_maatschappij',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5090',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Onderwijs en Opvoeding',
                'slug' => 'onderwijs_en_opvoeding',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5100',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Recht en Bestuur',
                'slug' => 'recht_en_bestuur',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5110',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Sport en Voeding',
                'slug' => 'sport_en_voeding',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5120',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Techniek',
                'slug' => 'techniek',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],

            //
            // items for 'In het gebied'
            //
            [
                'id' => '6010',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Centrum',
                'slug' => 'stadsdeel_centrum',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6020',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Nieuw-West',
                'slug' => 'stadsdeel_nieuw_west',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6030',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Noord',
                'slug' => 'stadsdeel_noord',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6040',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Oost',
                'slug' => 'stadsdeel_oost',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6050',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel West',
                'slug' => 'stadsdeel_west',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6060',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Zuid',
                'slug' => 'stadsdeel_zuid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6070',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Zuidoost',
                'slug' => 'stadsdeel_zuidoost',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6080',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Stadsdeel Weesp',
                'slug' => 'stadsdeel_weesp',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6090',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Buiten Amsterdam',
                'slug' => 'buiten_amsterdam',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],

            //
            // items for 'Sustainable development goals (SDG’s)'
            //
            [
                'id' => '8010',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Geen armoede',
                'slug' => 'geen_armoede',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
        ]);
    }
}
