<?php

use App\PhotoProject;
use Illuminate\Database\Seeder;

class PhotoProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        PhotoProject::truncate();

        PhotoProject::create([
            "title"=>"RED BIKE",
            "image"=>"https://sun9-47.userapi.com/PH_KEE-qcMns1Iv8YpIk3vtmTa_8f4hzUVq8CA/xCUJlo_Euzk.jpg",
            "date"=>"06 СЕНТЯБРЯ",
            "time"=>"16:00",
            "place"=>"УЛИЦА",
            "photographer"=>"@KOVALYOV_FOTO",
            "teacher"=>"@MARIAGOROBETS",
            "sponsor"=>"LOTUS PHOTO STUDIO",
            "price"=>2000,
            "is_active"=>true,
            "position"=>0
        ]);

        PhotoProject::create([
            "title"=>"POOL PARTY 3",
            "image"=>"https://sun9-25.userapi.com/DlNp4GzCv2LldYOeuAWn0mmHrBUzU3wXSZwv7Q/4J2qUno2h9g.jpg",
            "date"=>"13 СЕНТЯБРЯ",
            "time"=>"10:00",
            "place"=>"БАССЕЙН",
            "photographer"=>"@KOVALYOV_FOTO",
            "teacher"=>"@MARIAGOROBETS",
            "sponsor"=>"LOTUS PHOTO STUDIO",
            "price"=>2000,
            "is_active"=>true,
            "position"=>1
        ]);

        PhotoProject::create([
            "title"=>"VINTAGE CAR",
            "image"=>"https://sun9-39.userapi.com/qRCwJmPBb5J0vEsB0g908nDE2I52lZSuO4cwbQ/TWTTg5dw1kM.jpg",
            "date"=>"26 СЕНТЯБРЯ",
            "time"=>"16:00",
            "place"=>"УЛИЦА",
            "photographer"=>"@KOVALYOV_FOTO",
            "teacher"=>"@ALINANOVACHEK",
            "sponsor"=>"LOTUS PHOTO STUDIO",
            "price"=>2000,
            "is_active"=>true,
            "position"=>2
        ]);

        PhotoProject::create([
            "title"=>"BLACK TERRICON 2",
            "image"=>"https://sun9-58.userapi.com/xXN9jSU5NPY_lqXP1C_lhiwwf3FQB-HQompi8g/YjFPdco-ROE.jpg",
            "date"=>"27 СЕНТЯБРЯ",
            "time"=>"16:00",
            "place"=>"ТЕРРИКОН",
            "photographer"=>"@KOVALYOV_FOTO",
            "teacher"=>"@ALINANOVACHEK",
            "sponsor"=>"LOTUS PHOTO STUDIO",
            "price"=>2000,
            "is_active"=>true,
            "position"=>3
        ]);

        PhotoProject::create([
            "title"=>"ЭМОЦИИ И СНЕПЫ",
            "image"=>"https://sun2.48276.userapi.com/c848536/v848536665/177130/tzf1fK-6aio.jpg",
            "date"=>"12 СЕНТЯБРЯ",
            "time"=>"14:00",
            "place"=>"СТУДИЯ «LOTUS»",
            "photographer"=>"@ANTON.MISHUCK",
            "teacher"=>"@MARIA_KHARCHENKO",
            "sponsor"=>"LOTUS MODEL AGENCY",
            "price"=>0,
            "is_active"=>true,
            "position"=>4
        ]);

        PhotoProject::create([
            "title"=>"MODEL TESTS",
            "image"=>"https://sun2.48276.userapi.com/c848536/v848536665/177130/tzf1fK-6aio.jpg",
            "date"=>"29 СЕНТЯБРЯ",
            "time"=>"14:00",
            "place"=>"СТУДИЯ «LOTUS»",
            "photographer"=>"@_.ARTME",
            "teacher"=>"@MARIAGOROBETS",
            "sponsor"=>"LOTUS MODEL AGENCY",
            "price"=>0,
            "is_active"=>true,
            "position"=>5
        ]);

        PhotoProject::create([
            "title"=>"FOOTBALL",
            "image"=>"https://sun2.48276.userapi.com/c848536/v848536665/177130/tzf1fK-6aio.jpg",
            "date"=>"30 СЕНТЯБРЯ",
            "time"=>"16:00",
            "place"=>"СТАДИОН",
            "photographer"=>"@KOVALYOV_FOTO",
            "teacher"=>"@ALINANOVACHEK",
            "sponsor"=>"LOTUS MODEL AGENCY",
            "price"=>1500,
            "is_active"=>true,
            "position"=>6
        ]);
    }
}
