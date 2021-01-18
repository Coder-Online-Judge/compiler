<?php
/**
 *
 */
class Checker
{
    public $apiData;
    protected $file = array();

    public function __construct()
    {
        global $file;
        $this->file = $file;
    }

    public function buildChecker($data)
    {
        $this->apiData = $data;
        $this->createCheckerFile();
        $checkerData = $this->runChecker();
        $this->removeMergeFile();
        return $checkerData;
    }


    public function createCheckerFile()
    {
    	exec("mkdir -m 777 -p temp");
        $this->makeFile(ff()->input, $this->apiData['input']);
        $this->makeFile(ff()->output, $this->apiData['output']);
        $this->makeFile(ff()->expectedOutput, $this->apiData['expectedOutput']);
        $this->makeFile(ff()->checker, $this->apiData['checker']);
    }

    public function removeMergeFile()
    {
        exec("rm -R temp");
    }
    
    public function makeFile($fileName, $fileVal = "")
    {
        $filePath = $fileName;
        $file     = fopen($filePath, "w+");
        fwrite($file, $fileVal);
        fclose($file);
    }

    public function runChecker()
    {
        //$cmd = "g++ --std=c++11 $fileName 2> spj_error.txt";
        //$out = shell_exec($cmd);
        //echo "$out";
        return !file_exists(ff()->checker)? $this->runNormalChecker():$this->runSpjChecker();
        
    }

    public function runNormalChecker(){
        $this->createNormalCheckerScript();
        $checkerLogFile = ff()->checkerLog;
        $cmd = "bash temp/checker.sh > $checkerLogFile";
        shell_exec($cmd);
        $checkerLog = file_get_contents($checkerLogFile);

        return [
            'checkerLog' => $checkerLog,
            'checkerVerdict' => $this->getCheckerVerdict($checkerLog),
            'checkerError' => ""
        ];
    }

    public function runSpjChecker(){
        $this->createTestLib();

        $checkerFileName       = $this->file['checker'];
        $checkerErrorFile      = $this->file['checkerError'];
        $checkerExecutableFile = $this->file['checkerExecutableFile'];

        $cmd = "g++ --std=c++11 -lm $checkerFileName -o $checkerExecutableFile 2> $checkerErrorFile";
        shell_exec($cmd);
        $checkerError = file_get_contents($checkerErrorFile);

        $ckeckerLog = "";

        if (trim($checkerError) == "") {

            $checkerLogFile = $this->file['checkerLog'];

            $input          = $this->file['input'];
            $output         = $this->file['output'];
            $expectedOutput = $this->file['expectedOutput'];

            $cmd = "timeout 1s ./$checkerExecutableFile $input $output $expectedOutput 2> $checkerLogFile";
            shell_exec($cmd);
            $checkerLog = file_get_contents($checkerLogFile);
        } else {
            $checkerLog = "checker is not valid ";
        }

        $data                   = array();
        $data['checkerLog']     = $checkerLog;
        $data['checkerVerdict'] = $this->getCheckerVerdict($checkerLog);
        $data['checkerError'] = $checkerError;
        return $data;
    }

    public function createTestLib()
    {
        shell_exec("cp src/lib/testlib/testlib.h temp");
    }

    public function createNormalCheckerScript()
    {
        shell_exec("cp ../src/lib/normalChecker/checker.sh temp");
    }
    public function getCheckerVerdict($checkerLog)
    {
        if (strlen($checkerLog) < 2) {
            return 0;
        }

        $ok = substr($checkerLog, 0, 2);
        return $ok == "ok" ? 1 : 0;
    }
}
