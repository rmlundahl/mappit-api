<?php
/*
 * This seeder is added as part of a project setup.
 * It can be run from the commandline as follows:
 *
 * php artisan db:seed --class=ProjectSetupLerenMetDeStadSeeder.php
 *
 * if the className is not recognised, update your classmap files:
 * composer dump-autoload -o
 *
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSetupLerenMetDeStadSeeder extends Seeder
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
                'name' => 'Thema',
                'slug' => 'thema',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Faculteit Hogeschool Leiden',
                'slug' => 'faculteit_hogeschool_leiden',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Faculteit Universiteit Leiden',
                'slug' => 'faculteit_universiteit_leiden',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '5000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Faculteit mboRijnland',
                'slug' => 'faculteit_mborijnland',                
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Partner type',
                'slug' => 'partner_type',                
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Gebied',
                'slug' => 'gebied',                
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
                'name' => 'Hogeschool Leiden',
                'slug' => 'hogeschool_leiden',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1102',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'Universiteit Leiden',
                'slug' => 'universiteit_leiden',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1103',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'mboRijnland',
                'slug' => 'mborijnland',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '1104',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'Samenwerking',
                'slug' => 'samenwerking',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            
            //
            // items for 'Thema'
            //
            [
                'id' => '2010',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Duurzaamheid',
                'slug' => 'duurzaamheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2020',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Gezondheid',
                'slug' => 'gezondheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2030',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Welzijn',
                'slug' => 'welzijn',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2040',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Veiligheid',
                'slug' => 'veiligheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2050',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Financieel',
                'slug' => 'financieel',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2060',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Jeugd',
                'slug' => 'jeugd',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2070',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Cultuur',
                'slug' => 'cultuur',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '2080',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Educatie',
                'slug' => 'educatie',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],

            //
            // items for 'Faculteit Hogeschool Leiden'
            //
            [
                'id' => '3010',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Educatie',
                'slug' => 'educatie',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3020',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Gezondheidszorg',
                'slug' => 'gezondheidszorg',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3030',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Management en Bedrijf',
                'slug' => 'management_en_bedrijf',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3040',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Science en Technology',
                'slug' => 'science_en_technology',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '3050',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Sociaal Werk en Toegepaste Psychologie',
                'slug' => 'Sociaal_werk_en_toegepaste_psychologie',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],

            //
            // items for 'Faculteit Universiteit Leiden'
            //
            [
                'id' => '4010',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Archeologie',
                'slug' => 'archeologie',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4020',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Geesteswetenschappen',
                'slug' => 'geesteswetenschappen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4030',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Geneeskunde/LUMC',
                'slug' => 'geneeskunde_lumc',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4040',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Governance and Global Affairs',
                'slug' => 'governance_and_global_affairs',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4050',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Rechtsgeleerdheid',
                'slug' => 'rechtsgeleerdheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4060',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Sociale Wetenschappen',
                'slug' => 'sociale_wetenschappen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '4070',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Wiskunde en Natuurwetenschappen',
                'slug' => 'wiskunde_en_natuurwetenschappen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],

            //
            // items for 'Faculteit mboRijnland'
            //
            [
                'id' => '5010',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Sociaal Werk',
                'slug' => 'sociaal_werk',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            
            //
            // items for 'Partner type'
            //
            [
                'id' => '6010',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Bedrijfsleven',
                'slug' => 'bedrijfsleven',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6020',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Kennisinstellingen',
                'slug' => 'Kennisinstellingen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6030',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Overheid',
                'slug' => 'overheid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6040',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Ondernemers en coöperaties',
                'slug' => 'ondernemers_en_cooperaties',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6050',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Maatschappelijke instellingen',
                'slug' => 'maatschappelijke_instellingen',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6060',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Bewoners en communities',
                'slug' => 'bewoners_en_communities',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '6070',
                'language' => 'nl',
                'parent_id' => 6000,
                'name' => 'Vaste partners',
                'slug' => 'vaste_partners',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            
            //
            // items for 'Gebied'
            //
            [
                'id' => '7010',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Binnenstad Zuid',
                'slug' => 'binnenstad_zuid',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7020',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Binnenstad Noord',
                'slug' => 'binnenstad_noord',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7030',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Stationsdistrict',
                'slug' => 'stationsdistrict',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7040',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Leiden Noord',
                'slug' => 'leiden_noord',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7050',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Roodenburgerdistrict',
                'slug' => 'roodenburgerdistrict',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7060',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Bos- en Gasthuisdistrict',
                'slug' => 'bos-_en_gasthuisdistrict',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7070',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Morsdistrict',
                'slug' => 'norsdistrict',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7080',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Boerhaavedistrict',
                'slug' => 'boerhaavedistrict',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7090',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Merenwijk',
                'slug' => 'merenwijk',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
            [
                'id' => '7100',
                'language' => 'nl',
                'parent_id' => 7000,
                'name' => 'Stevenshof',
                'slug' => 'stevenshof',
                'created_at' => '2023-03-09',
                'updated_at' => '2023-03-09',
                'status_id' => 20
            ],
        ]);
    }
}
