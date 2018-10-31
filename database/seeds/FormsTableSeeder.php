<?php
use App\Form;	
use Illuminate\Database\Seeder;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Form::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 30; $i++) {
            Form::create([
                "name" => "Form" . ($i + 1),
                "school_id" => $faker->randomDigitNotNull,
                "steps" => [
                		"step1" => [
                			"label" => "Step 1 Fields",
                			"fields" => [
                				"field1" => [
                					"name" => "program",
                					"label" => "Which Program are you interested in?",
                					"placeholder" => "",
                					"type" => "select",
                					"options" => [
                						"option1" => [
                							"name" => "Master of Business Administration",
                							"value" => "MBA"
                						],
                						"option2" => [
                							"name" => "Master of Science in Finance",
                							"value" => "MSF"
                						],
                						"option3" => [
                							"name" => "Master of Science in Taxation",
                							"value" => "MST"
                						]
                					],
                					"validation" => [
                						"required"
                					]
                				],
					            "field2" => [
					            	"name" => "fname",
                					"label" => "First Name",
                					"placeholder" => "First Name",
                					"type" => "text",
                					"options" => [],
                					"validation" => [
                						"required", "fname"
                					]
					            ],
					            "field3" => [
					            	"name" => "lname",
                					"label" => "Last Name",
                					"placeholder" => "Last Name",
                					"type" => "text",
                					"options" => [],
                					"validation" => [
                						"required", "lname"
                					]
					            ]
                			]
                		],
                		"step2" => [
                			"label" => "Step 2 Fields",
                			"fields" => [
                				"field1" => [
                					"name" => "program",
                					"label" => "Which Program are you interested in?",
                					"placeholder" => "",
                					"type" => "select",
                					"options" => [
                						"option1" => [
                							"name" => "Master of Business Administration",
                							"value" => "MBA"
                						],
                						"option2" => [
                							"name" => "Master of Science in Finance",
                							"value" => "MSF"
                						],
                						"option3" => [
                							"name" => "Master of Science in Taxation",
                							"value" => "MST"
                						]
                					],
                					"validation" => [
                						"required"
                					]
                				],
					            "field2" => [
					            	"name" => "fname",
                					"label" => "First Name",
                					"placeholder" => "First Name",
                					"type" => "text",
                					"options" => [],
                					"validation" => [
                						"required", "fname"
                					]
					            ]
                			]
                		],
                		"step3" => [
                			"label" => "Step 3 Fields",
                			"fields" => [
                				"field1" => [
                					"name" => "program",
                					"label" => "Which Program are you interested in?",
                					"placeholder" => "",
                					"type" => "select",
                					"options" => [
                						"option1" => [
                							"name" => "Master of Business Administration",
                							"value" => "MBA"
                						],
                						"option2" => [
                							"name" => "Master of Science in Finance",
                							"value" => "MSF"
                						],
                						"option3" => [
                							"name" => "Master of Science in Taxation",
                							"value" => "MST"
                						]
                					],
                					"validation" => [
                						"required"
                					]
                				],
					            "field2" => [
					            	"name" => "fname",
                					"label" => "First Name",
                					"placeholder" => "First Name",
                					"type" => "text",
                					"options" => [],
                					"validation" => [
                						"required", "fname"
                					]
					            ],
					            "field3" => [
					            	"name" => "lname",
                					"label" => "Last Name",
                					"placeholder" => "Last Name",
                					"type" => "text",
                					"options" => [],
                					"validation" => [
                						"required", "lname"
                					]
					            ]
                			]
                		]
				]
            ]);
        }
    }
}
