<?php

class View
{
    function generate($content_view, $data = null)
    {
        
        if(is_array($data)) {
            extract($data);
        }
                
        include 'views/' . $content_view;
    }
}