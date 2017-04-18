<?php

use Sunra\PhpSimple\HtmlDomParser;

class RoboFile extends \Robo\Tasks
{
    const CATEGORIES = [
        'school', 'easy', 'medium', 'hard', 'challenge',
        // This breaks at the moment because of memory limits
        //'extcontest'
    ];

    private function getKey() {
        $json = json_decode(`curl --silent 'https://www.codechef.com/api/user/key'`, true);

        return $json['key'];
    }

    private function parseUrl($url) {
        $contents = file_get_contents($url);

        return HtmlDomParser::str_get_html($contents);
    }

    private function parseCategory($category) {

        $url = 'https://www.codechef.com/problems/' . $category . '/';

        $this->say("Fetching $url");
        $dom = $this->parseUrl($url);

        $links = $dom->find('.problemname a');

        $problems = [];

        $count = count($links);

        $this->say("Found $count problems in $category");

        foreach ($links as $link) {
            $problems[] = basename($link->href);
        }

        $this->taskWriteToFile("_data/$category.json")
            ->text(json_encode($problems))
            ->run();
    }

    private function setCategories($category) {
        if ($category === 'all') {
            return self::CATEGORIES;
        }

        return [$category];
    }

    public function updateProblemlist($category = 'all')
    {
        $this->_cleanDir('_data');

        $categories = $this->setCategories($category);

        foreach ($categories as $category) {
            $this->say("Downloading Category: $category");
            $this->parseCategory($category);
        }
    }

    protected function _cleanEmptyFiles($category) {
        foreach (glob("_problems/$category/*") as $file) {
            $size = stat($file)['size'];
            if($size === 0) {
                unlink($file);
            }
        }
    }

    private function _verifyProblem($category, $problem) {
        $filename = "_problems/$category/$problem.json";
        if (file_exists($filename)) {
            $json = json_decode(file_get_contents($filename));
            return (bool) $json;
        }

        return false;
    }

    public function downloadProblems($category = 'all') {
        $categories = $this->setCategories($category);

        foreach ($categories as $category) {

            @mkdir("_problems/$category");
            $this->_cleanEmptyFiles($category);

            $problems = json_decode(file_get_contents("_data/$category.json"));

            $cat = strtoupper($category);

            if ($cat === 'SCHOOL') {
                $cat = 'PRACTICE';
            }

            // Find problems that are not yet downloaded cleanly
            $problems = array_filter($problems, function($problem) use ($category){
                return (! $this->_verifyProblem($category, $problem));
            });

            $this->say('Downloading ' . count($problems) . ' problems from category:'. $category);

            $chunks = array_chunk($problems, 5);

            foreach ($chunks as $chunk) {
                $task = $this->taskParallelExec();

                foreach ($chunk as $problemname) {
                    $url = "https://www.codechef.com/api/contests/$cat/problems/$problemname";
                    $time = (time() * 1000) - 200;
                    $key = $this->getKey();
                    $filepath = "_problems/$category/$problemname.json";
                    if (! file_exists($filepath)) {
                        $task = $task->process("wget --output-document '$filepath' --header 'Cookie:poll_time=$time;userkey=$key;notification=0' $url");
                        $this->say("Added $url");
                    }
                }

                $task->run();
            }
        }
    }
}

