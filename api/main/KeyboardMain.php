<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-12-11
 * Time: 9:50 AM
 */

namespace main;


class KeyboardMain
{

     public $emojis=[
       'ابزار و لوازم'=>'🛠',
       'پوشاک و کودک'=>'👕️',
       'کادویی و تزئینی'=>'🎁',
       'خانه و آشپزخانه'=>'🏠️',
       'مدرسه و اداره'=>'✏️️',
       'ورزش و سرگرمی'=>'⚽️',
       'آرایشی و بهداشتی'=>'💄️',
       'زیور آلات'=>'💫',
     ];

    /**
     * @return array
     */
    public function welcomeButtons(): array
    {
        $keyboard = [

                [
                    ['text' => '🏪 فروشگاه ها'],
                ],

                [
                    ['text' => '📝 اخبار'],
                    ['text' =>  '🛍 دسته بندی ها'],
                ],
                [
                    ['text' => '📞 تماس با ما'],
                    ['text' => '❓ درباره ما'],

                ]

        ];

        return $keyboard;
    }


    /**
     * @return array
     */
    public function citiesButtons($cities): array
    {

        $keyboards = [[]];

        foreach ($cities as $i=>$city){
            $keyboard[]=[
                ['text'=>$city['name']],
                ['text'=>$city['name']]
            ];

        }

        $keyboards[][] = ['text' => '🔙 بازگشت به منو اصلی'];


        return $keyboards;
    }


    /**
     * @return array
     */
    public function categories($categories): array
    {
        $keyboards = [];

        $row=0;
        $index=1;

        foreach ($categories as $k=>$category){
            $keyboards[$row][]=['text'=>$this->emojis[$category['name']].' '.$category['name']];
            if($index % 2 == 0){
                $row++;
            }
            $index++;
        }


        $keyboards[]= [
              ['text' => '🔙 بازگشت به منو اصلی']
        ];

        return $keyboards;

    }


    /**
     * @return array
     */
    public function askCity(): array
    {
        $keyboard = [
            [
                ['text' => 'شهر خود را وارد کنید'],
            ],
        ];

        return $keyboard;
    }


    /**
     * @return array
     */
    public function enterCityOrBack(): array
    {
        $keyboard = [
            [
                ['text' => '🛒فروشگاه ها'],
                ['text' => '🔙 بازگشت به منو اصلی'],
            ],
        ];

        return $keyboard;
    }

    
    /**
     * @return array
     */
    public function backButton(): array
    {
        $keyboard = [
            [
                ['text' => '🔙 بازگشت به منو اصلی'],
            ],
        ];

        return $keyboard;
    }
    



    /**
     * @param $brands
     * @return array
     */
    public function listBrandBottom($brands): array
    {

        foreach ($brands as $key => $value) {
            $keyboard[][] = ['text' => $value['bra_Name']];
        }

        $keyboard[][] = ['text' => '🔙 بازگشت به منو اصلی'];

        return $keyboard;
    }

    public function back()
    {
        $keyboard = [
            [
                ['text' => '🔙 بازگشت به منو اصلی']
            ],
        ];

        return $keyboard;
    }

    public function listCities($cities): array
    {

        $keyboards = [];

        $row=0;
        $index=1;

        foreach ($cities as $k=>$city){
            $keyboards[$row][]=['text' =>"📍". $city['name'],"callback_data" => 'getShops-'.$city['id']];
            if($index % 3 == 0){
                $row++;
            }
            $index++;
        }

        return $keyboards;


//        $main = [];
//        $keyboard = [];
//        foreach ($cities as $key => $city) {
//            array_push($main, ['text' =>"📍". $city['name'],"callback_data" => 'getShops-'.$city['id']]);
//            $temp = array_slice($main, -1);
//            array_push($keyboard, ($temp));
//        }
//
//        return $keyboard;
    }



    public function previousStepBottom(): array
    {
        $keyboard = [
            [
                ['text' => '🔙 بازگشت به منو اصلی'],
            ]
        ];

        return $keyboard;
    }


}