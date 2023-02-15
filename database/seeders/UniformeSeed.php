<?php

namespace Database\Seeders;

use App\Models\Uniforme;
use Illuminate\Database\Seeder;

class UniformeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Uniforme::create(
            [
                'dni' => '43504693', 
                'apn' => 'ABARCA_CORDERO_JULIO CESAR',
                'sexo' => 'M',
                'talla_saco' => 'M(38)',
                'talla_pantalon' => 'S(30)',
                'talla_camisa' => 'M(15.5)',
                'recogido' => false,
                'user_id' => 6,            
            ]
        );

        Uniforme::create(
            [
                'dni' => '29655334', 
                'apn' => 'AEDO_LOPEZ_MARCO WILFREDO',
                'sexo' => 'M',
                'talla_saco' => '3XL(46)',
                'talla_pantalon' => '2XL(38)',
                'talla_camisa' => 'XL(17.5)',
                'recogido' => false,
                'user_id' => 6,            
            ]
        );


        Uniforme::create(
            [
                'dni' => '43369139', 
                'apn' => 'BERNEDO_MALAGA_VICTOR WILLIAMS',
                'sexo' => 'M',
                'talla_saco' => 'L(40)',
                'talla_pantalon' => 'L(34)',
                'talla_camisa' => 'ML(16)',
                'recogido' => false,
                'user_id' => 7,            
            ]
        );

        Uniforme::create(
            [
                'dni' => '40454472', 
                'apn' => 'ADRIAZOLA_HERRERA_ELIANA MARIA',
                'sexo' => 'F',
                'talla_saco' => 'M(12)',
                'talla_pantalon' => 'M(12)',
                'talla_camisa' => null,
                'recogido' => false,
                'user_id' => 7,            
            ]
        );


        Uniforme::create(
            [
                'dni' => '29628503', 
                'apn' => 'CASAPIA_GUZMAN_YESENIA MARISELA',
                'sexo' => 'F',
                'talla_saco' => 'L(14)',
                'talla_pantalon' => 'M(12)',
                'talla_camisa' => null,
                'recogido' => false,
                'user_id' => 8,            
            ]
        );
       
    }
}
