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
                "PAYPAL"=>MetodoPago::PAYPAL,
                "APPLE_PAY"=>MetodoPago::APPLE_PAY,
                "GOOGLE_PAY"=>MetodoPago::GOOGLE_PAY,
                "CARD"=>MetodoPago::CARD,
                "BIZUM"=>MetodoPago::BIZUM,
                "DEFAULT"=>null
            };
        }
    }

}
