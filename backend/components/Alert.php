<?php

namespace backend\components;

class Alert extends \dominus77\sweetalert2\Alert
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($session = $this->getSession()) {
            $this->initFlashWidget($this->processFlashSession($session));
        } else {
			if(!empty($this->getOptions()))
            	$this->initSwal($this->getOptions(), $this->callback);
        }
    }

    /**
     * @return bool|mixed|\yii\web\Session
     */
    private function getSession()
    {
        return $this->useSessionFlash ? Yii::$app->session : false;
    }
}