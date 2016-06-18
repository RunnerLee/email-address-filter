<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 16-6-18 下午10:52
 */

namespace Runner\EmailAddressFilter;

class Builder
{

    public static function build($input, $output)
    {
        $file = new \SplFileObject($input);
        $data = [
            [0, []],
        ];

        $insert = 1;

        while(!$file->eof()) {
            $row = strtolower(trim($file->fgets()));
            $pointPosition = strrpos($row, '.');
            $top = substr($row, $pointPosition + 1);
            $main = array_map(function($v) {
                return chr($v);
            }, unpack('c*', substr($row, 0, $pointPosition)));

            array_unshift($main, $top);

            $current = 0;

            foreach($main as $key) {
                if(isset($data[$current][1][$key])) {
                    $current = $data[$current][1][$key];
                    continue;
                }
                $data[$insert] = [
                    0, []
                ];
                $current = $data[$current][1][$key] = $insert++;
            }
        }

        $count = count($data);
        $data[0] = $data[0][0] . '  ' . implode(' ', array_keys($data[0][1])) . '  ' . implode('', array_map(function($v) {
                return chr($v);
            }, array_values($data[0][1])));

        for($i = 1; $i < $count; ++$i) {
            $node = array_keys($data[$i][1]);
            $position = array_values($data[$i][1]);

            $data[$i] = $data[$i][0] . '  ' . implode('', $node) . '  ' . implode('', array_map(function($v) {
                    return chr($v + 12);
                }, $position));
        }
        
        file_put_contents($output, implode("\n", $data));

        return true;
    }



}