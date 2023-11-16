<?php

class AppController{

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }
    
    //Get file name as template
    protected function render(string $template=null, array $variables = []){
        $templatePath = 'src/views/'.$template.'.php';
        $output = 'There is no such template';
        if(file_exists($templatePath)){
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;

    }
}