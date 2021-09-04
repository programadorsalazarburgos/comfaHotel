<?php

use Illuminate\Database\Seeder;

class TblMonedasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_monedas')->delete();
        
        \DB::table('tbl_monedas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Dirham de los Emiratos Árabes Unidos',
                'codigo_iso' => 'AED',
                'valor_usd' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Afgani afgano',
                'codigo_iso' => 'AFN',
                'valor_usd' => NULL,
                
                
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Lek albanés',
                'codigo_iso' => 'ALL',
                'valor_usd' => NULL,
                
                
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Dram armenio',
                'codigo_iso' => 'AMD',
                'valor_usd' => NULL,
                
                
            ),
            4 => 
            array (
                'id' => 5,
                'nombre' => 'Kwanza angoleño',
                'codigo_iso' => 'AOA',
                'valor_usd' => NULL,
                
                
            ),
            5 => 
            array (
                'id' => 6,
                'nombre' => 'Peso',
                'codigo_iso' => 'ARS',
                'valor_usd' => NULL,
                
                
            ),
            6 => 
            array (
                'id' => 7,
                'nombre' => 'Dólar australiano',
                'codigo_iso' => 'AUD',
                'valor_usd' => NULL,
                
                
            ),
            7 => 
            array (
                'id' => 8,
                'nombre' => 'Manat azerí',
                'codigo_iso' => 'AZN',
                'valor_usd' => NULL,
                
                
            ),
            8 => 
            array (
                'id' => 9,
                'nombre' => 'Marco convertible',
                'codigo_iso' => 'BAM',
                'valor_usd' => NULL,
                
                
            ),
            9 => 
            array (
                'id' => 10,
                'nombre' => 'Dólar de Barbados',
                'codigo_iso' => 'BBD',
                'valor_usd' => NULL,
                
                
            ),
            10 => 
            array (
                'id' => 11,
                'nombre' => 'Taka bangladeshí',
                'codigo_iso' => 'BDT',
                'valor_usd' => NULL,
                
                
            ),
            11 => 
            array (
                'id' => 12,
                'nombre' => 'Lev búlgaro',
                'codigo_iso' => 'BGN',
                'valor_usd' => NULL,
                
                
            ),
            12 => 
            array (
                'id' => 13,
                'nombre' => 'Dinar bahreiní',
                'codigo_iso' => 'BHD',
                'valor_usd' => NULL,
                
                
            ),
            13 => 
            array (
                'id' => 14,
                'nombre' => 'Franco de Burundi',
                'codigo_iso' => 'BIF',
                'valor_usd' => NULL,
                
                
            ),
            14 => 
            array (
                'id' => 15,
                'nombre' => 'Dólar de Brunéi',
                'codigo_iso' => 'BND',
                'valor_usd' => NULL,
                
                
            ),
            15 => 
            array (
                'id' => 16,
                'nombre' => 'Boliviano',
                'codigo_iso' => 'BOB',
                'valor_usd' => NULL,
                
                
            ),
            16 => 
            array (
                'id' => 17,
                'nombre' => 'Real brasileño',
                'codigo_iso' => 'BRL',
                'valor_usd' => NULL,
                
                
            ),
            17 => 
            array (
                'id' => 18,
                'nombre' => 'Dólar bahameño',
                'codigo_iso' => 'BSD',
                'valor_usd' => NULL,
                
                
            ),
            18 => 
            array (
                'id' => 19,
                'nombre' => 'Ngultrum butanés',
                'codigo_iso' => 'BTN',
                'valor_usd' => NULL,
                
                
            ),
            19 => 
            array (
                'id' => 20,
                'nombre' => 'Pula',
                'codigo_iso' => 'BWP',
                'valor_usd' => NULL,
                
                
            ),
            20 => 
            array (
                'id' => 21,
                'nombre' => 'Rublo bielorruso',
                'codigo_iso' => 'BYN',
                'valor_usd' => NULL,
                
                
            ),
            21 => 
            array (
                'id' => 22,
                'nombre' => 'Dólar beliceño',
                'codigo_iso' => 'BZD',
                'valor_usd' => NULL,
                
                
            ),
            22 => 
            array (
                'id' => 23,
                'nombre' => 'Dólar canadiense',
                'codigo_iso' => 'CAD',
                'valor_usd' => NULL,
                
                
            ),
            23 => 
            array (
                'id' => 24,
                'nombre' => 'Franco congoleño',
                'codigo_iso' => 'CDF',
                'valor_usd' => NULL,
                
                
            ),
            24 => 
            array (
                'id' => 25,
                'nombre' => 'Franco suizo',
                'codigo_iso' => 'CHF',
                'valor_usd' => NULL,
                
                
            ),
            25 => 
            array (
                'id' => 26,
                'nombre' => 'Peso chileno',
                'codigo_iso' => 'CLP',
                'valor_usd' => NULL,
                
                
            ),
            26 => 
            array (
                'id' => 27,
                'nombre' => 'Yuan chino',
                'codigo_iso' => 'CNY',
                'valor_usd' => NULL,
                
                
            ),
            27 => 
            array (
                'id' => 28,
                'nombre' => 'Peso colombiano',
                'codigo_iso' => 'COP',
                'valor_usd' => NULL,
                
                
            ),
            28 => 
            array (
                'id' => 29,
                'nombre' => 'Colón costarricense',
                'codigo_iso' => 'CRC',
                'valor_usd' => NULL,
                
                
            ),
            29 => 
            array (
                'id' => 30,
                'nombre' => 'Peso cubano',
                'codigo_iso' => 'CUP',
                'valor_usd' => NULL,
                
                
            ),
            30 => 
            array (
                'id' => 31,
                'nombre' => 'Escudo caboverdiano',
                'codigo_iso' => 'CVE',
                'valor_usd' => NULL,
                
                
            ),
            31 => 
            array (
                'id' => 32,
                'nombre' => 'Corona checa',
                'codigo_iso' => 'CZK',
                'valor_usd' => NULL,
                
                
            ),
            32 => 
            array (
                'id' => 33,
                'nombre' => 'Franco yibutiano',
                'codigo_iso' => 'DJF',
                'valor_usd' => NULL,
                
                
            ),
            33 => 
            array (
                'id' => 34,
                'nombre' => 'Corona danesa',
                'codigo_iso' => 'DKK',
                'valor_usd' => NULL,
                
                
            ),
            34 => 
            array (
                'id' => 35,
                'nombre' => 'Peso dominicano',
                'codigo_iso' => 'DOP',
                'valor_usd' => NULL,
                
                
            ),
            35 => 
            array (
                'id' => 36,
                'nombre' => 'Dinar argelino',
                'codigo_iso' => 'DZD',
                'valor_usd' => NULL,
                
                
            ),
            36 => 
            array (
                'id' => 37,
                'nombre' => 'Libra egipcia',
                'codigo_iso' => 'EGP',
                'valor_usd' => NULL,
                
                
            ),
            37 => 
            array (
                'id' => 38,
                'nombre' => 'Nakfa',
                'codigo_iso' => 'ERN',
                'valor_usd' => NULL,
                
                
            ),
            38 => 
            array (
                'id' => 39,
                'nombre' => 'Birr etíope',
                'codigo_iso' => 'ETB',
                'valor_usd' => NULL,
                
                
            ),
            39 => 
            array (
                'id' => 40,
                'nombre' => 'Euro',
                'codigo_iso' => 'EUR',
                'valor_usd' => NULL,
                
                
            ),
            40 => 
            array (
                'id' => 41,
                'nombre' => 'Dólar fiyiano',
                'codigo_iso' => 'FJD',
                'valor_usd' => NULL,
                
                
            ),
            41 => 
            array (
                'id' => 42,
                'nombre' => 'Libra británica',
                'codigo_iso' => 'GBP',
                'valor_usd' => NULL,
                
                
            ),
            42 => 
            array (
                'id' => 43,
                'nombre' => 'Lari georgiano',
                'codigo_iso' => 'GEL',
                'valor_usd' => NULL,
                
                
            ),
            43 => 
            array (
                'id' => 44,
                'nombre' => 'Cedi',
                'codigo_iso' => 'GHS',
                'valor_usd' => NULL,
                
                
            ),
            44 => 
            array (
                'id' => 45,
                'nombre' => 'Dalasi',
                'codigo_iso' => 'GMD',
                'valor_usd' => NULL,
                
                
            ),
            45 => 
            array (
                'id' => 46,
                'nombre' => 'Franco guineano',
                'codigo_iso' => 'GNF',
                'valor_usd' => NULL,
                
                
            ),
            46 => 
            array (
                'id' => 47,
                'nombre' => 'Quetzal guatemalteco',
                'codigo_iso' => 'GTQ',
                'valor_usd' => NULL,
                
                
            ),
            47 => 
            array (
                'id' => 48,
                'nombre' => 'Dólar guyanés',
                'codigo_iso' => 'GYD',
                'valor_usd' => NULL,
                
                
            ),
            48 => 
            array (
                'id' => 49,
                'nombre' => 'Lempira hondureño',
                'codigo_iso' => 'HNL',
                'valor_usd' => NULL,
                
                
            ),
            49 => 
            array (
                'id' => 50,
                'nombre' => 'Kuna croata',
                'codigo_iso' => 'HRK',
                'valor_usd' => NULL,
                
                
            ),
            50 => 
            array (
                'id' => 51,
                'nombre' => 'Gourde haitiano',
                'codigo_iso' => 'HTG',
                'valor_usd' => NULL,
                
                
            ),
            51 => 
            array (
                'id' => 52,
                'nombre' => 'Forinto húngaro',
                'codigo_iso' => 'HUF',
                'valor_usd' => NULL,
                
                
            ),
            52 => 
            array (
                'id' => 53,
                'nombre' => 'Rupia indonesia',
                'codigo_iso' => 'IDR',
                'valor_usd' => NULL,
                
                
            ),
            53 => 
            array (
                'id' => 54,
                'nombre' => 'Nuevo shéquel',
                'codigo_iso' => 'ILS',
                'valor_usd' => NULL,
                
                
            ),
            54 => 
            array (
                'id' => 55,
                'nombre' => 'Rupia india',
                'codigo_iso' => 'INR',
                'valor_usd' => NULL,
                
                
            ),
            55 => 
            array (
                'id' => 56,
                'nombre' => 'Dinar iraquí',
                'codigo_iso' => 'IQD',
                'valor_usd' => NULL,
                
                
            ),
            56 => 
            array (
                'id' => 57,
                'nombre' => 'Rial iraní',
                'codigo_iso' => 'IRR',
                'valor_usd' => NULL,
                
                
            ),
            57 => 
            array (
                'id' => 58,
                'nombre' => 'Corona islandes',
                'codigo_iso' => 'ISK',
                'valor_usd' => NULL,
                
                
            ),
            58 => 
            array (
                'id' => 59,
                'nombre' => 'Dólar jamaiquino',
                'codigo_iso' => 'JMD',
                'valor_usd' => NULL,
                
                
            ),
            59 => 
            array (
                'id' => 60,
                'nombre' => 'Dinar jordano',
                'codigo_iso' => 'JOD',
                'valor_usd' => NULL,
                
                
            ),
            60 => 
            array (
                'id' => 61,
                'nombre' => 'Yen',
                'codigo_iso' => 'JPY',
                'valor_usd' => NULL,
                
                
            ),
            61 => 
            array (
                'id' => 62,
                'nombre' => 'Chelín keniano',
                'codigo_iso' => 'KES',
                'valor_usd' => NULL,
                
                
            ),
            62 => 
            array (
                'id' => 63,
                'nombre' => 'Som kirguís',
                'codigo_iso' => 'KGS',
                'valor_usd' => NULL,
                
                
            ),
            63 => 
            array (
                'id' => 64,
                'nombre' => 'Riel camboyano',
                'codigo_iso' => 'KHR',
                'valor_usd' => NULL,
                
                
            ),
            64 => 
            array (
                'id' => 65,
                'nombre' => 'Franco comorano',
                'codigo_iso' => 'KMF',
                'valor_usd' => NULL,
                
                
            ),
            65 => 
            array (
                'id' => 66,
                'nombre' => 'Won norcoreano',
                'codigo_iso' => 'KPW',
                'valor_usd' => NULL,
                
                
            ),
            66 => 
            array (
                'id' => 67,
                'nombre' => 'Won surcoreano',
                'codigo_iso' => 'KRW',
                'valor_usd' => NULL,
                
                
            ),
            67 => 
            array (
                'id' => 68,
                'nombre' => 'Dinar kuwaití',
                'codigo_iso' => 'KWD',
                'valor_usd' => NULL,
                
                
            ),
            68 => 
            array (
                'id' => 69,
                'nombre' => 'Tenge kazajo',
                'codigo_iso' => 'KZT',
                'valor_usd' => NULL,
                
                
            ),
            69 => 
            array (
                'id' => 70,
                'nombre' => 'Kip laosiano',
                'codigo_iso' => 'LAK',
                'valor_usd' => NULL,
                
                
            ),
            70 => 
            array (
                'id' => 71,
                'nombre' => 'Libra libanesa',
                'codigo_iso' => 'LBP',
                'valor_usd' => NULL,
                
                
            ),
            71 => 
            array (
                'id' => 72,
                'nombre' => 'Rupia de Sri Lanka',
                'codigo_iso' => 'LKR',
                'valor_usd' => NULL,
                
                
            ),
            72 => 
            array (
                'id' => 73,
                'nombre' => 'Dólar liberiano',
                'codigo_iso' => 'LRD',
                'valor_usd' => NULL,
                
                
            ),
            73 => 
            array (
                'id' => 74,
                'nombre' => 'Loti',
                'codigo_iso' => 'LSL',
                'valor_usd' => NULL,
                
                
            ),
            74 => 
            array (
                'id' => 75,
                'nombre' => 'Dinar libio',
                'codigo_iso' => 'LYD',
                'valor_usd' => NULL,
                
                
            ),
            75 => 
            array (
                'id' => 76,
                'nombre' => 'Dirham marroquí',
                'codigo_iso' => 'MAD',
                'valor_usd' => NULL,
                
                
            ),
            76 => 
            array (
                'id' => 77,
                'nombre' => 'Leu moldavo',
                'codigo_iso' => 'MDL',
                'valor_usd' => NULL,
                
                
            ),
            77 => 
            array (
                'id' => 78,
                'nombre' => 'Ariary malgache',
                'codigo_iso' => 'MGA',
                'valor_usd' => NULL,
                
                
            ),
            78 => 
            array (
                'id' => 79,
                'nombre' => 'Denar macedonio',
                'codigo_iso' => 'MKD',
                'valor_usd' => NULL,
                
                
            ),
            79 => 
            array (
                'id' => 80,
                'nombre' => 'Kyat birmano',
                'codigo_iso' => 'MMK',
                'valor_usd' => NULL,
                
                
            ),
            80 => 
            array (
                'id' => 81,
                'nombre' => 'Tugrik mongol',
                'codigo_iso' => 'MNT',
                'valor_usd' => NULL,
                
                
            ),
            81 => 
            array (
                'id' => 82,
                'nombre' => 'Uguiya',
                'codigo_iso' => 'MRO',
                'valor_usd' => NULL,
                
                
            ),
            82 => 
            array (
                'id' => 83,
                'nombre' => 'Rupia de Mauricio',
                'codigo_iso' => 'MUR',
                'valor_usd' => NULL,
                
                
            ),
            83 => 
            array (
                'id' => 84,
                'nombre' => 'Rupia de Maldivas',
                'codigo_iso' => 'MVR',
                'valor_usd' => NULL,
                
                
            ),
            84 => 
            array (
                'id' => 85,
                'nombre' => 'Kwacha malauí',
                'codigo_iso' => 'MWK',
                'valor_usd' => NULL,
                
                
            ),
            85 => 
            array (
                'id' => 86,
                'nombre' => 'Peso mexicano',
                'codigo_iso' => 'MXN',
                'valor_usd' => NULL,
                
                
            ),
            86 => 
            array (
                'id' => 87,
                'nombre' => 'Ringgit malayo',
                'codigo_iso' => 'MYR',
                'valor_usd' => NULL,
                
                
            ),
            87 => 
            array (
                'id' => 88,
                'nombre' => 'Metical mozambiqueño',
                'codigo_iso' => 'MZN',
                'valor_usd' => NULL,
                
                
            ),
            88 => 
            array (
                'id' => 89,
                'nombre' => 'Dólar namibio',
                'codigo_iso' => 'NAD',
                'valor_usd' => NULL,
                
                
            ),
            89 => 
            array (
                'id' => 90,
                'nombre' => 'Naira',
                'codigo_iso' => 'NGN',
                'valor_usd' => NULL,
                
                
            ),
            90 => 
            array (
                'id' => 91,
                'nombre' => 'Córdoba nicaragüense',
                'codigo_iso' => 'NIO',
                'valor_usd' => NULL,
                
                
            ),
            91 => 
            array (
                'id' => 92,
                'nombre' => 'Corona noruega',
                'codigo_iso' => 'NOK',
                'valor_usd' => NULL,
                
                
            ),
            92 => 
            array (
                'id' => 93,
                'nombre' => 'Rupia nepalí',
                'codigo_iso' => 'NPR',
                'valor_usd' => NULL,
                
                
            ),
            93 => 
            array (
                'id' => 94,
                'nombre' => 'Dólar neozelandés',
                'codigo_iso' => 'NZD',
                'valor_usd' => NULL,
                
                
            ),
            94 => 
            array (
                'id' => 95,
                'nombre' => 'Rial omaní',
                'codigo_iso' => 'OMR',
                'valor_usd' => NULL,
                
                
            ),
            95 => 
            array (
                'id' => 96,
                'nombre' => 'Balboa panameño',
                'codigo_iso' => 'PAB',
                'valor_usd' => NULL,
                
                
            ),
            96 => 
            array (
                'id' => 97,
                'nombre' => 'Nuevo sol',
                'codigo_iso' => 'PEN',
                'valor_usd' => NULL,
                
                
            ),
            97 => 
            array (
                'id' => 98,
                'nombre' => 'Kina',
                'codigo_iso' => 'PGK',
                'valor_usd' => NULL,
                
                
            ),
            98 => 
            array (
                'id' => 99,
                'nombre' => 'Peso filipino',
                'codigo_iso' => 'PHP',
                'valor_usd' => NULL,
                
                
            ),
            99 => 
            array (
                'id' => 100,
                'nombre' => 'Rupia pakistaní',
                'codigo_iso' => 'PKR',
                'valor_usd' => NULL,
                
                
            ),
            100 => 
            array (
                'id' => 101,
                'nombre' => 'Zloty',
                'codigo_iso' => 'PLN',
                'valor_usd' => NULL,
                
                
            )
        ));


        \DB::table('tbl_monedas')->insert(array (
            101 => 
            array (
                'id' => 102,
                'nombre' => 'Guaraní',
                'codigo_iso' => 'PYG',
                'valor_usd' => NULL,
                
                
            ),
            102 => 
            array (
                'id' => 103,
                'nombre' => 'Riyal qatarí',
                'codigo_iso' => 'QAR',
                'valor_usd' => NULL,
                
                
            ),
            103 => 
            array (
                'id' => 104,
                'nombre' => 'Leu rumano',
                'codigo_iso' => 'RON',
                'valor_usd' => NULL,
                
                
            ),
            104 => 
            array (
                'id' => 105,
                'nombre' => 'Dinar serbio',
                'codigo_iso' => 'RSD',
                'valor_usd' => NULL,
                
                
            ),
            105 => 
            array (
                'id' => 106,
                'nombre' => 'Rublo ruso',
                'codigo_iso' => 'RUB',
                'valor_usd' => NULL,
                
                
            ),
            106 => 
            array (
                'id' => 107,
                'nombre' => 'Franco ruandés',
                'codigo_iso' => 'RWF',
                'valor_usd' => NULL,
                
                
            ),
            107 => 
            array (
                'id' => 108,
                'nombre' => 'Riyal saudí',
                'codigo_iso' => 'SAR',
                'valor_usd' => NULL,
                
                
            ),
            108 => 
            array (
                'id' => 109,
                'nombre' => 'Dólar de las Islas Salomón',
                'codigo_iso' => 'SBD',
                'valor_usd' => NULL,
                
                
            ),
            109 => 
            array (
                'id' => 110,
                'nombre' => 'Rupia de Seychelles',
                'codigo_iso' => 'SCR',
                'valor_usd' => NULL,
                
                
            ),
            110 => 
            array (
                'id' => 111,
                'nombre' => 'Libra sudanesa',
                'codigo_iso' => 'SDG',
                'valor_usd' => NULL,
                
                
            ),
            111 => 
            array (
                'id' => 112,
                'nombre' => 'Corona sueca',
                'codigo_iso' => 'SEK',
                'valor_usd' => NULL,
                
                
            ),
            112 => 
            array (
                'id' => 113,
                'nombre' => 'Dólar de Singapur',
                'codigo_iso' => 'SGD',
                'valor_usd' => NULL,
                
                
            ),
            113 => 
            array (
                'id' => 114,
                'nombre' => 'Leone',
                'codigo_iso' => 'SLL',
                'valor_usd' => NULL,
                
                
            ),
            114 => 
            array (
                'id' => 115,
                'nombre' => 'Chelín somalí',
                'codigo_iso' => 'SOS',
                'valor_usd' => NULL,
                
                
            ),
            115 => 
            array (
                'id' => 116,
                'nombre' => 'Dólar surinamés',
                'codigo_iso' => 'SRD',
                'valor_usd' => NULL,
                
                
            ),
            116 => 
            array (
                'id' => 117,
                'nombre' => 'Libra sursudanesa',
                'codigo_iso' => 'SSP',
                'valor_usd' => NULL,
                
                
            ),
            117 => 
            array (
                'id' => 118,
                'nombre' => 'Dobra',
                'codigo_iso' => 'STD',
                'valor_usd' => NULL,
                
                
            ),
            118 => 
            array (
                'id' => 119,
                'nombre' => 'Libra siria',
                'codigo_iso' => 'SYP',
                'valor_usd' => NULL,
                
                
            ),
            119 => 
            array (
                'id' => 120,
                'nombre' => 'Lilangeni',
                'codigo_iso' => 'SZL',
                'valor_usd' => NULL,
                
                
            ),
            120 => 
            array (
                'id' => 121,
                'nombre' => 'Baht tailandés',
                'codigo_iso' => 'THB',
                'valor_usd' => NULL,
                
                
            ),
            121 => 
            array (
                'id' => 122,
                'nombre' => 'Somoni tayiko',
                'codigo_iso' => 'TJS',
                'valor_usd' => NULL,
                
                
            ),
            122 => 
            array (
                'id' => 123,
                'nombre' => 'Manat turcomano',
                'codigo_iso' => 'TMT',
                'valor_usd' => NULL,
                
                
            ),
            123 => 
            array (
                'id' => 124,
                'nombre' => 'Dinar tunecino',
                'codigo_iso' => 'TND',
                'valor_usd' => NULL,
                
                
            ),
            124 => 
            array (
                'id' => 125,
                'nombre' => 'Pa\'anga',
                'codigo_iso' => 'TOP',
                'valor_usd' => NULL,
                
                
            ),
            125 => 
            array (
                'id' => 126,
                'nombre' => 'Lira turca',
                'codigo_iso' => 'TRY',
                'valor_usd' => NULL,
                
                
            ),
            126 => 
            array (
                'id' => 127,
                'nombre' => 'Dólar trinitense',
                'codigo_iso' => 'TTD',
                'valor_usd' => NULL,
                
                
            ),
            127 => 
            array (
                'id' => 128,
                'nombre' => 'Nuevo dólar taiwanés',
                'codigo_iso' => 'TWD',
                'valor_usd' => NULL,
                
                
            ),
            128 => 
            array (
                'id' => 129,
                'nombre' => 'Chelín tanzano',
                'codigo_iso' => 'TZS',
                'valor_usd' => NULL,
                
                
            ),
            129 => 
            array (
                'id' => 130,
                'nombre' => 'Grivna',
                'codigo_iso' => 'UAH',
                'valor_usd' => NULL,
                
                
            ),
            130 => 
            array (
                'id' => 131,
                'nombre' => 'Chelín ugandés',
                'codigo_iso' => 'UGX',
                'valor_usd' => NULL,
                
                
            ),
            131 => 
            array (
                'id' => 132,
                'nombre' => 'Dólar estadounidense',
                'codigo_iso' => 'USD',
                'valor_usd' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'nombre' => 'Peso uruguayo',
                'codigo_iso' => 'UYU',
                'valor_usd' => NULL,
                
                
            ),
            133 => 
            array (
                'id' => 134,
                'nombre' => 'Som uzbeko',
                'codigo_iso' => 'UZS',
                'valor_usd' => NULL,
                
                
            ),
            134 => 
            array (
                'id' => 135,
                'nombre' => 'Bolívar fuerte',
                'codigo_iso' => 'VEF',
                'valor_usd' => NULL,
                
                
            ),
            135 => 
            array (
                'id' => 136,
                'nombre' => 'Dong vietnamita',
                'codigo_iso' => 'VND',
                'valor_usd' => NULL,
                
                
            ),
            136 => 
            array (
                'id' => 137,
                'nombre' => 'Vatu',
                'codigo_iso' => 'VUV',
                'valor_usd' => NULL,
                
                
            ),
            137 => 
            array (
                'id' => 138,
                'nombre' => 'Tala',
                'codigo_iso' => 'WST',
                'valor_usd' => NULL,
                
                
            ),
            138 => 
            array (
                'id' => 139,
                'nombre' => 'Franco CFA de África Central',
                'codigo_iso' => 'XAF',
                'valor_usd' => NULL,
                
                
            ),
            139 => 
            array (
                'id' => 140,
                'nombre' => 'Dólar del Caribe Oriental',
                'codigo_iso' => 'XCD',
                'valor_usd' => NULL,
                
                
            ),
            140 => 
            array (
                'id' => 141,
                'nombre' => 'Franco CFA de África Occidental',
                'codigo_iso' => 'XOF',
                'valor_usd' => NULL,
                
                
            ),
            141 => 
            array (
                'id' => 142,
                'nombre' => 'Rial yemení',
                'codigo_iso' => 'YER',
                'valor_usd' => NULL,
                
                
            ),
            142 => 
            array (
                'id' => 143,
                'nombre' => 'Rand sudafricano',
                'codigo_iso' => 'ZAR',
                'valor_usd' => NULL,
                
                
            ),
            143 => 
            array (
                'id' => 144,
                'nombre' => 'Kwacha zambiano',
                'codigo_iso' => 'ZMW',
                'valor_usd' => NULL,
                
                
            ),
        ));
        
    }
}