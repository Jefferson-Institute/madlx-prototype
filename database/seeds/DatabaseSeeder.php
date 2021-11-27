<?php

use Illuminate\Database\Seeder;
use App\Visualization;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Visualization::create(
            [
              "authority"=>"LMS",
              "background"=>"#FFFFFF",
              "icon"=>null,
              "xtitle"=>null,
              "ytitle"=>null,
              "legend"=>1,
              "viz_state"=>'{"measures":["Statements.count"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
              "pivot"=>'{}',
              "title"=>"Statements",
              "type"=>"counter",
              "x"=>0,
              "y"=>0,
              "w"=>4,
              "h"=>5,
              "curve"=>0
            ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>'el-icon-user-solid',
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.Users"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Users",
            "type"=>"counter",
            "x"=>8,
            "y"=>0,
            "w"=>4,
            "h"=>5,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>'el-icon-user-solid',
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.Courses"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Courses",
            "type"=>"counter",
            "x"=>4,
            "y"=>0,
            "w"=>4,
            "h"=>5,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.objectDefinitionNameUnd","Statements.contextContextActivitiesParent"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.contextContextActivitiesParent","Statements.objectDefinitionNameUnd"],"y":["measures","Statements.verbDisplayEn"]}',
            "title"=>"Activities",
            "type"=>"sunburst",
            "x"=>0,
            "y"=>5,
            "w"=>6,
            "h"=>32,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Total statements",
            "type"=>"line",
            "x"=>6,
            "y"=>5,
            "w"=>6,
            "h"=>16,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"year"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[["Statements.count","desc"]]}',
            "pivot"=>'{"x":["Statements.actorName"],"y":["Statements.timestamp.year","measures"]}',
            "title"=>"Most active users",
            "type"=>"column",
            "x"=>6,
            "y"=>21,
            "w"=>6,
            "h"=>16,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.Users"],"dimensions":["Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]},{"member":"Statements.objectDefinitionNameUnd","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.objectDefinitionNameUnd"]}',
            "title"=>"Number of users per learning objective",
            "type"=>"stacked",
            "x"=>0,
            "y"=>37,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.ScoreAll"],"dimensions":["Statements.actorName","Statements.Course"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]},{"member":"Statements.Course","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.actorName"],"y":["measures","Statements.Course"]}',
            "title"=>"Scores per user by course",
            "type"=>"column",
            "x"=>0,
            "y"=>54,
            "w"=>12,
            "h"=>18,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["Statements.actorName","measures"]}',
            "title"=>"Total statements by user",
            "type"=>"line",
            "x"=>0,
            "y"=>72,
            "w"=>6,
            "h"=>17,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName","Statements.verbDisplayEn","Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"y":["measures","Statements.verbDisplayEn","Statements.actorName","Statements.objectDefinitionNameUnd"],"x":["Statements.timestamp.day"]}',
            "title"=>"Users activities per learning objective",
            "type"=>"area",
            "x"=>6,
            "y"=>72,
            "w"=>6,
            "h"=>17,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]},{"member":"Statements.objectDefinitionNameAll","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.objectDefinitionNameUnd"]}',
            "title"=>"How has activity changed over time?",
            "type"=>"stacked",
            "x"=>0,
            "y"=>89,
            "w"=>12,
            "h"=>18,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"dimensions":["Statements.Course"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.Course"]}',
            "title"=>"NUMBER OF ATTEMPTS WITH MAXIMUM RESULT",
            "type"=>"column",
            "x"=>0,
            "y"=>107,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.verbDisplayEn"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.verbDisplayEn"],"y":["measures"]}',
            "title"=>"MOST POPULAR VERBS",
            "type"=>"pie",
            "x"=>0,
            "y"=>124,
            "w"=>4,
            "h"=>19,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day","dateRange":"Last 30 days"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[["Statements.count","desc"]]}',
            "pivot"=>'{"x":["Statements.actorName"],"y":["measures","Statements.timestamp.day"]}',
            "title"=>"USER ENGAGEMENT LEADERBOARD - LAST 30 DAYS",
            "type"=>"column",
            "x"=>4,
            "y"=>124,
            "w"=>8,
            "h"=>19,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.Course"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]},{"member":"Statements.Course","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{"y":["measures"],"x":["Statements.Course"]}',
            "title"=>"WHAT ARE THE MOST POPULAR COURSES?",
            "type"=>"pie",
            "x"=>0,
            "y"=>143,
            "w"=>4,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"HOW HAS SCORE CHANGED OVER TIME?",
            "type"=>"area",
            "x"=>4,
            "y"=>143,
            "w"=>8,
            "h"=>17,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"dimensions":["Statements.Course"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]},{"member":"Statements.Course","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"AVERAGE SCORES PER COURSE",
            "type"=>"table",
            "x"=>0,
            "y"=>160,
            "w"=>4,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":12,"order":[]}',
            "pivot"=>'{}',
            "title"=>"WHO ARE THE MOST ACTIVE PEOPLE?",
            "type"=>"column",
            "x"=>4,
            "y"=>160,
            "w"=>8,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore","Statements.count"],"dimensions":["Statements.actorName"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":12,"order":[]}',
            "pivot"=>'{}',
            "title"=>"TOP USERS TOTAL STATEMENTS VS AVERAGE SCORE",
            "type"=>"column",
            "x"=>0,
            "y"=>177,
            "w"=>12,
            "h"=>18,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"LMS",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"dimensions":["Statements.id","Statements.actorName","Statements.verbDisplayEn","Statements.objectId","Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["LMS"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Activity log",
            "type"=>"table",
            "x"=>0,
            "y"=>195,
            "w"=>12,
            "h"=>18,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Statements",
            "type"=>"counter",
            "x"=>0,
            "y"=>0,
            "w"=>4,
            "h"=>6,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"HOW HAS SCORE CHANGED OVER TIME?",
            "type"=>"area",
            "x"=>4,
            "y"=>142,
            "w"=>8,
            "h"=>18,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore","Statements.count"],"dimensions":["Statements.actorName"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"TOP USERS TOTAL STATEMENTS VS AVERAGE SCORE",
            "type"=>"area",
            "x"=>0,
            "y"=>177,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"dimensions":["Statements.Course"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]},{"member":"Statements.Course","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"AVERAGE SCORES PER COURSE",
            "type"=>"table",
            "x"=>0,
            "y"=>160,
            "w"=>4,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"WHO ARE THE MOST ACTIVE PEOPLE?",
            "type"=>"column",
            "x"=>4,
            "y"=>160,
            "w"=>8,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.Course"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]},{"member":"Statements.Course","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"WHAT ARE THE MOST POPULAR COURSES?",
            "type"=>"pie",
            "x"=>0,
            "y"=>142,
            "w"=>4,
            "h"=>18,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day","dateRange":"Last 30 days"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.actorName"],"y":["measures","Statements.timestamp.day"]}',
            "title"=>"USER ENGAGEMENT LEADERBOARD - LAST 30 DAYS",
            "type"=>"column",
            "x"=>4,
            "y"=>125,
            "w"=>8,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.verbDisplayEn"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"MOST POPULAR VERBS",
            "type"=>"pie",
            "x"=>0,
            "y"=>125,
            "w"=>4,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"dimensions":["Statements.Course"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]},{"member":"Statements.Courses","operator":"set","values":[null]},{"member":"Statements.Course","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.Course"]}',
            "title"=>"NUMBER OF ATTEMPTS WITH MAXIMUM RESULT",
            "type"=>"column",
            "x"=>0,
            "y"=>108,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>'el-icon-user-solid',
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.Users"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Users",
            "type"=>"counter",
            "x"=>8,
            "y"=>0,
            "w"=>4,
            "h"=>6,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.Courses"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Courses",
            "type"=>"counter",
            "x"=>4,
            "y"=>0,
            "w"=>4,
            "h"=>6,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]},{"member":"Statements.objectDefinitionNameAll","operator":"set","values":[null]}],"limit":1000,"order":[["Statements.count","asc"]]}',
            "pivot"=>'{"y":["measures","Statements.objectDefinitionNameUnd"],"x":["Statements.timestamp.day"]}',
            "title"=>"How has activity changed over time?",
            "type"=>"stacked",
            "x"=>0,
            "y"=>91,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName","Statements.verbDisplayEn","Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.verbDisplayEn","Statements.actorName","Statements.objectDefinitionNameUnd"]}',
            "title"=>"Users activities per learning objective",
            "type"=>"area",
            "x"=>6,
            "y"=>74,
            "w"=>6,
            "h"=>17,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.Users"],"dimensions":["Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]},{"member":"Statements.objectDefinitionNameAll","operator":"set","values":[null]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.objectDefinitionNameUnd"]}',
            "title"=>"Number of users per learning objective",
            "type"=>"stacked",
            "x"=>0,
            "y"=>40,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.actorName"]}',
            "title"=>"Total statements by user",
            "type"=>"line",
            "x"=>0,
            "y"=>74,
            "w"=>6,
            "h"=>17,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.objectDefinitionNameUnd","Statements.contextContextActivitiesParent"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.contextContextActivitiesParent","Statements.objectDefinitionNameUnd"],"y":["measures"]}',
            "title"=>"Activities",
            "type"=>"sunburst",
            "x"=>0,
            "y"=>6,
            "w"=>6,
            "h"=>34,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Total statements",
            "type"=>"line",
            "x"=>6,
            "y"=>6,
            "w"=>6,
            "h"=>17,
            "curve"=>1
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.count"],"dimensions":["Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"year"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[["Statements.count","desc"]]}',
            "pivot"=>'{"x":["Statements.actorName"],"y":["measures","Statements.timestamp.year"]}',
            "title"=>"Most active users",
            "type"=>"column",
            "x"=>6,
            "y"=>23,
            "w"=>6,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.ScoreAll"],"dimensions":["Statements.actorName","Statements.Course"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.actorName"],"y":["measures","Statements.Course"]}',
            "title"=>"Scores per user by course",
            "type"=>"column",
            "x"=>0,
            "y"=>57,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Mobile APP",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"dimensions":["Statements.id","Statements.actorName","Statements.verbDisplayEn","Statements.objectId","Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Mobile APP"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Activity log",
            "type"=>"table",
            "x"=>0,
            "y"=>194,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>'el-icon-user-solid',
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.Users"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Users",
            "type"=>"counter",
            "x"=>8,
            "y"=>0,
            "w"=>4,
            "h"=>5,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>'el-icon-s-marketing',
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.Objectives"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Objectives",
            "type"=>"counter",
            "x"=>4,
            "y"=>0,
            "w"=>4,
            "h"=>5,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"dimensions":["Statements.objectDefinitionNameUnd"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.objectDefinitionNameUnd"],"y":["measures","Statements.actorName"]}',
            "title"=>"Average scores",
            "type"=>"rose",
            "x"=>0,
            "y"=>5,
            "w"=>4,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>0,
            "viz_state"=>'{"measures":["Statements.ScoreAll"],"dimensions":["Statements.objectDefinitionNameUnd","Statements.actorName"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[]}',
            "pivot"=>'{"x":["Statements.timestamp.day"],"y":["measures","Statements.objectDefinitionNameUnd","Statements.actorName"]}',
            "title"=>"Timeline",
            "type"=>"line",
            "x"=>0,
            "y"=>22,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.coursesAVGScore"],"dimensions":["Statements.actorName"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[["Statements.coursesAVGScore","desc"]]}',
            "pivot"=>'{}',
            "title"=>"Top users",
            "type"=>"column",
            "x"=>4,
            "y"=>5,
            "w"=>8,
            "h"=>17,
            "curve"=>0
          ]
        );

        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"measures":["Statements.count"],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Statements",
            "type"=>"counter",
            "x"=>0,
            "y"=>0,
            "w"=>4,
            "h"=>5,
            "curve"=>0
          ]
        );
        Visualization::create(
          [
            "authority"=>"Observers",
            "background"=>"#FFFFFF",
            "icon"=>null,
            "xtitle"=>null,
            "ytitle"=>null,
            "legend"=>1,
            "viz_state"=>'{"dimensions":["Statements.id","Statements.actorName","Statements.verbDisplayEn","Statements.objectId","Statements.objectDefinitionNameUnd"],"timeDimensions":[{"dimension":"Statements.timestamp","granularity":"day"}],"filters":[{"member":"Statements.authorityName","operator":"equals","values":["Observers"]}],"limit":1000,"order":[]}',
            "pivot"=>'{}',
            "title"=>"Activity log",
            "type"=>"table",
            "x"=>0,
            "y"=>39,
            "w"=>12,
            "h"=>17,
            "curve"=>0
          ]
        );

    }
}
