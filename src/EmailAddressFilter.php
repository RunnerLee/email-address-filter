<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-18 下午11:29
 */

namespace Runner\EmailAddressFilter;

class EmailAddressFilter
{

    /**
     * @var \SplFileObject
     */
    protected $file;

    /**
     * @var array
     */
    protected $topDomain;

    /**
     * @var \SplFileObject
     */
    protected $tempTable = null;


    /**
     * EmailAddressFilter constructor.
     * @param $dictionary
     * @param null $tempTable
     */
    public function __construct($dictionary, $tempTable = null)
    {
        $this->file = new \SplFileObject($dictionary);
        $line = explode('  ', trim($this->file->current()));
        $this->topDomain = [
            $line[0],
            array_combine(explode(' ', $line[1]), unpack('c*', $line[2]))
        ];
        if(!is_null($tempTable)) {
            $this->tempTable = new \SplFileObject($tempTable);
        }
    }


    /**
     * @param string $email
     * @return bool|string
     */
    public function filter($email)
    {
        if(false === $email = $this->formatChecker($email)) {
            return false;
        }
        $domain = explode('@', $email)[1];
        if(!$this->searchTree($domain)) {
            if(is_null($this->tempTable) || !$this->searchFromTempTable($domain) ) {
                return false;
            }
        }

        return $email;
    }


    /**
     * @param $email
     * @return bool|string
     */
    public function filterWithQueryDns($email)
    {
        if(false === $email = $this->formatChecker($email)) {
            return false;
        }
        $domain = explode('@', $email)[1];
        if($this->searchTree($domain)) {
            return $email;
        }

        if(!@dns_get_record($domain, DNS_MX)) {
            return false;
        }

        if(!is_null($this->tempTable)) {
            file_put_contents($this->tempTable->getPathname(), $domain . "\n", FILE_APPEND);
        }

        return true;
    }


    /**
     * @param string $domain
     * @return bool
     */
    public function searchFromTempTable($domain)
    {
        $domain = strtolower($domain);
        while(!$this->tempTable->eof()) {
            if($domain == trim($this->tempTable->current())) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param string $email
     * @return bool|string
     */
    public function formatChecker($email)
    {
        $specialChars = '._-';
        if(
            false === ($email = filter_var($email = strtolower($email), FILTER_VALIDATE_EMAIL)) ||
            false !== strpos($email, '/')                                                     ||
            false !== strpos($email, '__')                                                    ||
            false !== strpos($email, '--')                                                    ||
            false !== strpos($email, '..')                                                    ||
            false !== strpos($specialChars, substr($email, 0, 1))                             ||
            false !== strpos($specialChars, substr($email, strpos($email, '@') - 1, 1))
        ) {
            return false;
        }

        return $email;
    }


    /**
     * @param string $domain
     * @return bool
     */
    protected function searchTree($domain)
    {
        $pointPosition = strrpos($domain, '.');
        $top = substr($domain, $pointPosition + 1);

        if(!isset($this->topDomain[1][$top])) {
            return false;
        }

        $word = unpack('c*', substr($domain, 0, $pointPosition));

        $current = $this->topDomain[1][$top];
        $matched = true;

        foreach($word as $key) {
            $this->file->seek($current);
            $line = explode('  ', trim($this->file->current()));

            if(!isset($line[2])) {
                return false;
            }

            $node = [
                $line[0],
                array_combine(unpack('c*', $line[1]), unpack('c*', $line[2])),
            ];

            if(isset($node[1][$key])) {
                $current = $node[1][$key] - 12;
                continue;
            }
            $matched = false;
        }
        $this->file->seek($current);

        if('0' !== trim($this->file->current())) {
            $matched = false;
        }

        return $matched;
    }


    public function __destruct()
    {
        unset($this->file);
    }
}
