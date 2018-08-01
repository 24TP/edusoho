<?php

namespace ApiBundle\Api\Resource\Testpaper;

use ApiBundle\Api\Resource\Course\CourseTaskFilter;
use ApiBundle\Api\Resource\Filter;

class TestpaperFilter extends Filter
{
    protected $publicFields = array('testpaper', 'items', 'task');

    protected function publicFields(&$data)
    {
        if (!empty($data['task'])) {
            $tasKFilter = new CourseTaskFilter();
            $tasKFilter->filter($data['task']);
        }

        if (!empty($data['items'])) {
            foreach ($data['items'] as $questionType => &$questions) {
                $questions = array_values($questions);
                foreach ($questions as &$question) {
                    $itemFilter = new TestpaperItemFilter();
                    $itemFilter->filter($question);
                }
            }
        }
    }
}
