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
    /**
     * @return array
     */
    public function mainBottom(): array
    {
        $keyboard = [
            [
                ['text' => 'لیست برند ها'],
            ],
            [
                ['text' => 'انتقادات و پیشنهادات'],
                ['text' => 'معرفی به دوستان']
            ],
            [
                ['text' => 'مشاوره رایگان']
            ],
            [
                ['text' => 'تبلیغ شاپکت']
            ],
            [
                ['text' => 'معرفی ربات']
            ]
        ];

        return $keyboard;
    }

    /**
     * @return array
     */
    public function welcomeButtons(): array
    {
        $keyboard = [
            [
                ['text' => 'فروشگاه ها'],
                ['text' => 'دسته بندی ها'],
                ['text' => 'اخبار'],
                ['text' => 'درباره ما'],
                ['text' => 'تماس با ما'],
            ],

        ];

        return $keyboard;
    }


    /**
     * @return array
     */
    public function citiesButtons($cities): array
    {

        $keyboards = [[]];

        foreach ($cities as $city){
            $keyboard[]=['text'=>$city['name']];

        }

        $keyboards[][] = ['text' => '🔙 بازگشت به منو اصلی'];


        return $keyboards;
    }


    /**
     * @return array
     */
    public function categories($categories): array
    {
        $keyboards = [[]];

        foreach ($categories as $k=>$category){
            $keyboards[][]=['text'=>$category['name']];

        }

        $keyboards[][] = ['text' => '🔙 بازگشت به منو اصلی'];


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
                ['text' => 'فروشگاه ها'],
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

        $keyboard[][] = ['text' => 'بازگشت به منوی اصلی'];

        return $keyboard;
    }

    public function back()
    {
        $keyboard = [
            [
                ['text' => 'بازگشت به منوی اصلی']
            ],
        ];

        return $keyboard;
    }

    public function listCities($cities): array
    {
        $main = [];
        $keyboard = [];
        foreach ($cities as $key => $city) {
            array_push($main, ['text' => $city['name'],"callback_data" => 'getShops-'.$city['id']]);
            $temp = array_slice($main, -1);
            array_push($keyboard, ($temp));
        }

        return $keyboard;
    }


    public function inlineButtons()
    {
        $keyboards =[];

        $keyboards[]=[
            /*[
                'text' =>'r1 btn1' ,
                "callback_data" => 'SetYourFunctionWithParameter separated by dash'
            ]*/
        ];



        return $keyboards;

    }

    public function previousStepBottom(): array
    {
        $keyboard = [
            [
                ['text' => 'گام قبل'],
            ]
        ];

        return $keyboard;
    }


}