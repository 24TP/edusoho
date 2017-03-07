<?php

namespace Topxia\Api\Resource;

use Silex\Application;
use AppBundle\Common\ArrayToolkit;
use Symfony\Component\HttpFoundation\Request;
use Topxia\Service\Common\ServiceKernel;

class Courses extends BaseResource
{
    public function get(Application $app, Request $request)
    {
        $conditions = $request->query->all();
        $start = $request->query->get('start', 0);
        $limit = $request->query->get('limit', 20);
        if (isset($conditions['cursor'])) {
            $conditions['status']         = 'published';
            $conditions['parentId']       = 0;
            $conditions['updatedTime_GE'] = $conditions['cursor'];
            $order = array('updatedTime' => 'ASC');
        } else {
            $order = array('createdTime' => 'DESC');
        }

        $courses  = $this->getCourseService()->searchCourses($conditions, $order, $start, $limit);
        $courses  = $this->assemblyCourses($courses);
        $courses = $this->filter($courses);

        if (isset($conditions['cursor'])) {
            $next = $this->nextCursorPaging($conditions['cursor'], $start, $limit, $courses);
        } else {
            $next = $this->getCourseService()->searchCourseCount($conditions);
        }
        return $this->wrap($courses, $next);
    }

    public function discoveryColumn(Application $app, Request $request)
    {
        $defaultQuery = array(
            'orderType' => '',
            'type'      => '',
            'showCount' => ''
        );

        $result = array_merge($defaultQuery, $request->query->all());

        if (!empty($result['categoryId'])) {
            $conditions['categoryId'] = $result['categoryId'];
        }

        if ($result['orderType'] == 'hot') {
            $orderBy = 'hitNum';
        } elseif ($result['orderType'] == 'recommend') {
            $orderBy = 'recommendedSeq';
            $conditions['recommended'] = 1;
        } else {
            $orderBy = 'createdTime';
        }

        if ($result['type'] == 'live') {
            $conditions['type'] = 'live';
        } else {
            $conditions['type'] = 'normal';
        }
        if (empty($result['showCount'])) {
            $result['showCount'] = 6;
        }

        $conditions['status']   = 'published';
        $conditions['parentId'] = 0;

        $total   = $this->getCourseService()->searchCourseCount($conditions);
        $courses = $this->getCourseService()->searchCourses($conditions, $orderBy, 0, $result['showCount']);
        $courses = $this->filter($courses);

        foreach ($courses as $key => $value) {
            $courses[$key]['createdTime'] = strval(strtotime($value['createdTime']));
            $courses[$key]['updatedTime'] = strval(strtotime($value['updatedTime']));
            $userIds                      = $courses[$key]['teacherIds'];
            $courses[$key]['teachers']    = $this->getUserService()->findUsersByIds($userIds);
            $courses[$key]['teachers']    = array_values($this->multicallFilter('User', $courses[$key]['teachers']));
        }

        return $this->wrap($courses, min($result['showCount'], $total));
    }

    public function filter($courses)
    {
        $courseIds = ArrayToolkit::column($courses, 'id');
        $courseSets = $this->getCourseSetService()->findCourseSetsByCourseIds($courseIds);
        $courseSets = ArrayToolkit::index($courseSets, 'id');
        foreach($courses as &$course) {
            $course['courseSet'] = $courseSets[$course['courseSetId']];
        }
        return $this->multicallFilter('Course', $courses);
    }

    public function post(Application $app, Request $request)
    {

    }

    protected function assemblyCourses($courses)
    {
        $categoryIds = ArrayToolkit::column($courses, 'categoryId');
        $categories  = $this->getCategoryService()->findCategoriesByIds($categoryIds);
        foreach ($courses as &$course) {
            if (isset($categories[$course['categoryId']])) {
                $course['category'] = array(
                    'id'   => $categories[$course['categoryId']]['id'],
                    'name' => $categories[$course['categoryId']]['name']
                );
            } else {
                $course['category'] = array();
            }
        }

        return $courses;
    }

    protected function getCourseService()
    {
        return $this->createService('Course:CourseService');
    }

    protected function getCourseSetService()
    {
        return $this->createService('Course:CourseSetService');
    }

    protected function getCategoryService()
    {
        return $this->createService('Taxonomy:CategoryService');
    }

    protected function getUserService()
    {
        return $this->createService('User:UserService');
    }
}
