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
                ['text' => 'بازگشت'],
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
        $keyboard[] = [
            ['text' => 'پرفروش ترین ها'],
            ['text' => 'ارزان ترین ها'],
        ];

        $keyboard[] = [
            ['text' => 'محبوب ترین ها'],
            ['text' => 'تازه ترین ها'],
        ];

        foreach ($brands as $key => $value) {
            $keyboard[][] = ['text' => $value['bra_Name']];
        }

        $keyboard[][] = ['text' => 'بازگشت'];

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
                ['text' => 'بازگشت'],
                ['text' => 'بازگشت به مرحله قبل'],
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
            ['text' => 'بازگشت'],
            ['text' => 'بازگشت به مرحله قبل']
        ];

        return $keyboard;
    }

    public function back()
    {
        $keyboard = [
            [
                ['text' => 'بازگشت']
            ],
        ];

        return $keyboard;
    }
}