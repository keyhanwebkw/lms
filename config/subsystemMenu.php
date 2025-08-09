<?php

return [
    'Users' => [
        'icon' => 'fa fa-user',
        'routeName' => 'admin.user.parent.list',
        'child' => [
        ],
        'active' => [
            'admin.user.parent.list',
            'admin.user.parent.show',
            'admin.user.parent.create',
            'admin.user.parent.edit',
        ],
    ],
    'Courses' => [
        'icon' => 'fa fa-book',
        'child' => [
            'Courses list' => [
                'routeName' => 'admin.course.list',
                'active' => [
                    'admin.course.list',
                    'admin.course.create',
                    'admin.course.edit',
                    'admin.course.section.list',
                    'admin.course.section.create',
                    'admin.course.section.edit',
                    'admin.course.section.episode.list',
                    'admin.course.section.episode.create',
                    'admin.course.section.episode.edit',
                    'admin.exam.create',
                    'admin.exam.edit',
                    'admin.exam.show',
                    'admin.question.list',
                    'admin.question.create',
                    'admin.question.edit',
                    'admin.assignment.list'
                ],
            ],
            'Course category' => [
                'routeName' => 'admin.courseCategory.list',
                'active' => [
                    'admin.courseCategory.list',
                    'admin.courseCategory.create',
                ],
            ],
        ],
    ],
    'Articles' => [
        'icon' => 'fa fa-newspaper',
        'child' => [
            'Articles list' => [
                'routeName' => 'admin.article.list',
                'active' => [
                    'admin.article.list',
                    'admin.article.create',
                ]
            ],
            'Articles category' => [
                'routeName' => 'admin.articleCategory.list',
                'active' => [
                    'admin.articleCategory.list',
                    'admin.articleCategory.create',
                    'admin.articleCategory.edit',
                ]
            ]
        ]
    ],
    'Comments' => [
        'icon' => 'fa fa-comment',
        'routeName' => 'admin.comment.list',
        'child' => [
        ],
        'active' => [
            'admin.comment.list',
            'admin.comment.edit',
            'admin.comment.create',
        ],
    ],
//    'Cinema' => [
//        'icon' => 'fa fa-film',
//        'child' => [
//            'Movies' => [
//                'routeName' => 'admin.cg.movie.list',
//                'active' => [
//                    'admin.cg.movie.list',
//                    'admin.cg.movie.create',
//                    'admin.cg.movie.edit',
//                ]
//            ],
//            'Movie Categories' => [
//                'routeName' => 'admin.cg.movieCategory.list',
//                'active' => [
//                    'admin.cg.movieCategory.list',
//                    'admin.cg.movieCategory.create',
//                    'admin.cg.movieCategory.edit',
//                ]
//            ],
//        ]
//    ],
    'Faq' => [
        'icon' => 'fa fa-comment',
        'routeName' => 'admin.faq.list',
        'child' => [
        ],
        'active' => [
            'admin.faq.list',
            'admin.faq.create',
        ]
    ],
    'Roles' => [
        'icon' => 'fa fa-star',
        'routeName' => 'admin.role.list',
        'child' => [
        ],
        'active' => [
            'admin.role.list',
            'admin.role.create',
        ]
    ],
    'Managers' => [
        'icon' => 'fa fa-users',
        'child' => [
            'Managers list' => [
                'routeName' => 'admin.manager.list',
                'active' => [
                    'admin.manager.list',
                    'admin.manager.edit',
                ],
            ],
            'Add manager' => [
                'routeName' => 'admin.manager.create',
                'active' => [
                    'admin.manager.create',
                ],
            ],
        ],
    ],
    'Settings' => [
        'icon' => 'fa fa-gears',
        'child' => [
            'Index page' => [
                'routeName' => 'admin.setting.indexPage',
                'active' => [
                    'admin.setting.indexPage',
                    'admin.setting.indexPage.homeBanners.show',
                    'admin.setting.indexPage.homeContent.show',
                    'admin.setting.indexPage.homeLatestArticles.show',
                    'admin.setting.indexPage.homeLatestCourses.show',
                ]
            ],
        ]
    ],
];

