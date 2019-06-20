<?php
class Job
{
    const
        CACHE_PREFIX = 'SHM/',
        WORKER_NUMBER = 10;

    private
        $_queues = [],
        $_tasks = [],
        $_pids = [],
        $_workerNumber,
        $_cacheHandler,
        $_prefix,
        $_id;

    public function __construct(array $queue, $workerNumber = self::WORKER_NUMBER)
    {
        $count = count($queue);

        $length = ceil($count / $workerNumber);

        $this->_queues = array_chunk($queue, $length);

        $this->setWorkerNumber($workerNumber);

        $this->_prefix = self::CACHE_PREFIX . microtime(true) . '/';
    }

    public function setWorkerNumber($workerNumber)
    {
        $this->_workerNumber = $workerNumber;
    }

    public function __get($key)
    {
        return apc_fetch($this->_prefix . $key);
    }

    public function __set($key, $value)
    {
        apc_store($this->_prefix . $key, $value);
    }

    public function add(Closure $task)
    {
        $this->_tasks[] = $task->bindTo($this, $this);

        return $this;
    }

    public function run(Closure $task = null)
    {
        if (isset($task))
        {
            $this->add($task);
        }

        $i = 0;

        do
        {
            $queue = $this->_queues[$i++];

            $pid = pcntl_fork();

            $this->_id = $i;

            if ($pid === -1)
            {
                die("can't fork !");
            }
            elseif ($pid !== 0) // main
            {
                $this->_pids[$pid] = $pid;
            }
            else // child
            {
                foreach($this->_tasks as $task)
                {
                    $task($queue);
                }

                exit(0);
            }
        }
        while($i < $this->_workerNumber);

        do // main
        {
            $pid = pcntl_wait($status);

            unset($this->_pids[$pid]);
        }
        while(count($this->_pids));
    }
}

$driver = new mysqli(':host', ':user', ':pwd', ':db');

$query = 'SELECT * FROM :table LIMIT :n';

if (false !== ($res = $driver->query($query)))
{
    $resultSet = [];

    while($row = mysqli_fetch_assoc($res))
    {
        $resultSet[] = $row;
    }

    $job = new Job($resultSet);

    $job->test = [];

    $job->run(function($queue = [])
    {
        // task

        foreach($queue as $value)
        {
            $test = $this->test;

            $value['workedId'] = $this->_id;

            // ...

            $test[] = $value;

            $this->test = $test;
        }
    });

    print_r($job->test);
}