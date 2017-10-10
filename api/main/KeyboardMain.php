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

    public function aboutBotBottom(): array
    {
        $keyboard = [
            [
                ['text' => 'درباره ما'],
                ['text' => 'ارتباط با ما']
            ],
            [
                ['text' => 'نحوه خرید'],
            ],
            [
                ['text' => 'شرایط ارسال'],
                ['text' => 'شرایط استرداد']
            ],
            [
                ['text' => 'قوانین و مقررات']
            ],
            [
                ['text' => 'اینستاگرام'],
                ['text' => 'تلگرام']
            ],
            [
                ['text' => 'بازگشت به منوی اصلی'],
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

    public function addToCartButton($brands): array
    {
        $main = [];
        $keyboard = [];
        foreach ($brands as $key => $value) {
            array_push($main, ['text' => 'اضافه کردن به سبد خرید', "callback_data" => $value['pro_ID']]);
            $temp = array_slice($main, -1);
            array_push($keyboard, ($temp));
        }

        return $keyboard;
    }

    /**
     * @return array
     */
    public function listProductBottom(): array
    {
        $keyboard = [
            [
                ['text' => 'اضافه کردن به سبد خرید'],
                ['text' => 'مشاهده سبد خرید'],
            ],
            [
                ['text' => 'می پسندم'],
                ['text' => 'نمی پسندم'],
            ],
            [
                ['text' => 'ثبت نظر'],
                ['text' => 'ثبت امتیاز'],
            ],
            [
                ['text' => 'بازگشت به منوی اصلی'],
            ],
        ];

        return $keyboard;
    }

    /**
     * @param $categories
     * @return array
     */
    public function listCategoryBottom($categories): array
    {
        $keyboard[] = [
            ['text' => 'پرفروش ترین ها'],
            ['text' => 'ارزان ترین ها'],
        ];

        $keyboard[] = [
            ['text' => 'محبوب ترین ها'],
            ['text' => 'تازه ترین ها'],
        ];

        foreach ($categories as $key => $value) {
            $keyboard[][] = ['text' => $value['cat_Name']];
        }

        $keyboard[] = [
            ['text' => 'بازگشت به منوی اصلی'],
            ['text' => 'بازگشت به مرحله قبل']
        ];

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

    public function listProduct($product): array
    {
        $main = [];
        $keyboard = [];
        foreach ($product as $key => $value) {
            array_push($main, ['text' => 'مشاهده محصول', "callback_data" => $value['pro_ID']]);
            $temp = array_slice($main, -1);
            array_push($keyboard, ($temp));
        }

        return $keyboard;
    }

    public function showCartBottom(): array
    {
        $keyboard = [
            [
                ['text' => 'افزودن محصول'],
                ['text' => 'حذف محصول']
            ],
            [
                ['text' => 'مشاهده سبد خرید'],
            ],
            [
                ['text' => 'ثبت نهایی'],
            ],
            [
                ['text' => 'بازگشت به منوی اصلی']
            ]
        ];

        return $keyboard;
    }
    
    public function afterAddingToCart(): array
    {
        $keyboard = [
            [
                ['text' => 'افزودن محصول'],
                ['text' => 'حذف محصول']
            ],
            [
                ['text' => 'مشاهده سبد خرید'],
            ],
            [
                ['text' => 'ثبت نهایی'],
            ],
            [
                ['text' => 'بازگشت به منوی اصلی']
            ]
        ];

        return $keyboard;
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