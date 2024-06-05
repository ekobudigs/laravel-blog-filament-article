<?php

namespace App\Http\Controllers;

use App\Models\TextWidget;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteController extends Controller
{
    public function about(): View
    {
        $widget = TextWidget::query()->where('key', '=', 'about-page')->where('active', '=', true)->first();
     
      
        if(!$widget){
            throw new NotFoundHttpException();
        }

        return view('about', compact('widget'));

        
    }
}
