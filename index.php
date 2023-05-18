<?php

require_once 'vendor/autoload.php';

use QueryParty\Database\QueryBuilder;

/**
 * Exemplos de listagens
 */

$courses = (new QueryBuilder('courses as c'))
    ->select(['c.title', 'c.duration', 'c.level'])
    ->orderBy('c.title', 'ASC')
    ->limit(10)
    ->fetch();

$moreStudents = (new QueryBuilder('courses as c'))
    ->select(['c.title', 'COUNT(e.student_id) AS total_students'])
    ->join('enrollments as e', 'c.id = e.course_id', 'LEFT')
    ->groupBy('c.title')
    ->orderBy('total_students', 'DESC')
    ->fetch();

$searchMoreCourses = (new QueryBuilder('students as s'))
    ->select(['s.name', 'COUNT(e.course_id) AS total_courses'])
    ->join('enrollments as e', 's.id = e.student_id', 'LEFT')
    ->where('s.name', 'LIKE', 'B%')
    ->groupBy('s.id, s.name')
    ->having('total_courses', '>=', 2)
    ->orderBy('total_courses', 'DESC')
    ->fetch();

/**
 * Exemplo de criação
 */

 $freshman = (new QueryBuilder('students'))
     ->insert(['id' => 7, 'name' => 'Mark Zuckerberg', 'email' => 'mark.zuckerberg@snapchat.com'])
     ->execute();
 
/**
 * Exemplo de atualização
 */

 $repair = (new QueryBuilder('students'))
     ->update(['email' => 'mark.zuckerberg@metaplatforms.com'])
     ->where('id', '=', 6)
     ->execute();

/**
 * Exemplo de exclusão
 */

$remove = (new QueryBuilder('students'))
    ->delete()
    ->where('id', '=', 7)
    ->execute();
