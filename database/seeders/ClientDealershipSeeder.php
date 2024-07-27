<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientDealershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //TOYOTA
        $client = DB::table('tms_clients')->where('name', 'TOYOTA')->first();
        $locations = DB::table('tms_locations')->pluck('id', 'name');
        $clientLocations = [
            ['name' => 'TOYOTA MOTOR PHILIPPINES', 'code' => 'TMP-SVC', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA MOTOR PHILIPPINES', 'code' => 'TMP-BVC', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA MOTOR PHILIPPINES', 'code' => 'TMP', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA ALBAY', 'code' => 'TAB', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA ANGELES PAMPANGA', 'code' => 'TAP', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA BAGUIO CITY', 'code' => 'TBG', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA BATAAN', 'code' => 'TBI', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA BACOOR', 'code' => 'TBR', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA CAMARINES SUR', 'code' => 'TCS', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA DAGUPAN CITY', 'code' => 'TDG', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA DASMARINAS', 'code' => 'TDM', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA ILOCOS NORTE', 'code' => 'TIN', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA LIPA', 'code' => 'TLB', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA LA UNION', 'code' => 'TLU', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA MARILAO', 'code' => 'TMR', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA NUEVA ECIJA', 'code' => 'TNI', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA PLARIDEL', 'code' => 'TPB', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SUBIC', 'code' => 'TSB', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SILANG', 'code' => 'TSC', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SAN FERNANDO', 'code' => 'TSF', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SANTIAGO', 'code' => 'TSG', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SAN PABLO', 'code' => 'TSP', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA TARLAC', 'code' => 'TTA', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA TUGUEGARAO', 'code' => 'TTG', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA TAYTAY RIZAL', 'code' => 'TTR', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA LUCENA', 'code' => 'TLC', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA BATANGAS CITY', 'code' => 'TBC', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA CALAMBA', 'code' => 'TCL', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SAN JOSE DELMONTE', 'code' => 'TSJ', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA SANTA ROSA', 'code' => 'TSR', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA VALENZUELA', 'code' => 'TVI', 'client_id' => $client->id, 'location_id' => $locations['LUZON'], 'is_active' => 1],
            ['name' => 'TOYOTA ALABANG', 'code' => 'TAI', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA ABAD SANTOS', 'code' => 'TAS', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA BICUTAN', 'code' => 'TBP', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA CUBAO', 'code' => 'TCI', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA COMMONWEALTH', 'code' => 'TCM', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA FAIRVIEW', 'code' => 'TFV', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA GLOBAL CITY', 'code' => 'TGC', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA MANILA BAY', 'code' => 'TMB', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA MAKATI', 'code' => 'TMI', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA MARIKINA', 'code' => 'TMS', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA PASONG TAMO', 'code' => 'TPT', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA QUEZON AVENUE', 'code' => 'TQA', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA PASIG', 'code' => 'TS2', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA SHAW', 'code' => 'TSI', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA OTIS', 'code' => 'TOT', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA NORTH EDSA', 'code' => 'TB2', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA BALINTAWAK', 'code' => 'TBK', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
            ['name' => 'TOYOTA AKLAN', 'code' => 'TAK', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA BUTUAN', 'code' => 'TBT', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA CEBU CITY', 'code' => 'TCB', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA CAGAYAN DE ORO', 'code' => 'TCO', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA CALAPAN', 'code' => 'TCA', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA DAVAO CITY', 'code' => 'TDC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA DUMAGUETE', 'code' => 'TDU', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA GENERAL SANTOS', 'code' => 'TGS', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA ILIGAN', 'code' => 'TIC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA ILOILO', 'code' => 'TIL', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA KIDAPAWAN CITY', 'code' => 'TKC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA LAPULAPU', 'code' => 'TLL', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA MATINA', 'code' => 'TMA', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA MANDAUE NORTH', 'code' => 'TMDN', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA MANDAUE SOUTH', 'code' => 'TMDS', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA MABOLO', 'code' => 'TML', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA NEGROS OCCIDENTAL', 'code' => 'TNO', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA ORMOC', 'code' => 'TOL', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA PUERTO PRINCESA', 'code' => 'TPP', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA ROXAS', 'code' => 'TRC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA TAGBILARAN', 'code' => 'TTB', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA TAGUM', 'code' => 'TTC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA TACLOBAN', 'code' => 'TTL', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA TALISAY', 'code' => 'TTY', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA VALENCIA', 'code' => 'TVC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA ZAMBOANGA', 'code' => 'TZC', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],
            ['name' => 'TOYOTA CALBAYOG', 'code' => 'TCY', 'client_id' => $client->id, 'location_id' => $locations['VISMIN'], 'is_active' => 1],

            ['name' => 'ATI', 'code' => 'ATI', 'client_id' => 3, 'location_id' => $locations['BTG PORT'], 'is_active' => 1],
            ['name' => 'BIPI', 'code' => 'BIPI', 'client_id' => 4, 'location_id' => $locations['BTG PORT'], 'is_active' => 1],
            ['name' => 'FUJI', 'code' => 'FUJI', 'client_id' => 5, 'location_id' => $locations['BTG PORT'], 'is_active' => 1],
            ['name' => '2GO', 'code' => '2GO', 'client_id' => 6, 'location_id' => $locations['BTG PORT'], 'is_active' => 1],

            ['name' => '2GO PIER2', 'code' => '2GO PIER2', 'client_id' => 6, 'location_id' => $locations['MNL PORT'], 'is_active' => 1],
            ['name' => 'PSACC CY7', 'code' => 'PSACC CY7', 'client_id' => 7, 'location_id' => $locations['MNL PORT'], 'is_active' => 1],
            ['name' => 'MERIDIAN', 'code' => 'MERIDIAN', 'client_id' => 8, 'location_id' => $locations['MNL PORT'], 'is_active' => 1],
            ['name' => 'MORETA CY2', 'code' => 'MORETA CY2', 'client_id' => 9, 'location_id' => $locations['MNL PORT'], 'is_active' => 1],
            ['name' => 'VITAS CY', 'code' => 'VITAS CY', 'client_id' => 10, 'location_id' => $locations['MNL PORT'], 'is_active' => 1],

        ];
        DB::table('tms_client_dealerships')->insert($clientLocations);

        // LEXUS
        $client = DB::table('tms_clients')->where('name', 'LEXUS')->first();
        $locations = DB::table('tms_locations')->pluck('id', 'name');
        $clientLocations = [
            ['name' => 'LEXUS MANILA', 'code' => 'LMI', 'client_id' => $client->id, 'location_id' => $locations['METRO MANILA'], 'is_active' => 1],
        ];
        DB::table('tms_client_dealerships')->insert($clientLocations);



    }
}
