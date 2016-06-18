<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-18 下午11:29
 */

namespace Runner\EmailAddressFilter;

class EmailAddressFilter
{

    protected $file;

    protected $topDomain;

    public function __construct($file)
    {
        $this->file = new \SplFileObject($file);
        $line = explode('  ', trim($this->file->current()));
        $this->topDomain = [
            $line[0],
            array_combine(explode(' ', $line[1]), unpack('c*', $line[2]))
        ];
    }


    public function filter($email)
    {
        if(false === $email = $this->formatChecker($email)) {
            return false;
        }
        $domain = explode('@', $email)[1];
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

        $matched && $matched = $email;

        return $matched;
    }


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


    public function __destruct()
    {
        unset($this->file);
    }
}
