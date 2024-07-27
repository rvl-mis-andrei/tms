<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TractorTrailerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tractors = [];
        $trailers = [];

        // Tractors data
        $tractorsData = [
            ['name' => 'B01', 'plate_no' => 'NCQ 4600', 'body_no' => 'B01', 'description' => 'Tractor B01', 'remarks' => '', 'status' => 1],
            ['name' => 'B02', 'plate_no' => 'DDL 9813', 'body_no' => 'B02', 'description' => 'Tractor B02', 'remarks' => '', 'status' => 1],
            ['name' => 'B03', 'plate_no' => 'DBX 9612', 'body_no' => 'B03', 'description' => 'Tractor B03', 'remarks' => '', 'status' => 1],
            ['name' => 'B04', 'plate_no' => 'DDL 9554', 'body_no' => 'B04', 'description' => 'Tractor B04', 'remarks' => '', 'status' => 1],
            ['name' => 'B05', 'plate_no' => 'DBY 3538', 'body_no' => 'B05', 'description' => 'Tractor B05', 'remarks' => '', 'status' => 1],
            ['name' => 'B06', 'plate_no' => 'NCQ 4599', 'body_no' => 'B06', 'description' => 'Tractor B06', 'remarks' => '', 'status' => 1],
            ['name' => 'B07', 'plate_no' => 'DBX 9613', 'body_no' => 'B07', 'description' => 'Tractor B07', 'remarks' => '', 'status' => 1],
            ['name' => 'B08', 'plate_no' => 'DAO 6109', 'body_no' => 'B08', 'description' => 'Tractor B08', 'remarks' => '', 'status' => 1],
            ['name' => 'B09', 'plate_no' => 'DBZ 8846', 'body_no' => 'B09', 'description' => 'Tractor B09', 'remarks' => '', 'status' => 1],
            ['name' => 'B10', 'plate_no' => 'DCQ 3105', 'body_no' => 'B10', 'description' => 'Tractor B10', 'remarks' => '', 'status' => 1],
            ['name' => 'B11', 'plate_no' => 'DCQ 3038', 'body_no' => 'B11', 'description' => 'Tractor B11', 'remarks' => '', 'status' => 1],
            ['name' => 'B12', 'plate_no' => 'NCP 1125', 'body_no' => 'B12', 'description' => 'Tractor B12', 'remarks' => '', 'status' => 1],
            ['name' => 'B13', 'plate_no' => 'DCQ 3039', 'body_no' => 'B13', 'description' => 'Tractor B13', 'remarks' => '', 'status' => 1],
            ['name' => 'B14', 'plate_no' => 'DCQ 3036', 'body_no' => 'B14', 'description' => 'Tractor B14', 'remarks' => '', 'status' => 1],
            ['name' => 'B15', 'plate_no' => 'DDL 8320', 'body_no' => 'B15', 'description' => 'Tractor B15', 'remarks' => '', 'status' => 1],
            ['name' => 'B16', 'plate_no' => 'NCQ 9484', 'body_no' => 'B16', 'description' => 'Tractor B16', 'remarks' => '', 'status' => 1],
            ['name' => 'B17', 'plate_no' => 'DBX 9610', 'body_no' => 'B17', 'description' => 'Tractor B17', 'remarks' => '', 'status' => 1],
            ['name' => 'B18', 'plate_no' => 'DBZ 8845', 'body_no' => 'B18', 'description' => 'Tractor B18', 'remarks' => '', 'status' => 1],
            ['name' => 'B19', 'plate_no' => 'NCQ 9481', 'body_no' => 'B19', 'description' => 'Tractor B19', 'remarks' => '', 'status' => 1],
            ['name' => 'B20', 'plate_no' => 'NCN 9365', 'body_no' => 'B20', 'description' => 'Tractor B20', 'remarks' => '', 'status' => 1],
            ['name' => 'B21', 'plate_no' => 'NCN 9367', 'body_no' => 'B21', 'description' => 'Tractor B21', 'remarks' => '', 'status' => 1],
            ['name' => 'B22', 'plate_no' => 'NCQ 5775', 'body_no' => 'B22', 'description' => 'Tractor B22', 'remarks' => '', 'status' => 1],
            ['name' => 'B23', 'plate_no' => 'NCQ 9482', 'body_no' => 'B23', 'description' => 'Tractor B23', 'remarks' => '', 'status' => 1],
            ['name' => 'B24', 'plate_no' => 'NCQ 9487', 'body_no' => 'B24', 'description' => 'Tractor B24', 'remarks' => '', 'status' => 1],
            ['name' => 'B25', 'plate_no' => 'NCQ 4601', 'body_no' => 'B25', 'description' => 'Tractor B25', 'remarks' => '', 'status' => 1],
            ['name' => 'B26', 'plate_no' => 'DAO 6110', 'body_no' => 'B26', 'description' => 'Tractor B26', 'remarks' => '', 'status' => 1],
            ['name' => 'B27', 'plate_no' => 'NCN 9366', 'body_no' => 'B27', 'description' => 'Tractor B27', 'remarks' => '', 'status' => 1],
            ['name' => 'B28', 'plate_no' => 'DBY 3537', 'body_no' => 'B28', 'description' => 'Tractor B28', 'remarks' => '', 'status' => 1],
            ['name' => 'B29', 'plate_no' => 'NCQ 4595', 'body_no' => 'B29', 'description' => 'Tractor B29', 'remarks' => '', 'status' => 1],
            ['name' => 'B30', 'plate_no' => 'DAO 6111', 'body_no' => 'B30', 'description' => 'Tractor B30', 'remarks' => '', 'status' => 1],
            ['name' => 'B31', 'plate_no' => 'NCP 1124', 'body_no' => 'B31', 'description' => 'Tractor B31', 'remarks' => '', 'status' => 1],
            ['name' => 'B32', 'plate_no' => 'DBY 3540', 'body_no' => 'B32', 'description' => 'Tractor B32', 'remarks' => '', 'status' => 1],
        ];

        foreach ($tractorsData as $tractor) {
            $tractors[$tractor['name']] = DB::table('tractors')->insertGetId($tractor);
        }

        $trailersData = [
            ['name' => 'NZD204', 'plate_no' => 'NZD204', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3504', 'plate_no' => 'AUB3504', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3493', 'plate_no' => 'AUB3493', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3486', 'plate_no' => 'AUB3486', 'trailer_type_id' => 3, 'description' => '8MDL', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3494', 'plate_no' => 'AUB3494', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'NZD231', 'plate_no' => 'NZD231', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3495', 'plate_no' => 'AUB3495', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'MV-FILE 5906', 'plate_no' => 'MV-FILE 5906', 'trailer_type_id' => 4, 'description' => 'Panda 2', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3499', 'plate_no' => 'AUB3499', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3505', 'plate_no' => 'AUB3505', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3503', 'plate_no' => 'AUB3503', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUA7901', 'plate_no' => 'AUA7901', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'NZD292', 'plate_no' => 'NZD292', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3502', 'plate_no' => 'AUB3502', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUA6298', 'plate_no' => 'AUA6298', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => '8MDL-2018-009-C', 'plate_no' => '8MDL-2018-009-C', 'trailer_type_id' => 3, 'description' => '8MDL', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3492', 'plate_no' => 'AUB3492', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3498', 'plate_no' => 'AUB3498', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'NZD499', 'plate_no' => 'NZD499', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'NZD493', 'plate_no' => 'NZD493', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'NZD723', 'plate_no' => 'NZD723', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'MV-FILE-15902', 'plate_no' => 'MV-FILE-15902', 'trailer_type_id' => 5, 'description' => 'Panda 4', 'remarks' => '', 'status' => 1],
            ['name' => '8MDL-2018-006-C', 'plate_no' => '8MDL-2018-006-C', 'trailer_type_id' => 3, 'description' => '8MDL', 'remarks' => '', 'status' => 1],
            ['name' => 'AUA6804', 'plate_no' => 'AUA6804', 'trailer_type_id' => 3, 'description' => '8MDL', 'remarks' => '', 'status' => 1],
            ['name' => 'MV-FILE 11063', 'plate_no' => 'MV-FILE 11063', 'trailer_type_id' => 5, 'description' => 'Panda 3', 'remarks' => '', 'status' => 1],
            ['name' => 'AUA7741', 'plate_no' => 'AUA7741', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3501', 'plate_no' => 'AUB3501', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3489', 'plate_no' => 'AUB3489', 'trailer_type_id' => 2, 'description' => 'Bangpoo', 'remarks' => '', 'status' => 1],
            ['name' => 'AUB3224', 'plate_no' => 'AUB3224', 'trailer_type_id' => 3, 'description' => '8MDL', 'remarks' => '', 'status' => 1],
            ['name' => 'MV-FILE-11065', 'plate_no' => 'MV-FILE-11065', 'trailer_type_id' => 4, 'description' => 'Panda 1', 'remarks' => '', 'status' => 1],
            ['name' => '8MDL-2019-0003-C', 'plate_no' => '8MDL-2019-0003-C', 'trailer_type_id' => 3, 'description' => '8MDL', 'remarks' => '', 'status' => 1],
            ['name' => 'AUA6655', 'plate_no' => 'AUA6655', 'trailer_type_id' => 1, 'description' => 'Japan', 'remarks' => '', 'status' => 1],
        ];

        // Insert trailers and get their IDs
        foreach ($trailersData as $trailer) {
            $trailers[$trailer['name']] = DB::table('trailers')->insertGetId($trailer);
        }


        $tractorTrailersData = [
            ['tractor_id' => $tractors['B01'], 'trailer_id' => $trailers['NZD204'], 'pdriver' => 'NEBRIJA, ROMEL', 'sdriver' => 'SORONIO, DARWIN'],
            ['tractor_id' => $tractors['B02'], 'trailer_id' => $trailers['AUB3504'], 'pdriver' => 'COROCOTO JR., ROMULO', 'sdriver' => 'KIONISALA, JESSIE'],
            ['tractor_id' => $tractors['B03'], 'trailer_id' => $trailers['AUB3493'], 'pdriver' => 'MAGANA, ROEL', 'sdriver' => 'OLDAN, ANTHONY'],
            ['tractor_id' => $tractors['B04'], 'trailer_id' => $trailers['AUB3486'], 'pdriver' => 'BERSABAL, ASTERIO', 'sdriver' => 'DERI, ROBERT'],
            ['tractor_id' => $tractors['B05'], 'trailer_id' => $trailers['AUB3494'], 'pdriver' => 'OGANA, JOSELITO', 'sdriver' => 'ELLARDA, ROALT'],
            ['tractor_id' => $tractors['B06'], 'trailer_id' => $trailers['NZD231'], 'pdriver' => 'ZARRAGA, JOHN LOUEI', 'sdriver' => 'BERMUDEZ, JAY-R'],
            ['tractor_id' => $tractors['B07'], 'trailer_id' => $trailers['AUB3495'], 'pdriver' => 'NAZAR, FERDINAND', 'sdriver' => 'DANGHEL, EDGARDO'],
            ['tractor_id' => $tractors['B08'], 'trailer_id' => $trailers['MV-FILE 5906'], 'pdriver' => 'PARAN JR., ANTONIO', 'sdriver' => 'BERSABAL, RONIE'],
            ['tractor_id' => $tractors['B09'], 'trailer_id' => $trailers['AUB3499'], 'pdriver' => 'VILLAMIN, JHUDY', 'sdriver' => 'PONAY, PATERNO'],
            ['tractor_id' => $tractors['B10'], 'trailer_id' => $trailers['AUB3505'], 'pdriver' => 'ROMANES, FROILAN', 'sdriver' => 'RILLERA, LEO'],
            ['tractor_id' => $tractors['B11'], 'trailer_id' => $trailers['AUB3503'], 'pdriver' => 'ANATAN, ELIEZER', 'sdriver' => 'BERSABAL, ELMER'],
            ['tractor_id' => $tractors['B12'], 'trailer_id' => $trailers['AUA7901'], 'pdriver' => 'DERI, WILFREDO', 'sdriver' => 'CASIPONG, ANGELO'],
            ['tractor_id' => $tractors['B13'], 'trailer_id' => $trailers['NZD292'], 'pdriver' => 'PONSARAN, ANTONIO', 'sdriver' => 'BORBON, ZOSIMO'],
            ['tractor_id' => $tractors['B14'], 'trailer_id' => $trailers['AUB3502'], 'pdriver' => 'JUANICO, JOSEPH', 'sdriver' => 'SUAZO, RODERICK'],
            ['tractor_id' => $tractors['B15'], 'trailer_id' => $trailers['AUA6298'], 'pdriver' => 'NERIZ, JULIUS', 'sdriver' => 'BERMUDEZ, SAMUEL'],
            ['tractor_id' => $tractors['B16'], 'trailer_id' => $trailers['8MDL-2018-009-C'], 'pdriver' => 'CABRERA, JASPER', 'sdriver' => 'ANATAN, ALDWIN'],
            ['tractor_id' => $tractors['B17'], 'trailer_id' => $trailers['AUB3492'], 'pdriver' => 'BOLIVAR, ROBERTO', 'sdriver' => 'OGANA, RONNIE'],
            ['tractor_id' => $tractors['B18'], 'trailer_id' => $trailers['AUB3498'], 'pdriver' => 'PARAGUSAN, ANTONIO', 'sdriver' => 'PANDONG, MANUEL'],
            ['tractor_id' => $tractors['B19'], 'trailer_id' => $trailers['NZD499'], 'pdriver' => 'SORE, FELIMON', 'sdriver' => 'BERSABAL, EUGENE'],
            ['tractor_id' => $tractors['B20'], 'trailer_id' => $trailers['NZD493'], 'pdriver' => 'CABRERA, JOHN', 'sdriver' => 'CAYACAP, MANUEL'],
            ['tractor_id' => $tractors['B21'], 'trailer_id' => $trailers['NZD723'], 'pdriver' => 'CABRERA, PHILIP', 'sdriver' => 'DE LEON, ROMEO'],
            ['tractor_id' => $tractors['B22'], 'trailer_id' => $trailers['MV-FILE-15902'], 'pdriver' => 'CANONIZADO, JAY-AR', 'sdriver' => 'PANDONG, AURELIO'],
            ['tractor_id' => $tractors['B23'], 'trailer_id' => $trailers['8MDL-2018-006-C'], 'pdriver' => 'OGANA, MELVIN', 'sdriver' => 'MUSCO, CHRISTIAN'],
            ['tractor_id' => $tractors['B24'], 'trailer_id' => $trailers['AUA6804'], 'pdriver' => 'RAMOS, RAMON', 'sdriver' => 'OGANA, JERRY'],
            ['tractor_id' => $tractors['B25'], 'trailer_id' => $trailers['MV-FILE 11063'], 'pdriver' => 'MUSCO, JOHN', 'sdriver' => 'MUSCO, JOHNNY'],
            ['tractor_id' => $tractors['B26'], 'trailer_id' => $trailers['AUA7741'], 'pdriver' => 'MUSCO, BERNARD', 'sdriver' => 'MUSCO, BERNARDO'],
            ['tractor_id' => $tractors['B27'], 'trailer_id' => $trailers['AUB3501'], 'pdriver' => 'OGANA, ROEL', 'sdriver' => 'MUSCO, BRYAN'],
            ['tractor_id' => $tractors['B28'], 'trailer_id' => $trailers['AUB3489'], 'pdriver' => 'BRANDIN, FERNANDO', 'sdriver' => 'CABRERA, ANDREW'],
            ['tractor_id' => $tractors['B29'], 'trailer_id' => $trailers['AUB3224'], 'pdriver' => 'CANONIZADO, JAMES', 'sdriver' => 'CANONIZADO, JAN'],
            ['tractor_id' => $tractors['B30'], 'trailer_id' => $trailers['MV-FILE-11065'], 'pdriver' => 'CABRERA, JACOB', 'sdriver' => 'CANONIZADO, JIM'],
            ['tractor_id' => $tractors['B31'], 'trailer_id' => $trailers['8MDL-2019-0003-C'], 'pdriver' => 'PANDONG, CANDIDO', 'sdriver' => 'PANDONG, CAESAR'],
            ['tractor_id' => $tractors['B32'], 'trailer_id' => $trailers['AUA6655'], 'pdriver' => 'PANDONG, CARLITO', 'sdriver' => 'PANDONG, CAMILO'],
        ];

        // Insert tractor-trailer links with drivers
        DB::table('tractor_trailers')->insert($tractorTrailersData);

        DB::table('trailer_types')->insert([
            ['name' => 'Japan', 'is_active' => 1],
            ['name' => 'Bangpoo', 'is_active' => 1],
            ['name' => '8MDL', 'is_active' => 1],
            ['name' => 'Panda 2', 'is_active' => 1],
            ['name' => 'Panda 4', 'is_active' => 1],
        ]);

    }
}
