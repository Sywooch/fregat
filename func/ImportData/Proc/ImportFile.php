<?php

/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 19.10.2016
 * Time: 13:57
 */

namespace app\func\ImportData\Proc;

use app\models\Fregat\Import\Importconfig;
use app\models\Fregat\Import\Logreport;
use Exception;
use SplObserver;
use yii\db\ActiveRecord;

/**
 * Class ImportFile
 * @package app\func\ImportData\Proc
 */
abstract class ImportFile implements iImportFile
{
    /**
     * @var array
     */
    private $observers = array();
    /**
     * @var bool
     */
    private $_debug;
    /**
     * @var Logreport
     */
    private $logReport;
    /**
     * @var string
     */
    private $fileName;
    /**
     * @var bool|string
     */
    private $fileLastDate;
    /**
     * @var integer
     */
    private $row;
    /**
     * @var array
     */
    private $_importLogs;
    /**
     * @var string
     */
    protected $fieldNameDB;
    /**
     * @var bool|string
     */
    protected $typeFile;
    /**
     * @var string
     */
    protected $importFileLastDateFieldDB;
    /**
     * @var mixed
     */
    protected $startTime;
    /**
     * @var mixed
     */
    protected $endTime;
    /**
     * @var Importconfig
     */
    private $_importConfig;

    /**
     * ImportFile constructor.
     * @param Importconfig $importConfig
     * @param $fieldNameDB
     * @param Logreport $logReport
     *
     * @throws Exception
     */
    public function __construct(Importconfig $importConfig, $fieldNameDB, Logreport $logReport)
    {
        $this->logReport = $logReport;
        $this->fieldNameDB = $fieldNameDB;
        $this->setTypeFile();
        $this->setImportConfig($importConfig);
        $fileName = dirname($_SERVER['SCRIPT_FILENAME']) . '/imp/' . $this->getImportConfig()->$fieldNameDB . '.' . $this->typeFile;

        if (!file_exists($fileName))
            throw new Exception('Файл не существует. ' . $fileName);

        $this->fileName = $fileName;
        $this->fileLastDate = date("Y-m-d H:i:s", filemtime($this->fileName));
    }

    /**
     * @return Importconfig
     */
    public function getImportConfig()
    {
        return $this->_importConfig;
    }

    /**
     * @param Importconfig $importConfig
     */
    private function setImportConfig($importConfig)
    {
        $this->_importConfig = $importConfig;
    }

    /**
     * @return Logreport
     */
    public function getLogReport()
    {
        return $this->logReport;
    }

    /**
     * @param Logreport $logReport
     */
    protected function setLogReport(Logreport $logReport)
    {
        $this->logReport = $logReport;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    protected function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return bool|string
     */
    public function getFileLastDate()
    {
        return $this->fileLastDate;
    }

    /**
     * @param bool|string $fileLastDate
     */
    protected function setFileLastDate($fileLastDate)
    {
        $this->fileLastDate = $fileLastDate;
    }

    /**
     * @return integer
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param integer $row
     */
    protected function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return bool
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->_debug = $debug;
    }

    /**
     * @param string $formNameLog
     * @return ImportLog
     * @throws Exception
     */
    public function getImportLog($formNameLog)
    {
        if (isset($this->_importLogs[$formNameLog]))
            return $this->_importLogs[$formNameLog];
        else
            throw new Exception('Отсутствует ActiveRecord лога ' . $formNameLog);
    }

    /**
     * @param ImportLog $importLog
     */
    public function setImportLog($importLog)
    {
        $this->_importLogs[$importLog->getActiveRecordLog()->formName()] = $importLog;
    }

    public function removeImportLog($formNameLog)
    {
        if (isset($this->_importLogs[$formNameLog]))
            unset($this->_importLogs[$formNameLog]);
        else
            throw new Exception('Отсутствует ActiveRecord лога ' . $formNameLog);
    }

    /**
     * @return bool|string
     */
    protected function setTypeFile()
    {
        switch ($this->fieldNameDB) {
            case 'emp_filename':
                return $this->typeFile = 'txt';
            case 'os_filename':
                return $this->typeFile = 'xlsx';
            case 'mat_filename':
                return $this->typeFile = 'xlsx';
            case 'gu_filename':
                return $this->typeFile = 'xlsx';
        }

        return false;
    }

    /**
     * @return bool|string
     */
    protected function getMaxFileLastDate()
    {
        switch ($this->fieldNameDB) {
            case 'emp_filename':
                return $this->importFileLastDateFieldDB = 'logreport_employeelastdate';
            case 'os_filename':
                return $this->importFileLastDateFieldDB = 'logreport_oslastdate';
            case 'mat_filename':
                return $this->importFileLastDateFieldDB = 'logreport_matlastdate';
            case 'gu_filename':
                return $this->importFileLastDateFieldDB = 'logreport_gulastdate';
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function isChanged()
    {
        $Field = $this->getMaxFileLastDate();

        if (!$Field)
            return false;

        if (empty(Logreport::find()->count()) || $this->getDebug())
            return true;

        $fileLastDateFromDB = Logreport::find()->max($Field);

        if (empty($fileLastDateFromDB))
            return false;

        return strtotime($this->fileLastDate) > strtotime($fileLastDateFromDB);
    }

    /**
     *
     */
    protected function setLastDateImportFileToDB()
    {
        $this->getLogReport()->{$this->importFileLastDateFieldDB} = $this->fileLastDate;
    }


    /**
     * Attach an SplObserver
     * @link http://php.net/manual/en/splsubject.attach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to attach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * Detach an observer
     * @link http://php.net/manual/en/splsubject.detach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to detach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function detach(SplObserver $observer)
    {
        $key = array_search($observer, $this->observers, true);
        if (false !== $key) {
            unset($this->observers[$key]);
        }
    }

    public function getObservers()
    {
        return $this->observers;
    }


    /**
     * @param string $FieldName
     * @return bool|iFilterObserver
     */
    public function getObserverByFieldName($FieldName)
    {
        foreach ($this->getObservers() as $observer) {
            if ($observer->getFieldName() === $FieldName)
                return $observer;
        }

        return false;
    }

    /**
     * Notify an observer
     * @link http://php.net/manual/en/splsubject.notify.php
     * @return void
     * @since 5.1.0
     */
    public function notify()
    {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }

    /**
     *
     */
    abstract public function iterate();

}