<?php

/**
 * Библиотека выполнения задач через заданные промежутки времени. Приблеженный аналог CRON
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopCron {
    
    var $check_host=null;

    function PHPShopCron() {
        global $PHPShopSystem;
        $this->PHPShopSystem = $PHPShopSystem;
        $this->SysValue = $GLOBALS['SysValue'];
        $this->date = date("U");
        $this->debug = false;

        // Настройка
        $this->job();
    }

    // Настройка
    function job() {
        
        $where['used']="='0'";
 
        // Мультибаза
        if (defined("HostID")){
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
            $this->check_host='&hostID='.HostID;
        }
        elseif (defined("HostMain")){
            $where['used'].= ' and (servers ="" or servers REGEXP "i1000i")';
        }

        $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['cron']['cron_job']);
        $PHPShopOrm->debug = $this->debug;
        $this->job = $PHPShopOrm->select(array('*'),$where, array('order' => 'id'), array('limit' => 100));
    }

    // Выполнение задачи
    function execute($job) {
        
        $path = explode("?",$job['path']);
        
        if (is_file($path[0])) {

            // Безопасность
            $cron_secure = md5($this->SysValue['connect']['host'] . $this->SysValue['connect']['dbase'] . $this->SysValue['connect']['user_db'] . $this->SysValue['connect']['pass_db']);
            
            if(!empty($path[1]))
                $true_path = "http://" . $_SERVER['SERVER_NAME'] . "/" . $job['path'] . '&s=' . $cron_secure. $this->check_host;
            else $true_path = "http://" . $_SERVER['SERVER_NAME'] . "/" . $job['path'] . '?s=' . $cron_secure. $this->check_host;

            if (fopen($true_path, "r"))
                return 'Выполнено';
            else
                return 'Ошибка вызова файла';
        }
        else
            return 'Ошибка размещения файла';
    }

    // Выполнение задачи для отключения fopen_url
    function _execute($job) {

        if (is_file($job['path'])) {
            if (fopen($_SERVER['DOCUMENT_ROOT'] . $job['path'], "r"))
                return 'Выполнено';
            else
                return 'Ошибка вызова файл';
        }
        else
            return 'Ошибка размещения файла';
    }

    // Запуск
    function start() {
        if (is_array($this->job))
            foreach ($this->job as $job) {
                if ($job['enabled'] and empty($job['used'])) {
                    if ($this->date - $job['last_execute'] > (86400 / $job['execute_day_num'])) {
                        // Пишем лог
                        $this->log($job, 'start');

                        // Выполнение задачи
                        $job['status'] = $this->execute($job);

                        // Пишем лог
                        $this->log($job, 'end');
                    }
                }
            }
    }

    // Запись лога в базу
    function log($job, $action) {

        switch ($action) {

            case "start":

                // Блокируем загрузки
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['cron']['cron_job']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->update(array('used_new' => '1'), array('id' => '=' . $job['id']));

                // Пишем лог
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['cron']['cron_log']);
                $PHPShopOrm->debug = $this->debug;
                $this->log_id = $PHPShopOrm->insert(array('date_new' => $this->date, 'name_new' => $job['name'], 'path_new' => $_SERVER['SERVER_NAME'].'/'.$job['path']. $this->check_host,
                    'job_id_new' => $job['id']));
                break;

            case "end":

                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['cron']['cron_log']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->update(array('status_new' => $job['status']), array('id' => '=' . $this->log_id));

                // Снимаем блокировку загрузки
                $PHPShopOrm = new PHPShopOrm($this->SysValue['base']['cron']['cron_job']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->update(array('used_new' => '0', 'last_execute_new' => $this->date), array('id' => '=' . $job['id']));

                break;
        }
    }

}

?>