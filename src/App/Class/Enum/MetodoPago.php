<?php

namespace App\Class\Enum;

enum MetodoPago
{
    case PAYPAL;
    case APPLE_PAY;
    case GOOGLE_PAY;
    case CARD;
    case BIZUM;

    public static function convertirStringAMetodoPago(?string $tipo):?MetodoPago{

        if($tipo==null){
            return null;
        }else{
            return match($tipo){
                "paypal"=>MetodoPago::PAYPAL,
                "apple_pay"=>MetodoPago::APPLE_PAY,
                "google_pay"=>MetodoPago::GOOGLE_PAY,
                "card"=>MetodoPago::CARD,
                "bizum"=>MetodoPago::BIZUM,
                "default"=>null
            };
        }
    }

}
