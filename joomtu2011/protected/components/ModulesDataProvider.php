<?php

class ModulesDataProvider extends CDataProvider {

    protected function fetchData() {
        $app = Yii::app();
        $data = array();
        $modules = $app->getModules();
        foreach ($modules as $id => $module) {
            $object = $app->getModule($id);
            $item = array(
                'id' => $object->getId(),
                'primaryKey' => $object->getId(),
                'path' => $object->getBasePath(),
                'name' => $object->getName(),
                    //'hasConfig'=>is_file($object->getBasePath().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'configs.php'),
                    //'hasPermission'=>is_file($object->getBasePath().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'permissions.php'),
            );
            $data[] = new CAttributeCollection($item);
        }
        return $data;
    }

    protected function fetchKeys() {
        $keys = array();
        foreach ($this->getData() as $i => $data)
            $keys[$i] = $data->id;
        return $keys;
    }

    protected function calculateTotalItemCount() {
        return count($this->getData());
    }

}

?>