<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class DragAndDropHelper extends \Codeception\Module
{
    public function dragAndDropJS($source, $target)
    {
        $I = $this->getModule(name: 'WebDriver');

        $filePath = codecept_data_dir('drag_and_drop_helper.js');

        $file = fopen($filePath, 'r');
        $script = "";
        
        while(!feof($file)) {
            $script .= fgets($file);

        }
        fclose($file);
        $I->executeJS(
            $script . "$('{$source}').simulateDragDrop({ dropTarget: '{$target}' });"
        );
    }

}
