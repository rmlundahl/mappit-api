<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Log;

class ProjectSetupSeeder extends Seeder
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
                'name' => 'Student of medewerker',
                'slug' => 'student_of_medewerker',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Opleiding',
                'slug' => 'opleiding',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Soort studie werk',
                'slug' => 'soort_studie_werk',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Periode',
                'slug' => 'periode',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '5000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Organisatie', // instituut, universiteit, onderwijsinstelling
                'slug' => 'organisatie',                
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '6000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Land',
                'slug' => 'land',                
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '7000',
                'language' => 'nl',
                'parent_id' => null,
                'name' => 'Stad',
                'slug' => 'stad',                
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],

            //
            // items for 'student of medewerker'
            //
            [
                'id' => '1020',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'student',
                'slug' => 'student',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '1030',
                'language' => 'nl',
                'parent_id' => 1000,
                'name' => 'medewerker',
                'slug' => 'medewerker',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],

            //
            // items for 'Opleiding'
            //
            [
                'id' => '2010',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Alle opleidingen',
                'slug' => 'alle_opleidingen',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2020',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Bestuurskunde',
                'slug' => 'bestuurskunde',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2030',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Ervaringsdeskundigheid in Zorg en Welzijn',
                'slug' => 'ervaringsdeskundigheid_in_zorg_en_welzijn',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2040',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'HBO-Rechten',
                'slug' => 'hbo_rechten',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2050',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Legal Management',
                'slug' => 'legal_management',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2060',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Social Work',
                'slug' => 'social_work',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2070',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Urban Managment',
                'slug' => 'urban_managment',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2080',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Sociaal Juridische Dienstverlening',
                'slug' => 'sociaal_juridische_dienstverlening',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '2090',
                'language' => 'nl',
                'parent_id' => 2000,
                'name' => 'Toegepaste Psychologie',
                'slug' => 'toegepaste_psychologie',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],

            //
            // items for 'Soort studie werk'
            //
            [
                'id' => '3010',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Alle studie en werk',
                'slug' => 'alle_studie_en_werk',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3020',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Studie',
                'slug' => 'studie',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3030',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Stage',
                'slug' => 'stage',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3040',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Afstuderen',
                'slug' => 'afstuderen',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3050',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Studiereis',
                'slug' => 'studiereis',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3060',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Onderzoek',
                'slug' => 'onderzoek',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3070',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Project',
                'slug' => 'project',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3080',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Les geven in het buitenland',
                'slug' => 'les_geven_in_het_buitenland',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '3090',
                'language' => 'nl',
                'parent_id' => 3000,
                'name' => 'Online les volgen in het buitenland',
                'slug' => 'Online_les_volgen_in_het_buitenland',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],

            //
            // items for 'Periode'
            //
            [
                'id' => '4010',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Alle periodes',
                'slug' => 'alle_periodes',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4020',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Eerste semester',
                'slug' => 'eerste_semester',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4030',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Tweede semester',
                'slug' => 'tweede_semester',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4040',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Summer course',
                'slug' => 'summer_course',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4050',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Winter course',
                'slug' => 'winter_course',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4060',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => '3 maanden',
                'slug' => '3_maanden',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4070',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Half jaar',
                'slug' => 'half_jaar',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4080',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => '1 jaar',
                'slug' => '1_jaar',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
            [
                'id' => '4090',
                'language' => 'nl',
                'parent_id' => 4000,
                'name' => 'Geen periode',
                'slug' => 'geen_periode',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],

            //
            // items for 'Organisatie'
            //
            [
                'id' => '5010',
                'language' => 'nl',
                'parent_id' => 5000,
                'name' => 'Alle onderwijsinstellingen',
                'slug' => 'alle_onderwijsinstellingen',
                'created_at' => '2020-12-24',
                'updated_at' => '2020-12-24',
                'status_id' => 20
            ],
        ]);
    }
}
