<?php

class AppController{
    protected function render(string $template=null){
        $templatePath = 'src/views/'.$template.'.html';
        $output = 'There is no such template';
        if(file_exists($templatePath)){
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;

    }
}