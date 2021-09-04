<?php

use Illuminate\Database\Seeder;

class TblPaisesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_paises')->delete();
        
        \DB::table('tbl_paises')->insert(array (
            0 => 
            array (
                'id' => 1,
                'iso' => 'CO',
                'nombre_pais' => 'Colombia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            1 => 
            array (
                'id' => 2,
                'iso' => 'AX',
                'nombre_pais' => 'Islas Gland',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            2 => 
            array (
                'id' => 3,
                'iso' => 'AL',
                'nombre_pais' => 'Albania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            3 => 
            array (
                'id' => 4,
                'iso' => 'DE',
                'nombre_pais' => 'Alemania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            4 => 
            array (
                'id' => 5,
                'iso' => 'AD',
                'nombre_pais' => 'Andorra',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            5 => 
            array (
                'id' => 6,
                'iso' => 'AO',
                'nombre_pais' => 'Angola',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            6 => 
            array (
                'id' => 7,
                'iso' => 'AI',
                'nombre_pais' => 'Anguilla',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            7 => 
            array (
                'id' => 8,
                'iso' => 'AQ',
                'nombre_pais' => 'Antártida',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            8 => 
            array (
                'id' => 9,
                'iso' => 'AG',
                'nombre_pais' => 'Antigua y Barbuda',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            9 => 
            array (
                'id' => 10,
                'iso' => 'AN',
                'nombre_pais' => 'Antillas Holandesas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            10 => 
            array (
                'id' => 11,
                'iso' => 'SA',
                'nombre_pais' => 'Arabia Saudí',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            11 => 
            array (
                'id' => 12,
                'iso' => 'DZ',
                'nombre_pais' => 'Argelia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            12 => 
            array (
                'id' => 13,
                'iso' => 'AR',
                'nombre_pais' => 'Argentina',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            13 => 
            array (
                'id' => 14,
                'iso' => 'AM',
                'nombre_pais' => 'Armenia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            14 => 
            array (
                'id' => 15,
                'iso' => 'AW',
                'nombre_pais' => 'Aruba',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            15 => 
            array (
                'id' => 16,
                'iso' => 'AU',
                'nombre_pais' => 'Australia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            16 => 
            array (
                'id' => 17,
                'iso' => 'AT',
                'nombre_pais' => 'Austria',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            17 => 
            array (
                'id' => 18,
                'iso' => 'AZ',
                'nombre_pais' => 'Azerbaiyán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            18 => 
            array (
                'id' => 19,
                'iso' => 'BS',
                'nombre_pais' => 'Bahamas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            19 => 
            array (
                'id' => 20,
                'iso' => 'BH',
                'nombre_pais' => 'Bahréin',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            20 => 
            array (
                'id' => 21,
                'iso' => 'BD',
                'nombre_pais' => 'Bangladesh',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            21 => 
            array (
                'id' => 22,
                'iso' => 'BB',
                'nombre_pais' => 'Barbados',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            22 => 
            array (
                'id' => 23,
                'iso' => 'BY',
                'nombre_pais' => 'Bielorrusia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            23 => 
            array (
                'id' => 24,
                'iso' => 'BE',
                'nombre_pais' => 'Bélgica',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            24 => 
            array (
                'id' => 25,
                'iso' => 'BZ',
                'nombre_pais' => 'Belice',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            25 => 
            array (
                'id' => 26,
                'iso' => 'BJ',
                'nombre_pais' => 'Benin',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            26 => 
            array (
                'id' => 27,
                'iso' => 'BM',
                'nombre_pais' => 'Bermudas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            27 => 
            array (
                'id' => 28,
                'iso' => 'BT',
                'nombre_pais' => 'Bhután',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            28 => 
            array (
                'id' => 29,
                'iso' => 'BO',
                'nombre_pais' => 'Bolivia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            29 => 
            array (
                'id' => 30,
                'iso' => 'BA',
                'nombre_pais' => 'Bosnia y Herzegovina',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            30 => 
            array (
                'id' => 31,
                'iso' => 'BW',
                'nombre_pais' => 'Botsuana',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            31 => 
            array (
                'id' => 32,
                'iso' => 'BV',
                'nombre_pais' => 'Isla Bouvet',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            32 => 
            array (
                'id' => 33,
                'iso' => 'BR',
                'nombre_pais' => 'Brasil',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            33 => 
            array (
                'id' => 34,
                'iso' => 'BN',
                'nombre_pais' => 'Brunéi',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            34 => 
            array (
                'id' => 35,
                'iso' => 'BG',
                'nombre_pais' => 'Bulgaria',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            35 => 
            array (
                'id' => 36,
                'iso' => 'BF',
                'nombre_pais' => 'Burkina Faso',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            36 => 
            array (
                'id' => 37,
                'iso' => 'BI',
                'nombre_pais' => 'Burundi',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            37 => 
            array (
                'id' => 38,
                'iso' => 'CV',
                'nombre_pais' => 'Cabo Verde',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            38 => 
            array (
                'id' => 39,
                'iso' => 'KY',
                'nombre_pais' => 'Islas Caimán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            39 => 
            array (
                'id' => 40,
                'iso' => 'KH',
                'nombre_pais' => 'Camboya',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            40 => 
            array (
                'id' => 41,
                'iso' => 'CM',
                'nombre_pais' => 'Camerún',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            41 => 
            array (
                'id' => 42,
                'iso' => 'CA',
                'nombre_pais' => 'Canadá',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            42 => 
            array (
                'id' => 43,
                'iso' => 'CF',
                'nombre_pais' => 'República Centroafricana',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            43 => 
            array (
                'id' => 44,
                'iso' => 'TD',
                'nombre_pais' => 'Chad',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            44 => 
            array (
                'id' => 45,
                'iso' => 'CZ',
                'nombre_pais' => 'República Checa',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            45 => 
            array (
                'id' => 46,
                'iso' => 'CL',
                'nombre_pais' => 'Chile',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            46 => 
            array (
                'id' => 47,
                'iso' => 'CN',
                'nombre_pais' => 'China',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            47 => 
            array (
                'id' => 48,
                'iso' => 'CY',
                'nombre_pais' => 'Chipre',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            48 => 
            array (
                'id' => 49,
                'iso' => 'CX',
                'nombre_pais' => 'Isla de Navidad',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            49 => 
            array (
                'id' => 50,
                'iso' => 'VA',
                'nombre_pais' => 'Ciudad del Vaticano',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            50 => 
            array (
                'id' => 51,
                'iso' => 'CC',
                'nombre_pais' => 'Islas Cocos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            51 => 
            array (
                'id' => 52,
                'iso' => 'AF',
                'nombre_pais' => 'Afganistán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            52 => 
            array (
                'id' => 53,
                'iso' => 'KM',
                'nombre_pais' => 'Comoras',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            53 => 
            array (
                'id' => 54,
                'iso' => 'CD',
                'nombre_pais' => 'República Democrática del Congo',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            54 => 
            array (
                'id' => 55,
                'iso' => 'CG',
                'nombre_pais' => 'Congo',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            55 => 
            array (
                'id' => 56,
                'iso' => 'CK',
                'nombre_pais' => 'Islas Cook',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            56 => 
            array (
                'id' => 57,
                'iso' => 'KP',
                'nombre_pais' => 'Corea del Norte',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            57 => 
            array (
                'id' => 58,
                'iso' => 'KR',
                'nombre_pais' => 'Corea del Sur',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            58 => 
            array (
                'id' => 59,
                'iso' => 'CI',
                'nombre_pais' => 'Costa de Marfil',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            59 => 
            array (
                'id' => 60,
                'iso' => 'CR',
                'nombre_pais' => 'Costa Rica',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            60 => 
            array (
                'id' => 61,
                'iso' => 'HR',
                'nombre_pais' => 'Croacia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            61 => 
            array (
                'id' => 62,
                'iso' => 'CU',
                'nombre_pais' => 'Cuba',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            62 => 
            array (
                'id' => 63,
                'iso' => 'DK',
                'nombre_pais' => 'Dinamarca',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            63 => 
            array (
                'id' => 64,
                'iso' => 'DM',
                'nombre_pais' => 'Dominica',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            64 => 
            array (
                'id' => 65,
                'iso' => 'DO',
                'nombre_pais' => 'República Dominicana',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            65 => 
            array (
                'id' => 66,
                'iso' => 'EC',
                'nombre_pais' => 'Ecuador',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            66 => 
            array (
                'id' => 67,
                'iso' => 'EG',
                'nombre_pais' => 'Egipto',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            67 => 
            array (
                'id' => 68,
                'iso' => 'SV',
                'nombre_pais' => 'El Salvador',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            68 => 
            array (
                'id' => 69,
                'iso' => 'AE',
                'nombre_pais' => 'Emiratos Árabes Unidos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            69 => 
            array (
                'id' => 70,
                'iso' => 'ER',
                'nombre_pais' => 'Eritrea',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            70 => 
            array (
                'id' => 71,
                'iso' => 'SK',
                'nombre_pais' => 'Eslovaquia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            71 => 
            array (
                'id' => 72,
                'iso' => 'SI',
                'nombre_pais' => 'Eslovenia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            72 => 
            array (
                'id' => 73,
                'iso' => 'ES',
                'nombre_pais' => 'España',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            73 => 
            array (
                'id' => 74,
                'iso' => 'UM',
                'nombre_pais' => 'Islas ultramarinas de Estados Unidos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            74 => 
            array (
                'id' => 75,
                'iso' => 'US',
                'nombre_pais' => 'Estados Unidos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            75 => 
            array (
                'id' => 76,
                'iso' => 'EE',
                'nombre_pais' => 'Estonia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            76 => 
            array (
                'id' => 77,
                'iso' => 'ET',
                'nombre_pais' => 'Etiopía',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            77 => 
            array (
                'id' => 78,
                'iso' => 'FO',
                'nombre_pais' => 'Islas Feroe',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            78 => 
            array (
                'id' => 79,
                'iso' => 'PH',
                'nombre_pais' => 'Filipinas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            79 => 
            array (
                'id' => 80,
                'iso' => 'FI',
                'nombre_pais' => 'Finlandia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            80 => 
            array (
                'id' => 81,
                'iso' => 'FJ',
                'nombre_pais' => 'Fiyi',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            81 => 
            array (
                'id' => 82,
                'iso' => 'FR',
                'nombre_pais' => 'Francia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            82 => 
            array (
                'id' => 83,
                'iso' => 'GA',
                'nombre_pais' => 'Gabón',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            83 => 
            array (
                'id' => 84,
                'iso' => 'GM',
                'nombre_pais' => 'Gambia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            84 => 
            array (
                'id' => 85,
                'iso' => 'GE',
                'nombre_pais' => 'Georgia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            85 => 
            array (
                'id' => 86,
                'iso' => 'GS',
                'nombre_pais' => 'Islas Georgias del Sur y Sandwich del Sur',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            86 => 
            array (
                'id' => 87,
                'iso' => 'GH',
                'nombre_pais' => 'Ghana',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            87 => 
            array (
                'id' => 88,
                'iso' => 'GI',
                'nombre_pais' => 'Gibraltar',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            88 => 
            array (
                'id' => 89,
                'iso' => 'GD',
                'nombre_pais' => 'Granada',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            89 => 
            array (
                'id' => 90,
                'iso' => 'GR',
                'nombre_pais' => 'Grecia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            90 => 
            array (
                'id' => 91,
                'iso' => 'GL',
                'nombre_pais' => 'Groenlandia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            91 => 
            array (
                'id' => 92,
                'iso' => 'GP',
                'nombre_pais' => 'Guadalupe',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            92 => 
            array (
                'id' => 93,
                'iso' => 'GU',
                'nombre_pais' => 'Guam',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            93 => 
            array (
                'id' => 94,
                'iso' => 'GT',
                'nombre_pais' => 'Guatemala',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            94 => 
            array (
                'id' => 95,
                'iso' => 'GF',
                'nombre_pais' => 'Guayana Francesa',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            95 => 
            array (
                'id' => 96,
                'iso' => 'GN',
                'nombre_pais' => 'Guinea',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            96 => 
            array (
                'id' => 97,
                'iso' => 'GQ',
                'nombre_pais' => 'Guinea Ecuatorial',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            97 => 
            array (
                'id' => 98,
                'iso' => 'GW',
                'nombre_pais' => 'Guinea-Bissau',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            98 => 
            array (
                'id' => 99,
                'iso' => 'GY',
                'nombre_pais' => 'Guyana',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            99 => 
            array (
                'id' => 100,
                'iso' => 'HT',
                'nombre_pais' => 'Haití',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            100 => 
            array (
                'id' => 101,
                'iso' => 'HM',
                'nombre_pais' => 'Islas Heard y McDonald',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            101 => 
            array (
                'id' => 102,
                'iso' => 'HN',
                'nombre_pais' => 'Honduras',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            102 => 
            array (
                'id' => 103,
                'iso' => 'HK',
                'nombre_pais' => 'Hong Kong',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            103 => 
            array (
                'id' => 104,
                'iso' => 'HU',
                'nombre_pais' => 'Hungría',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            104 => 
            array (
                'id' => 105,
                'iso' => 'IN',
                'nombre_pais' => 'India',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            105 => 
            array (
                'id' => 106,
                'iso' => 'ID',
                'nombre_pais' => 'Indonesia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            106 => 
            array (
                'id' => 107,
                'iso' => 'IR',
                'nombre_pais' => 'Irán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            107 => 
            array (
                'id' => 108,
                'iso' => 'IQ',
                'nombre_pais' => 'Iraq',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            108 => 
            array (
                'id' => 109,
                'iso' => 'IE',
                'nombre_pais' => 'Irlanda',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            109 => 
            array (
                'id' => 110,
                'iso' => 'IS',
                'nombre_pais' => 'Islandia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            110 => 
            array (
                'id' => 111,
                'iso' => 'IL',
                'nombre_pais' => 'Israel',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            111 => 
            array (
                'id' => 112,
                'iso' => 'IT',
                'nombre_pais' => 'Italia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            112 => 
            array (
                'id' => 113,
                'iso' => 'JM',
                'nombre_pais' => 'Jamaica',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            113 => 
            array (
                'id' => 114,
                'iso' => 'JP',
                'nombre_pais' => 'Japón',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            114 => 
            array (
                'id' => 115,
                'iso' => 'JO',
                'nombre_pais' => 'Jordania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            115 => 
            array (
                'id' => 116,
                'iso' => 'KZ',
                'nombre_pais' => 'Kazajstán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            116 => 
            array (
                'id' => 117,
                'iso' => 'KE',
                'nombre_pais' => 'Kenia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            117 => 
            array (
                'id' => 118,
                'iso' => 'KG',
                'nombre_pais' => 'Kirguistán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            118 => 
            array (
                'id' => 119,
                'iso' => 'KI',
                'nombre_pais' => 'Kiribati',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            119 => 
            array (
                'id' => 120,
                'iso' => 'KW',
                'nombre_pais' => 'Kuwait',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            120 => 
            array (
                'id' => 121,
                'iso' => 'LA',
                'nombre_pais' => 'Laos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            121 => 
            array (
                'id' => 122,
                'iso' => 'LS',
                'nombre_pais' => 'Lesotho',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            122 => 
            array (
                'id' => 123,
                'iso' => 'LV',
                'nombre_pais' => 'Letonia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            123 => 
            array (
                'id' => 124,
                'iso' => 'LB',
                'nombre_pais' => 'Líbano',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            124 => 
            array (
                'id' => 125,
                'iso' => 'LR',
                'nombre_pais' => 'Liberia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            125 => 
            array (
                'id' => 126,
                'iso' => 'LY',
                'nombre_pais' => 'Libia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            126 => 
            array (
                'id' => 127,
                'iso' => 'LI',
                'nombre_pais' => 'Liechtenstein',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            127 => 
            array (
                'id' => 128,
                'iso' => 'LT',
                'nombre_pais' => 'Lituania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            128 => 
            array (
                'id' => 129,
                'iso' => 'LU',
                'nombre_pais' => 'Luxemburgo',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            129 => 
            array (
                'id' => 130,
                'iso' => 'MO',
                'nombre_pais' => 'Macao',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            130 => 
            array (
                'id' => 131,
                'iso' => 'MK',
                'nombre_pais' => 'ARY Macedonia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            131 => 
            array (
                'id' => 132,
                'iso' => 'MG',
                'nombre_pais' => 'Madagascar',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            132 => 
            array (
                'id' => 133,
                'iso' => 'MY',
                'nombre_pais' => 'Malasia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            133 => 
            array (
                'id' => 134,
                'iso' => 'MW',
                'nombre_pais' => 'Malawi',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            134 => 
            array (
                'id' => 135,
                'iso' => 'MV',
                'nombre_pais' => 'Maldivas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            135 => 
            array (
                'id' => 136,
                'iso' => 'ML',
                'nombre_pais' => 'Malí',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            136 => 
            array (
                'id' => 137,
                'iso' => 'MT',
                'nombre_pais' => 'Malta',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            137 => 
            array (
                'id' => 138,
                'iso' => 'FK',
                'nombre_pais' => 'Islas Malvinas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            138 => 
            array (
                'id' => 139,
                'iso' => 'MP',
                'nombre_pais' => 'Islas Marianas del Norte',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            139 => 
            array (
                'id' => 140,
                'iso' => 'MA',
                'nombre_pais' => 'Marruecos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            140 => 
            array (
                'id' => 141,
                'iso' => 'MH',
                'nombre_pais' => 'Islas Marshall',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            141 => 
            array (
                'id' => 142,
                'iso' => 'MQ',
                'nombre_pais' => 'Martinica',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            142 => 
            array (
                'id' => 143,
                'iso' => 'MU',
                'nombre_pais' => 'Mauricio',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            143 => 
            array (
                'id' => 144,
                'iso' => 'MR',
                'nombre_pais' => 'Mauritania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            144 => 
            array (
                'id' => 145,
                'iso' => 'YT',
                'nombre_pais' => 'Mayotte',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            145 => 
            array (
                'id' => 146,
                'iso' => 'MX',
                'nombre_pais' => 'México',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            146 => 
            array (
                'id' => 147,
                'iso' => 'FM',
                'nombre_pais' => 'Micronesia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            147 => 
            array (
                'id' => 148,
                'iso' => 'MD',
                'nombre_pais' => 'Moldavia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            148 => 
            array (
                'id' => 149,
                'iso' => 'MC',
                'nombre_pais' => 'Mónaco',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            149 => 
            array (
                'id' => 150,
                'iso' => 'MN',
                'nombre_pais' => 'Mongolia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            150 => 
            array (
                'id' => 151,
                'iso' => 'MS',
                'nombre_pais' => 'Montserrat',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            151 => 
            array (
                'id' => 152,
                'iso' => 'MZ',
                'nombre_pais' => 'Mozambique',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            152 => 
            array (
                'id' => 153,
                'iso' => 'MM',
                'nombre_pais' => 'Myanmar',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            153 => 
            array (
                'id' => 154,
                'iso' => 'NA',
                'nombre_pais' => 'Namibia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            154 => 
            array (
                'id' => 155,
                'iso' => 'NR',
                'nombre_pais' => 'Nauru',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            155 => 
            array (
                'id' => 156,
                'iso' => 'NP',
                'nombre_pais' => 'Nepal',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            156 => 
            array (
                'id' => 157,
                'iso' => 'NI',
                'nombre_pais' => 'Nicaragua',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            157 => 
            array (
                'id' => 158,
                'iso' => 'NE',
                'nombre_pais' => 'Níger',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            158 => 
            array (
                'id' => 159,
                'iso' => 'NG',
                'nombre_pais' => 'Nigeria',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            159 => 
            array (
                'id' => 160,
                'iso' => 'NU',
                'nombre_pais' => 'Niue',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            160 => 
            array (
                'id' => 161,
                'iso' => 'NF',
                'nombre_pais' => 'Isla Norfolk',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            161 => 
            array (
                'id' => 162,
                'iso' => 'NO',
                'nombre_pais' => 'Noruega',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            162 => 
            array (
                'id' => 163,
                'iso' => 'NC',
                'nombre_pais' => 'Nueva Caledonia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            163 => 
            array (
                'id' => 164,
                'iso' => 'NZ',
                'nombre_pais' => 'Nueva Zelanda',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            164 => 
            array (
                'id' => 165,
                'iso' => 'OM',
                'nombre_pais' => 'Omán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            165 => 
            array (
                'id' => 166,
                'iso' => 'NL',
                'nombre_pais' => 'Países Bajos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            166 => 
            array (
                'id' => 167,
                'iso' => 'PK',
                'nombre_pais' => 'Pakistán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            167 => 
            array (
                'id' => 168,
                'iso' => 'PW',
                'nombre_pais' => 'Palau',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            168 => 
            array (
                'id' => 169,
                'iso' => 'PS',
                'nombre_pais' => 'Palestina',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            169 => 
            array (
                'id' => 170,
                'iso' => 'PA',
                'nombre_pais' => 'Panamá',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            170 => 
            array (
                'id' => 171,
                'iso' => 'PG',
                'nombre_pais' => 'Papúa Nueva Guinea',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            171 => 
            array (
                'id' => 172,
                'iso' => 'PY',
                'nombre_pais' => 'Paraguay',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            172 => 
            array (
                'id' => 173,
                'iso' => 'PE',
                'nombre_pais' => 'Perú',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            173 => 
            array (
                'id' => 174,
                'iso' => 'PN',
                'nombre_pais' => 'Islas Pitcairn',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            174 => 
            array (
                'id' => 175,
                'iso' => 'PF',
                'nombre_pais' => 'Polinesia Francesa',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            175 => 
            array (
                'id' => 176,
                'iso' => 'PL',
                'nombre_pais' => 'Polonia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            176 => 
            array (
                'id' => 177,
                'iso' => 'PT',
                'nombre_pais' => 'Portugal',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            177 => 
            array (
                'id' => 178,
                'iso' => 'PR',
                'nombre_pais' => 'Puerto Rico',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            178 => 
            array (
                'id' => 179,
                'iso' => 'QA',
                'nombre_pais' => 'Qatar',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            179 => 
            array (
                'id' => 180,
                'iso' => 'GB',
                'nombre_pais' => 'Reino Unido',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            180 => 
            array (
                'id' => 181,
                'iso' => 'RE',
                'nombre_pais' => 'Reunión',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            181 => 
            array (
                'id' => 182,
                'iso' => 'RW',
                'nombre_pais' => 'Ruanda',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            182 => 
            array (
                'id' => 183,
                'iso' => 'RO',
                'nombre_pais' => 'Rumania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            183 => 
            array (
                'id' => 184,
                'iso' => 'RU',
                'nombre_pais' => 'Rusia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            184 => 
            array (
                'id' => 185,
                'iso' => 'EH',
                'nombre_pais' => 'Sahara Occidental',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            185 => 
            array (
                'id' => 186,
                'iso' => 'SB',
                'nombre_pais' => 'Islas Salomón',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            186 => 
            array (
                'id' => 187,
                'iso' => 'WS',
                'nombre_pais' => 'Samoa',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            187 => 
            array (
                'id' => 188,
                'iso' => 'AS',
                'nombre_pais' => 'Samoa Americana',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            188 => 
            array (
                'id' => 189,
                'iso' => 'KN',
                'nombre_pais' => 'San Cristóbal y Nevis',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            189 => 
            array (
                'id' => 190,
                'iso' => 'SM',
                'nombre_pais' => 'San Marino',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            190 => 
            array (
                'id' => 191,
                'iso' => 'PM',
                'nombre_pais' => 'San Pedro y Miquelón',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            191 => 
            array (
                'id' => 192,
                'iso' => 'VC',
                'nombre_pais' => 'San Vicente y las Granadinas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            192 => 
            array (
                'id' => 193,
                'iso' => 'SH',
                'nombre_pais' => 'Santa Helena',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            193 => 
            array (
                'id' => 194,
                'iso' => 'LC',
                'nombre_pais' => 'Santa Lucía',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            194 => 
            array (
                'id' => 195,
                'iso' => 'ST',
                'nombre_pais' => 'Santo Tomé y Príncipe',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            195 => 
            array (
                'id' => 196,
                'iso' => 'SN',
                'nombre_pais' => 'Senegal',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            196 => 
            array (
                'id' => 197,
                'iso' => 'CS',
                'nombre_pais' => 'Serbia y Montenegro',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            197 => 
            array (
                'id' => 198,
                'iso' => 'SC',
                'nombre_pais' => 'Seychelles',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            198 => 
            array (
                'id' => 199,
                'iso' => 'SL',
                'nombre_pais' => 'Sierra Leona',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            199 => 
            array (
                'id' => 200,
                'iso' => 'SG',
                'nombre_pais' => 'Singapur',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            200 => 
            array (
                'id' => 201,
                'iso' => 'SY',
                'nombre_pais' => 'Siria',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            201 => 
            array (
                'id' => 202,
                'iso' => 'SO',
                'nombre_pais' => 'Somalia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            202 => 
            array (
                'id' => 203,
                'iso' => 'LK',
                'nombre_pais' => 'Sri Lanka',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            203 => 
            array (
                'id' => 204,
                'iso' => 'SZ',
                'nombre_pais' => 'Suazilandia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            204 => 
            array (
                'id' => 205,
                'iso' => 'ZA',
                'nombre_pais' => 'Sudáfrica',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            205 => 
            array (
                'id' => 206,
                'iso' => 'SD',
                'nombre_pais' => 'Sudán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            206 => 
            array (
                'id' => 207,
                'iso' => 'SE',
                'nombre_pais' => 'Suecia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            207 => 
            array (
                'id' => 208,
                'iso' => 'CH',
                'nombre_pais' => 'Suiza',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            208 => 
            array (
                'id' => 209,
                'iso' => 'SR',
                'nombre_pais' => 'Surinam',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            209 => 
            array (
                'id' => 210,
                'iso' => 'SJ',
                'nombre_pais' => 'Svalbard y Jan Mayen',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            210 => 
            array (
                'id' => 211,
                'iso' => 'TH',
                'nombre_pais' => 'Tailandia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            211 => 
            array (
                'id' => 212,
                'iso' => 'TW',
                'nombre_pais' => 'Taiwán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            212 => 
            array (
                'id' => 213,
                'iso' => 'TZ',
                'nombre_pais' => 'Tanzania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            213 => 
            array (
                'id' => 214,
                'iso' => 'TJ',
                'nombre_pais' => 'Tayikistán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            214 => 
            array (
                'id' => 215,
                'iso' => 'IO',
                'nombre_pais' => 'Territorio Británico del Océano Índico',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            215 => 
            array (
                'id' => 216,
                'iso' => 'TF',
                'nombre_pais' => 'Territorios Australes Franceses',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            216 => 
            array (
                'id' => 217,
                'iso' => 'TL',
                'nombre_pais' => 'Timor Oriental',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            217 => 
            array (
                'id' => 218,
                'iso' => 'TG',
                'nombre_pais' => 'Togo',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            218 => 
            array (
                'id' => 219,
                'iso' => 'TK',
                'nombre_pais' => 'Tokelau',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            219 => 
            array (
                'id' => 220,
                'iso' => 'TO',
                'nombre_pais' => 'Tonga',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            220 => 
            array (
                'id' => 221,
                'iso' => 'TT',
                'nombre_pais' => 'Trinidad y Tobago',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            221 => 
            array (
                'id' => 222,
                'iso' => 'TN',
                'nombre_pais' => 'Túnez',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            222 => 
            array (
                'id' => 223,
                'iso' => 'TC',
                'nombre_pais' => 'Islas Turcas y Caicos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            223 => 
            array (
                'id' => 224,
                'iso' => 'TM',
                'nombre_pais' => 'Turkmenistán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            224 => 
            array (
                'id' => 225,
                'iso' => 'TR',
                'nombre_pais' => 'Turquía',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            225 => 
            array (
                'id' => 226,
                'iso' => 'TV',
                'nombre_pais' => 'Tuvalu',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            226 => 
            array (
                'id' => 227,
                'iso' => 'UA',
                'nombre_pais' => 'Ucrania',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            227 => 
            array (
                'id' => 228,
                'iso' => 'UG',
                'nombre_pais' => 'Uganda',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            228 => 
            array (
                'id' => 229,
                'iso' => 'UY',
                'nombre_pais' => 'Uruguay',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            229 => 
            array (
                'id' => 230,
                'iso' => 'UZ',
                'nombre_pais' => 'Uzbekistán',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            230 => 
            array (
                'id' => 231,
                'iso' => 'VU',
                'nombre_pais' => 'Vanuatu',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            231 => 
            array (
                'id' => 232,
                'iso' => 'VE',
                'nombre_pais' => 'Venezuela',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            232 => 
            array (
                'id' => 233,
                'iso' => 'VN',
                'nombre_pais' => 'Vietnam',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            233 => 
            array (
                'id' => 234,
                'iso' => 'VG',
                'nombre_pais' => 'Islas Vírgenes Británicas',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            234 => 
            array (
                'id' => 235,
                'iso' => 'VI',
                'nombre_pais' => 'Islas Vírgenes de los Estados Unidos',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            235 => 
            array (
                'id' => 236,
                'iso' => 'WF',
                'nombre_pais' => 'Wallis y Futuna',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            236 => 
            array (
                'id' => 237,
                'iso' => 'YE',
                'nombre_pais' => 'Yemen',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            237 => 
            array (
                'id' => 238,
                'iso' => 'DJ',
                'nombre_pais' => 'Yibuti',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            238 => 
            array (
                'id' => 239,
                'iso' => 'ZM',
                'nombre_pais' => 'Zambia',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
            239 => 
            array (
                'id' => 240,
                'iso' => 'ZW',
                'nombre_pais' => 'Zimbabue',
                'created_at' => '2018-11-07 11:07:50',
                'updated_at' => '2018-11-07 11:07:50',
            ),
        ));
        
        
    }
}